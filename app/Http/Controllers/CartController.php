<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $cartItem) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'product' => $product,
                    'quantity' => $cartItem['quantity'],
                    'sugar_level' => $cartItem['sugar_level'] ?? 'normal',
                    'ice_level' => $cartItem['ice_level'] ?? 'normal',
                    'toppings' => $cartItem['toppings'] ?? [],
                    'notes' => $cartItem['notes'] ?? '',
                    'subtotal' => $product->price * $cartItem['quantity'],
                ];
                $subtotal += $product->price * $cartItem['quantity'];
            }
        }

        $tax = ($subtotal * config('app.tax_percentage', 10)) / 100;
        $serviceCharge = config('app.service_charge', 2000);
        $total = $subtotal + $tax + $serviceCharge;
        $discount = session()->get('discount', 0);
        $total -= $discount;

        return view('cart.index', compact('items', 'subtotal', 'tax', 'serviceCharge', 'discount', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'sugar_level' => 'nullable|string',
            'ice_level' => 'nullable|string',
            'toppings' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $product = Product::find($request->product_id);

        if ($product->isOutOfStock()) {
            return response()->json(['message' => 'Produk tidak tersedia'], 422);
        }

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $request->quantity;
        } else {
            $cart[$productId] = [
                'quantity' => $request->quantity,
                'sugar_level' => $request->sugar_level,
                'ice_level' => $request->ice_level,
                'toppings' => $request->toppings ?? [],
                'notes' => $request->notes,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'message' => 'Ditambahkan ke keranjang',
            'cartCount' => count($cart),
        ]);
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher || !$voucher->isValid()) {
            return response()->json(['message' => 'Kode voucher tidak valid'], 422);
        }

        $cart = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cart as $productId => $cartItem) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal += $product->price * $cartItem['quantity'];
            }
        }

        $discount = $voucher->calculateDiscount($subtotal);

        session()->put('voucher_code', $request->code);
        session()->put('discount', $discount);

        return response()->json([
            'message' => 'Kode voucher berhasil digunakan',
            'discount' => $discount,
        ]);
    }
}
