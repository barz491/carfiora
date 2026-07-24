<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $orders = Order::with('user', 'items.product', 'payment')
                       ->latest()
                       ->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'payment');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,cooking,ready,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        if ($request->status === 'completed') {
            $order->update(['completed_at' => now()]);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function cancel(Order $order)
    {
        if ($order->status === 'completed' || $order->status === 'cancelled') {
            return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan');
    }
}
