<?php

namespace App\Services;

class CartService
{
    /**
     * Add item to cart (without variations)
     */
    public static function addItem(array &$cart, $product, int $quantity = 1): void
    {
        $itemKey = "item_{$product->id}";

        if (isset($cart['items'][$itemKey])) {
            $cart['items'][$itemKey]['quantity'] += $quantity;
        } else {
            $cart['items'][$itemKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'item_price' => (float) $product->price, // ✅ ADD THIS
                'variation' => null, // ✅ ADD THIS
                'variation_price' => 0, // ✅ ADD THIS
                'notes' => '', // ✅ ADD THIS
                'subtotal' => (float) $product->price * $quantity,
            ];
        }

        self::recalculate($cart);
    }

    public static function addItemWithVariation(
        array &$cart,
        $product,
        int $quantity = 1,
        ?array $variation = null,
        string $notes = ''
    ): void {
        // Create unique key based on product + variation + notes
        $variationKey = $variation ? $variation['id'] : 'no_var';
        $notesHash = $notes ? md5($notes) : 'no_notes';
        $itemKey = "item_{$product->id}_{$variationKey}_{$notesHash}";

        $variationPrice = $variation ? $variation['price'] : 0;
        $variationName = $variation ? $variation['name'] : null;
        $variationId = $variation ? $variation['id'] : null;
        $itemPrice = $product->price + $variationPrice;

        if (isset($cart['items'][$itemKey])) {
            $cart['items'][$itemKey]['quantity'] += $quantity;
            $cart['items'][$itemKey]['subtotal'] = $cart['items'][$itemKey]['item_price'] * $cart['items'][$itemKey]['quantity'];
        } else {
            $cart['items'][$itemKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'variation' => $variationName,
                'variation_id' => $variationId, // ✅ Store variation ID
                'variation_price' => $variationPrice,
                'notes' => $notes,
                'item_price' => $itemPrice,
                'subtotal' => $itemPrice * $quantity,
            ];
        }

        self::recalculate($cart);
    }

    /**
     * Remove item from cart
     */
    public static function removeItem(array &$cart, int $itemId): void
    {
        $itemKey = "item_{$itemId}";

        if (isset($cart['items'][$itemKey])) {
            unset($cart['items'][$itemKey]);
            self::recalculate($cart);
        }
    }

    /**
     * Update item quantity
     */
    public static function updateQuantity(array &$cart, int $itemId, int $quantity): void
    {
        $itemKey = "item_{$itemId}";

        if (isset($cart['items'][$itemKey])) {
            if ($quantity <= 0) {
                self::removeItem($cart, $itemId);
            } else {
                $cart['items'][$itemKey]['quantity'] = $quantity;
                $cart['items'][$itemKey]['subtotal'] = $cart['items'][$itemKey]['price'] * $quantity;
                self::recalculate($cart);
            }
        }
    }

    /**
     * Adjust quantity by delta (increment/decrement)
     */
    public static function adjustQuantity(array &$cart, int $itemId, int $delta): void
    {
        $itemKey = "item_{$itemId}";

        if (isset($cart['items'][$itemKey])) {
            $newQuantity = $cart['items'][$itemKey]['quantity'] + $delta;
            self::updateQuantity($cart, $itemId, $newQuantity);
        }
    }

    /**
     * Apply discount to cart
     */
    public static function applyDiscount(array &$cart, string $type, float $value): void
    {
        $cart['discount']['type'] = $type; // 'percentage' or 'fixed'
        $cart['discount']['value'] = max(0, $value);

        self::recalculate($cart);
    }

    /**
     * Remove discount from cart
     */
    public static function removeDiscount(array &$cart): void
    {
        $cart['discount']['type'] = 'percentage';
        $cart['discount']['value'] = 0;

        self::recalculate($cart);
    }

    /**
     * Recalculate all cart totals
     */
    public static function recalculate(array &$cart): void
    {
        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart['items'] as $itemKey => &$item) {
            // Recalculate item subtotal based on item_price (includes variation)
            $item['subtotal'] = $item['item_price'] * $item['quantity'];
            $subtotal += $item['subtotal'];
        }

        // Calculate discount
        $discountAmount = 0;
        if (isset($cart['discount']) && $cart['discount']['value'] > 0) {
            if ($cart['discount']['type'] === 'percentage') {
                $discountAmount = $subtotal * ($cart['discount']['value'] / 100);
            } else {
                $discountAmount = $cart['discount']['value'];
            }

            // Discount cannot exceed subtotal
            $discountAmount = min($discountAmount, $subtotal);
        }

        // Calculate tax (if needed)
        $taxRate = $cart['tax_rate'] ?? 0;
        $taxAmount = ($subtotal - $discountAmount) * ($taxRate / 100);

        // Update totals
        $cart['totals'] = [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discountAmount, 2),
            'tax' => round($taxAmount, 2),
            'grand_total' => round($subtotal - $discountAmount + $taxAmount, 2),
        ];

        // Update item count
        $cart['item_count'] = array_sum(array_column($cart['items'], 'quantity'));
    }

    /**
     * Clear entire cart
     */
    public static function clear(array &$cart): void
    {
        $cart = self::initialize();
    }

    /**
     * Initialize empty cart structure
     */
    public static function initialize(float $taxRate = 0): array
    {
        return [
            'items' => [],
            'discount' => [
                'type' => 'percentage',
                'value' => 0,
            ],
            'tax_rate' => $taxRate,
            'totals' => [
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'grand_total' => 0,
            ],
            'item_count' => 0,
        ];
    }

    /**
     * Get cart items as array (for easier rendering)
     */
    public static function getItems(array $cart): array
    {
        return $cart['items'] ?? [];
    }

    /**
     * Check if cart is empty
     */
    public static function isEmpty(array $cart): bool
    {
        return empty($cart['items']);
    }

    /**
     * Get cart summary for order creation
     */
    public static function getSummary(array $cart): array
    {
        return [
            'items' => self::getItems($cart),
            'item_count' => $cart['item_count'],
            'subtotal' => $cart['totals']['subtotal'],
            'discount_type' => $cart['discount']['type'],
            'discount_value' => $cart['discount']['value'],
            'discount_amount' => $cart['totals']['discount'],
            'tax_rate' => $cart['tax_rate'],
            'tax_amount' => $cart['totals']['tax'],
            'grand_total' => $cart['totals']['grand_total'],
        ];
    }
}
