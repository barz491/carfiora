@extends('layouts.app')

@section('title', 'Keranjang - Carfiora')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Keranjang Belanja</h1>

    @if(count($items) > 0)
    <div class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-bold">Produk</th>
                            <th class="px-6 py-3 text-left text-sm font-bold">Harga</th>
                            <th class="px-6 py-3 text-left text-sm font-bold">Qty</th>
                            <th class="px-6 py-3 text-left text-sm font-bold">Subtotal</th>
                            <th class="px-6 py-3 text-left text-sm font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($items as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item['product']->name }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item['product']->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $item['quantity'] }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('cart.remove', $item['product']->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Ringkasan</h2>
                <div class="space-y-3 border-b pb-4">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak (10%)</span>
                        <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Layanan</span>
                        <span>Rp {{ number_format($serviceCharge, 0, ',', '.') }}</span>
                    </div>
                    @if($discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon</span>
                        <span>-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                <div class="flex justify-between text-xl font-bold mt-4">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="w-full mt-6 bg-amber-600 text-white py-3 rounded-lg hover:bg-amber-700 transition text-center block">
                    Lanjut ke Pembayaran
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
        <p class="text-gray-600 dark:text-gray-300 mb-4">Keranjang Anda kosong</p>
        <a href="{{ route('products.index') }}" class="inline-block bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700 transition">
            Lanjut Belanja
        </a>
    </div>
    @endif
</div>
@endsection
