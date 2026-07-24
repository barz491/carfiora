@extends('layouts.app')

@section('title', 'Carfiora - Premium Cafe & Coffee')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-amber-50 to-white dark:from-gray-900 dark:to-gray-800">
    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    Selamat datang di <span class="text-amber-600">Carfiora</span>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                    Nikmati pengalaman kopi premium dengan suasana yang nyaman dan pelayanan terbaik.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('products.index') }}" class="bg-amber-600 text-white px-8 py-3 rounded-lg hover:bg-amber-700 transition">Pesan Sekarang</a>
                    <a href="#promo" class="border-2 border-amber-600 text-amber-600 px-8 py-3 rounded-lg hover:bg-amber-50 transition">Lihat Promo</a>
                </div>
            </div>
            <div class="relative">
                <div class="w-full h-96 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl"></div>
            </div>
        </div>
    </section>

    <!-- Promos Section -->
    @if($promos->count() > 0)
    <section id="promo" class="bg-white dark:bg-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-12 text-center">Promo Spesial</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($promos as $promo)
                <div class="bg-gradient-to-br from-amber-100 to-amber-50 dark:from-gray-700 dark:to-gray-600 p-8 rounded-xl">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $promo->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ $promo->description }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Best Sellers -->
    @if($bestSellers->count() > 0)
    <section class="bg-gray-50 dark:bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-12 text-center">Best Sellers</h2>
            <div class="grid md:grid-cols-4 gap-8">
                @foreach($bestSellers->take(4) as $product)
                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700"></div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                        <p class="text-amber-600 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <button onclick="addToCart({{ $product->id }})" class="w-full mt-4 bg-amber-600 text-white py-2 rounded-lg hover:bg-amber-700 transition">
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Categories -->
    @if($categories->count() > 0)
    <section class="bg-white dark:bg-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-12 text-center">Kategori</h2>
            <div class="grid md:grid-cols-5 gap-6">
                @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="text-center p-6 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-amber-100 dark:hover:bg-gray-600 transition">
                    <div class="text-4xl mb-2">{{ $category->icon ?? '☕' }}</div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $category->name }}</h3>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
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
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
