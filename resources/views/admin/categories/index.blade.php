@extends('layouts.app')

@section('title', 'Manage Categories - Carfiora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            ➕ Add Category
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
                    <th class="px-6 py-3 text-left text-sm font-bold">Description</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Products</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4 font-bold">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ Str::limit($category->description, 50) }}</td>
                    <td class="px-6 py-4">{{ $category->products_count ?? 0 }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                        No categories found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
