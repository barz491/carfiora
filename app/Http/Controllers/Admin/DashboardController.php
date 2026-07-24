<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $todayRevenue = Payment::where('status', 'completed')
                               ->whereDate('created_at', today())
                               ->sum('amount');
        $monthlyRevenue = Payment::where('status', 'completed')
                                 ->whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->sum('amount');
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        $topProducts = Product::with('orderItems')
                             ->get()
                             ->sortByDesc(function ($product) {
                                 return $product->orderItems->sum('quantity');
                             })
                             ->take(5);

        $recentOrders = Order::with('user', 'items.product')
                            ->latest()
                            ->take(10)
                            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'todayRevenue',
            'monthlyRevenue',
            'totalProducts',
            'totalUsers',
            'pendingOrders',
            'completedOrders',
            'topProducts',
            'recentOrders'
        ));
    }
}
