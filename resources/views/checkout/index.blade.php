@extends('layouts.app')

@section('title', 'Checkout - Carfiora')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-bold mb-2">Nama Pemesan</label>
            <input type="text" name="customer_name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-amber-600" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold mb-2">Nomor Telepon</label>
            <input type="tel" name="customer_phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-amber-600" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold mb-2">Jenis Pesanan</label>
            <select name="order_type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-amber-600">
                <option value="dine_in">Makan di Tempat</option>
                <option value="take_away">Bawa Pulang</option>
            </select>
        </div>

        <div class="mb-6" id="tableNumberField" style="display: none;">
            <label class="block text-sm font-bold mb-2">Nomor Meja</label>
            <input type="number" name="table_number" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-amber-600" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold mb-2">Catatan (Opsional)</label>
            <textarea name="notes" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-amber-600"></textarea>
        </div>

        <button type="submit" class="w-full bg-amber-600 text-white py-3 rounded-lg hover:bg-amber-700 transition font-bold text-lg">
            Lanjut ke Pembayaran
        </button>
    </form>
</div>

<script>
document.querySelector('select[name="order_type"]').addEventListener('change', function() {
    const tableField = document.getElementById('tableNumberField');
    tableField.style.display = this.value === 'dine_in' ? 'block' : 'none';
});
</script>
@endsection
