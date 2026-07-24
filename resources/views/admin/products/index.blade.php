@extends('layouts.app')

@section('title', 'Manage Products - Carfiora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            ➕ Add Product
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-bold">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Category</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Price</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Stock</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4 font-bold">{{ $product->name }}</td>
                    <td class="px-6 py-4">{{ $product->category->name }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">{{ $product->stock }} pcs</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                        No products found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
</div>
@endsection
