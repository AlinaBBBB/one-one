<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Страница оформления заказа
     */
    public function create()
    {
        $cartSummary = CartHelper::getCartSummary();
        
        if ($cartSummary['items']->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }
        
        return view('orders.create', [
            'cartItems' => $cartSummary['items'],
            'subtotal' => $cartSummary['subtotal'],
            'deliveryPrice' => $cartSummary['deliveryPrice'],
            'total' => $cartSummary['total'],
            'user' => Auth::user()
        ]);
    }

    /**
     * Сохранить заказ
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:500',
            'payment_method' => 'required|in:card,cash,online',
        ]);

        $cartSummary = CartHelper::getCartSummary();
        
        if ($cartSummary['items']->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_amount' => $cartSummary['total'],
            'status' => 'new',
            'delivery_address' => $request->delivery_address,
            'payment_method' => $request->payment_method,
            'phone' => $request->phone,
        ]);

        foreach ($cartSummary['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price ?? 0,
                'size' => $item->size ?? null,
                'color' => $item->color ?? null,
            ]);
        }

        CartHelper::clearCart();

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Заказ #' . $order->order_number . ' оформлен!');
    }

    /**
     * Просмотр заказа
     */
    public function show($id)
    {
        $order = Order::with(['items.product.images'])->findOrFail($id);
        
        // Проверка доступа
        if ($order->user_id !== Auth::id() && (Auth::user()->role ?? 0) !== 1) {
            abort(403);
        }
        
        return view('orders.show', compact('order'));
    }

    /**
     * История заказов
     */
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('orders.history', compact('orders'));
    }
}