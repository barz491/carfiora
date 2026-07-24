@extends('layouts.app')

@section('title', 'Admin Dashboard - Carfiora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Admin Dashboard</h1>

    <!-- Stats Grid -->
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                </div>
                <div class="text-4xl text-blue-600">📦</div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="text-4xl text-green-600">💰</div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
                </div>
                <div class="text-4xl text-purple-600">👥</div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
                </div>
                <div class="text-4xl text-orange-600">☕</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4">Quick Actions</h2>
        <div class="grid md:grid-cols-4 gap-4">
            <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 transition text-center">
                ➕ Add Category
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 transition text-center">
                ➕ Add Product
            </a>
            <a href="{{ route('admin.promos.create') }}" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 transition text-center">
                ➕ Add Promo
            </a>
            <a href="{{ route('admin.orders.index') }}" class="bg-orange-600 text-white p-4 rounded-lg hover:bg-orange-700 transition text-center">
                📋 View Orders
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-2xl font-bold">Recent Orders</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-bold">Order #</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Customer</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-bold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($recentOrders as $order)
                    <tr>
                        <td class="px-6 py-4 font-mono">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-bold
                                @if($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'ready') bg-blue-100 text-blue-800
                                @elseif($order->status === 'cooking') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                            No orders yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
