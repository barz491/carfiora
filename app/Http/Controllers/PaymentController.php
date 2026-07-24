<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Order $order)
    {
        if (!auth()->check() && $order->user_id !== null) {
            abort(403);
        }

        return view('payment.index', compact('order'));
    }

    public function process(Request $request, Order $order)
    {
        $request->validate([
            'method' => 'required|in:qris,debit,cash',
            'card_name' => 'nullable|string',
            'card_number' => 'nullable|string',
            'card_expiry' => 'nullable|string',
            'card_cvv' => 'nullable|string',
            'cash_amount' => 'nullable|numeric',
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => $request->method,
            'amount' => $order->total,
            'status' => 'completed',
            'transaction_id' => 'TRX-' . strtoupper(uniqid()),
            'paid_at' => now(),
            'metadata' => [
                'card_name' => $request->card_name,
                'cash_amount' => $request->cash_amount,
            ],
        ]);

        $order->update(['status' => 'processing']);

        return redirect()->route('order.success', $order->id);
    }
}
