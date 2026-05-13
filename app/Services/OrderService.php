<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\OrderCoupon;
use App\Models\Item;
use App\Models\Tax;
use App\Models\Address;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Enums\Source;
use App\Enums\TaxType;
use App\Jobs\SendOrderMail;
use App\Jobs\SendOrderSms;
use App\Jobs\SendOrderPush;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderService
{
    public function createFromPosPayload(array $payload): Order
    {
        return DB::transaction(function () use ($payload) {

            $items = [];
            $subtotal = 0;
            $taxTotal = 0;

            foreach ($payload['cart'] as $cartItem) {

                $item = Item::findOrFail($cartItem['product_id']);

                $price = $item->price;
                $variationPrice = 0;
                $variationName = null;

                if ($cartItem['variation_id']) {
                    $variation = $item->variations()
                        ->find($cartItem['variation_id']);
                    if ($variation) {
                        $variationPrice = $variation->price;
                        $variationName = $variation->name;
                    }
                }

                $unitPrice = $price + $variationPrice;
                $lineTotal = $unitPrice * $cartItem['qty'];

                $subtotal += $lineTotal;

                $items[] = [
                    'item_id' => $item->id,
                    'quantity' => $cartItem['qty'],
                    'item_price' => $price,
                    'total_price' => $lineTotal,
                    'variation_id' => $cartItem['variation_id'],
                    'variation_name' => $variationName,
                    'variation_price' => $variationPrice,
                    'instruction' => $cartItem['notes'] ?? null,
                    'branch_id' => Auth::user()->branch_id,
                ];
            }

            $discountType   = $payload['totals']['discount_type'] ?? null;
            $discountValue  = $payload['totals']['discount_value'] ?? 0;
            $discountAmount = $payload['totals']['discount_amount'] ?? 0;

            $orderData = [
                'token_number' => $payload['token'],
                'payment_type' => $payload['payment_type'],
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxTotal,
                'total_amount' => $payload['totals']['grand_total'],
                'paid_amount' => $payload['paid_amount'],
                'change_amount' => $payload['change_due'],
                'items' => $items,
                'branch_id' => Auth::user()->branch_id,
            ];

            return $this->createOrder($orderData);
        });
    }
    /**
     * Create order from cart data
     */
    public function createOrder(array $orderData): Order
    {
        try {
            return DB::transaction(function () use ($orderData) {
                // 1. Create main order (tax already calculated in cart)
                $order = $this->createMainOrder($orderData);

                // 2. Create order items (tax per item for record keeping)
                if (!empty($orderData['items'])) {
                    $this->createOrderItems($order, $orderData['items']);
                }

                // 3. Add delivery address if provided
                if (!empty($orderData['address_id'])) {
                    $this->attachAddress($order, $orderData['address_id']);
                }

                // 4. Add coupon if applied
                if (!empty($orderData['coupon_id'])) {
                    $this->attachCoupon($order, $orderData['coupon_id'], $orderData['discount'] ?? 0);
                }

                // 5. Send notifications
                $this->sendNotifications($order);

                return $order->fresh();
            });
        } catch (Exception $exception) {
            Log::error('Order creation failed', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'data' => $orderData
            ]);

            throw new Exception('Failed to create order: ' . $exception->getMessage(), 422);
        }
    }

    /**
     * Create main order record
     */
    protected function createMainOrder(array $data): Order
    {
        $walkingCustomer = User::defaultCustomer();
        $order = Order::create([
            'token' => $data['token_number'] ?? null,
            'user_id' =>  $walkingCustomer->id,
            'branch_id' => Auth::user()->branch_id,
            'subtotal' => $data['subtotal'] ?? 0,
            'discount' => $data['discount_amount'] ?? 0,
            'delivery_charge' => $data['delivery_charge'] ?? 0,
            'total_tax' => $data['tax_amount'] ?? 0,
            'total' => $data['total_amount'] ?? 0,
            'order_type' => $data['order_type'] ?? OrderType::TAKEAWAY,
            'is_advance_order' => $data['is_advance_order'] ?? 10,
            'address_id' => $data['address_id'] ?? null,
            'delivery_time' => $data['delivery_time'] ?? null,
            'preparation_time' => $data['preparation_time'] ?? 30,
            'coupon_id' => $data['coupon_id'] ?? null,
            'source' => Source::POS,
            'payment_type' => $data['payment_type'] ?? 'Cash',
            'pos_payment_method' => Order::getPaymentTypeId($data['payment_type']),
            'payment_status'   => PaymentStatus::PAID,
            'pos_received_amount' => $data['paid_amount'] ?? 0,
            'status' => OrderStatus::ACCEPT,
            'order_datetime' => now(),
            'discount_type' => $data['discount_type'] ?? null,
            'discount_value' => $data['discount_value'] ?? 0,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'tax_amount' => $data['tax_amount'] ?? 0,
            'total_amount' => $data['total_amount'] ?? 0,
            'pos_received_amount' => $data['paid_amount'] ?? 0,
            'change_amount' => $data['change_amount'] ?? 0,
        ]);

        // Generate order serial number
        $order->order_serial_no = date('dmy') . $order->id;
        $order->save();

        return $order;
    }

    /**
     * Create order items with tax calculations
     */
    protected function createOrderItems(Order $order, array $items): void
    {
        $orderItems = [];

        // Preload items and taxes for better performance
        $itemIds = collect($items)->pluck('item_id')->toArray();
        $itemTaxMap = Item::whereIn('id', $itemIds)->pluck('tax_id', 'id');
        $taxes = Tax::get()->keyBy('id');

        foreach ($items as $item) {
            $taxCalculation = $this->calculateItemTax($item, $itemTaxMap, $taxes);

            // Prepare variation JSON in your format
            $itemVariations = $this->prepareVariationJson($item);

            $orderItems[] = [
                'order_id' => $order->id,
                'branch_id' => $item['branch_id'] ?? null,
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['item_price'],
                'discount' => $item['discount'] ?? 0,
                'tax_name' => $taxCalculation['tax_name'],
                'tax_rate' => $taxCalculation['tax_rate'],
                'tax_type' => $taxCalculation['tax_type'],
                'tax_amount' => $taxCalculation['tax_amount'],
                'item_variations' => json_encode($itemVariations),
                'item_extras' => json_encode($item['item_extras'] ?? []),
                'instruction' => $item['instruction'] ?? null,
                'item_variation_total' => $item['item_variation_total'] ?? 0,
                'item_extra_total' => $item['item_extra_total'] ?? 0,
                'total_price' => $item['total_price'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($orderItems)) {
            OrderItem::insert($orderItems);
        }
    }

    /**
     * Prepare variation JSON in your format
     */
    protected function prepareVariationJson(array $item): array
    {
        $variations = [];

        // If variation data exists from POS
        if (!empty($item['variation_id']) && !empty($item['variation_name'])) {
            $variations[] = [
                'id' => count($variations) + 1,
                'item_id' => $item['item_id'],
                'item_attribute_id' => $item['variation_id'],
                'variation_name' => 'Type', // Or get from config
                'name' => $item['variation_name'],
            ];
        }

        // If old format exists
        if (!empty($item['item_variations'])) {
            if (is_string($item['item_variations'])) {
                $item['item_variations'] = json_decode($item['item_variations'], true);
            }

            if (is_array($item['item_variations'])) {
                $variations = array_merge($variations, $item['item_variations']);
            }
        }

        return $variations;
    }

    /**
     * Calculate tax for individual item
     */
    protected function calculateItemTax(array $item, $itemTaxMap, $taxes): array
    {
        $itemId = $item['item_id'];
        $taxId = $itemTaxMap[$itemId] ?? null;

        if (!$taxId || !isset($taxes[$taxId])) {
            return [
                'tax_name' => null,
                'tax_rate' => 0,
                'tax_type' => TaxType::FIXED,
                'tax_amount' => 0,
            ];
        }

        $tax = $taxes[$taxId];
        $taxType = $tax->type ?? TaxType::FIXED;

        $taxAmount = $taxType === TaxType::FIXED
            ? $tax->tax_rate
            : ($item['total_price'] * $tax->tax_rate) / 100;

        return [
            'tax_name' => $tax->name,
            'tax_rate' => $tax->tax_rate,
            'tax_type' => $taxType,
            'tax_amount' => round($taxAmount, 2),
        ];
    }



    /**
     * Attach delivery address to order
     */
    protected function attachAddress(Order $order, int $addressId): void
    {
        $address = Address::find($addressId);

        if (!$address) {
            Log::warning('Address not found for order', ['address_id' => $addressId, 'order_id' => $order->id]);
            return;
        }

        OrderAddress::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'label' => $address->label,
            'address' => $address->address,
            'apartment' => $address->apartment,
            'latitude' => $address->latitude,
            'longitude' => $address->longitude,
        ]);
    }

    /**
     * Attach coupon to order
     */
    protected function attachCoupon(Order $order, int $couponId, float $discount): void
    {
        if ($couponId <= 0) {
            return;
        }

        OrderCoupon::create([
            'order_id' => $order->id,
            'coupon_id' => $couponId,
            'user_id' => Auth::id(),
            'discount' => $discount,
        ]);
    }

    /**
     * Send order notifications
     */
    protected function sendNotifications(Order $order): void
    {
        $notificationData = [
            'order_id' => $order->id,
            'status' => $order->status,
        ];

        // SendOrderMail::dispatch($notificationData);
        // SendOrderSms::dispatch($notificationData);
        // SendOrderPush::dispatch($notificationData);
    }

    /**
     * Create order from Livewire POS cart
     */
    public function createFromCart(array $cart, array $orderDetails): Order
    {
        $items = collect(CartService::getItems($cart))->map(function ($item, $itemKey) use ($orderDetails) {
            // Parse variation from cart item
            $variationId = null;
            $variationName = null;
            if (!empty($item['variation'])) {
                // Extract variation ID from item key if exists
                // itemKey format: item_{product_id}_{variation_id}_{notes_hash}
                $keyParts = explode('_', $itemKey);
                if (count($keyParts) >= 3 && is_numeric($keyParts[2])) {
                    $variationId = $keyParts[2];
                }
                $variationName = $item['variation'];
            }

            return [
                'item_id' => $item['id'],
                'branch_id' => $orderDetails['branch_id'],
                'quantity' => $item['quantity'],
                'item_price' => $item['price'],
                'total_price' => $item['subtotal'],
                'discount' => 0,
                'variation_id' => $variationId,
                'variation_name' => $variationName,
                'variation_price' => $item['variation_price'] ?? 0,
                'item_variations' => [],
                'item_extras' => [],
                'instruction' => $item['notes'] ?? null,
                'item_variation_total' => $item['variation_price'] ?? 0,
                'item_extra_total' => 0,
            ];
        })->toArray();

        $orderData = [
            'token_number' => $orderDetails['token_number'],
            'payment_type' => $orderDetails['payment_type'],
            'status' => $orderDetails['status'] ?? OrderStatus::PENDING,
            'subtotal' => $cart['totals']['subtotal'],
            'branch_id' => $orderDetails['branch_id'],
            'discount_type' => $cart['discount']['type'],
            'discount_value' => $cart['discount']['value'],
            'discount_amount' => $cart['totals']['discount'],
            'tax_amount' => $cart['totals']['tax'],
            'total_amount' => $cart['totals']['grand_total'],
            'paid_amount' => $orderDetails['paid_amount'],
            'change_amount' => $orderDetails['change_amount'] ?? 0,
            'items' => $items,
            'address_id' => $orderDetails['address_id'] ?? null,
            'coupon_id' => $orderDetails['coupon_id'] ?? null,
        ];

        return $this->createOrder($orderData);
    }
}
