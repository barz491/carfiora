@extends('layouts.app')

@section('title', 'Pembayaran - Carfiora')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8">Pembayaran</h1>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Payment Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
            <h2 class="text-2xl font-bold mb-6">Metode Pembayaran</h2>

            <form method="POST" action="{{ route('payment.process', $order->id) }}" id="paymentForm">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold mb-4">Pilih Metode Pembayaran</label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="method" value="qris" required onchange="togglePaymentMethod('qris')" class="mr-3" />
                            <span class="font-bold">QRIS</span>
                        </label>
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="method" value="debit" required onchange="togglePaymentMethod('debit')" class="mr-3" />
                            <span class="font-bold">Kartu Debit</span>
                        </label>
                        <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="method" value="cash" required onchange="togglePaymentMethod('cash')" class="mr-3" />
                            <span class="font-bold">Tunai</span>
                        </label>
                    </div>
                </div>

                <div id="cardFields" style="display: none;" class="mb-6">
                    <input type="text" name="card_name" placeholder="Nama Pemegang Kartu" class="w-full px-4 py-2 border rounded-lg mb-3" />
                    <input type="text" name="card_number" placeholder="Nomor Kartu" class="w-full px-4 py-2 border rounded-lg mb-3" />
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="card_expiry" placeholder="MM/YY" class="px-4 py-2 border rounded-lg" />
                        <input type="text" name="card_cvv" placeholder="CVV" class="px-4 py-2 border rounded-lg" />
                    </div>
                </div>

                <div id="cashFields" style="display: none;" class="mb-6">
                    <input type="number" name="cash_amount" placeholder="Jumlah Uang" class="w-full px-4 py-2 border rounded-lg" />
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-bold text-lg">
                    Bayar Sekarang
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow p-8">
            <h2 class="text-2xl font-bold mb-6">Ringkasan Pesanan</h2>
            <div class="space-y-4 mb-6 pb-6 border-b">
                @foreach($order->items as $item)
                <div class="flex justify-between">
                    <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            <div class="space-y-2 text-sm mb-4">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pajak</span>
                    <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Biaya Layanan</span>
                    <span>Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Diskon</span>
                    <span>-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            <div class="flex justify-between text-xl font-bold pt-4 border-t">
                <span>Total</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<script>
function togglePaymentMethod(method) {
    document.getElementById('cardFields').style.display = method === 'debit' ? 'block' : 'none';
    document.getElementById('cashFields').style.display = method === 'cash' ? 'block' : 'none';
}
</script>
@endsection
