<?php

namespace App\Livewire\Pos;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


class IndexBeta extends Component
{
    // Cart state
    public array $cart = [];

    public $categories;
    public $products;

    // Success state
    public bool $orderSuccess = false;
    public ?string $orderNumber = null;

    public array $recentOrders = [];

    public function mount()
    {
        $this->categories = $this->getCategories();
        $this->products = $this->getProducts();

        $this->cart = CartService::initialize();
    }

    #[Layout('components.layouts.pos')]
    public function render()
    {
        return view('livewire.pos.index-beta');
    }

    /**
     * Public methods
     */
    public function getProducts()
    {
        return Item::with('media', 'category', 'tax', 'variations', 'variations.itemAttribute')
            ->active()
            ->orderBy('order', 'asc')
            ->get();
    }

    public function getCategories()
    {
        return ItemCategory::select('id', 'name')->active()->orderBy('sort', 'asc')->get();
    }

    /**
     * Actions
     */

    /**
     * Finalize order
     */
    public function finalizeOrder($payload)
    {
        try {
            validator($payload, [
                'cart' => 'required|array|min:1',
                'cart.*.product_id' => 'required|exists:items,id',
                'cart.*.qty' => 'required|integer|min:1',
                'token' => 'required|integer',
                'payment_type' => 'required|in:Cash,Card,UPI',
                'totals.subtotal' => 'required|numeric|min:0',
                'totals.discount_type' => 'nullable|in:percentage,fixed',
                'totals.discount_value' => 'nullable|numeric|min:0',
                'totals.discount_amount' => 'nullable|numeric|min:0',
                'totals.grand_total' => 'required|numeric|min:0',
            ])->validate();

            $order = app(OrderService::class)
                ->createFromPosPayload($payload);

            $this->dispatch('order-finalized', orderId: $order->id);
        } catch (\Throwable $e) {
            report($e);

            $this->dispatch('order-failed', message: 'Order failed. Try again.');
        }
    }

    public function loadRecentOrders()
    {
        $this->recentOrders = Order::query()
            ->where('branch_id', auth()->user()->branch_id)
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'order_serial_no' => $order->order_serial_no,
                'token' => $order->token,
                'total' => $order->total,
                'payment_label' => $order->payment_method_label, // ✅ accessor
                'created_at' => $order->created_at,
            ])
            ->toArray();
    }
}
