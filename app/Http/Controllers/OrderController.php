<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function success(Order $order)
    {
        return view('orders.success', compact('order'));
    }

    public function downloadInvoice(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // TODO: Implement PDF export
        return response()->download('invoice_' . $order->order_number . '.pdf');
    }
}
