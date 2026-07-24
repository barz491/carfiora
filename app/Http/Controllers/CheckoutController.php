<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        return view('checkout.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'order_type' => 'required|in:dine_in,take_away',
            'table_number' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $subtotal = 0;
        foreach ($cart as $productId => $cartItem) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal += $product->price * $cartItem['quantity'];
            }
        }

        $tax = ($subtotal * config('app.tax_percentage', 10)) / 100;
        $serviceCharge = config('app.service_charge', 2000);
        $discount = session()->get('discount', 0);
        $total = $subtotal + $tax + $serviceCharge - $discount;

        // Create Order
        $order = Order::create([
            'user_id' => auth()->id() ?? null,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'type' => $request->order_type,
            'table_number' => $request->table_number,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'notes' => $request->notes,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'service_charge' => $serviceCharge,
            'discount' => $discount,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Create Order Items
        foreach ($cart as $productId => $cartItem) {
            $product = Product::find($productId);
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $cartItem['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $cartItem['quantity'],
                    'sugar_level' => $cartItem['sugar_level'] ?? null,
                    'ice_level' => $cartItem['ice_level'] ?? null,
                    'toppings' => json_encode($cartItem['toppings'] ?? []),
                    'notes' => $cartItem['notes'] ?? null,
                ]);
            }
        }

        session()->forget(['cart', 'discount', 'voucher_code']);

        return redirect()->route('payment.index', $order->id);
    }
}
