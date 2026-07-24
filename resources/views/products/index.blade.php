@extends('layouts.app')

@section('title', 'Menu - Carfiora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Menu</h1>

    <div class="grid md:grid-cols-4 gap-8 mb-8">
        <!-- Filter Sidebar -->
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg">
                <h3 class="text-lg font-bold mb-4">Kategori</h3>
                <div class="space-y-2">
                    <a href="{{ route('products.index') }}" class="block p-2 hover:bg-amber-100 rounded">Semua</a>
                    @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="block p-2 hover:bg-amber-100 rounded">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="md:col-span-3">
            <div class="grid md:grid-cols-3 gap-8">
                @forelse($products as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700"></div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">{{ $product->category->name }}</p>
                        <p class="text-amber-600 font-bold text-lg mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <button onclick="addToCart({{ $product->id }})" class="w-full bg-amber-600 text-white py-2 rounded-lg hover:bg-amber-700 transition">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-600 dark:text-gray-300">Tidak ada produk tersedia</p>
                </div>
                @endforelse
            </div>

            {{ $products->links() }}
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1,
        }),
    })
    .then(response => response.json())
    .then(data => alert(data.message))
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
