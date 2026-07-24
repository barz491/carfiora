@extends('layouts.app')

@section('title', 'Orders Management - Carfiora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Orders Management</h1>

    @if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-bold">Order #</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Customer</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Total</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 font-mono text-sm">{{ $order->order_number }}</td>
                    <td class="px-6 py-4">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-sm {{ $order->type === 'dine_in' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            {{ $order->type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm font-bold
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'ready') bg-blue-100 text-blue-800
                            @elseif($order->status === 'cooking') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-orange-100 text-orange-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                        No orders found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
</div>
@endsection
