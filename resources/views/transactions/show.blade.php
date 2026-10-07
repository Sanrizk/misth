@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('content')

<div class="max-w-2xl mx-auto space-y-4">

    {{-- Invoice Header --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs text-gray-400 mb-1">Nomor Invoice</p>
                <p class="font-mono font-bold text-gray-800">{{ $transaction->invoice_number }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($transaction->status === 'completed') bg-green-100 text-green-700
                @elseif($transaction->status === 'paid') bg-blue-100 text-blue-700
                @elseif($transaction->status === 'shipping') bg-purple-100 text-purple-700
                @elseif($transaction->status === 'cancelled') bg-red-100 text-red-700
                @else bg-yellow-100 text-yellow-700 @endif">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs text-gray-400">Customer</p>
                <p class="font-medium text-gray-700">{{ optional($transaction->user)->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Metode Bayar</p>
                <p class="font-medium text-gray-700">{{ $transaction->payment_method }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Tanggal Order</p>
                <p class="font-medium text-gray-700">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y H:i') }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400">No HP Customer</p>
                <p class="font-medium text-gray-700">{{ optional($transaction->user)->phone ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- QUICK ACTION untuk status pending (Konfirmasi Pembayaran di Lokasi) --}}
    @if($transaction->status === 'pending')
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
        <div class="flex items-start gap-3 mb-4">
            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-yellow-800">Menunggu Konfirmasi Pembayaran</p>
                <p class="text-xs text-yellow-600 mt-0.5">
                    Customer sudah hadir dan menunjukkan QR Code. Konfirmasi pembayaran di bawah.
                </p>
            </div>
        </div>

        <div class="flex gap-3">
            {{-- Konfirmasi Bayar --}}
            <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="paid">
                <button type="submit"
                        @click.prevent="$dispatch('confirm', { message: 'Konfirmasi pembayaran dari {{ optional($transaction->user)->name }}? Total: Rp {{ number_format($transaction->total_amount, 0, \\',\\', \\'.\\') }}', onConfirm: () => $el.closest('form') ? $el.closest('form').submit() : null })"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl text-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Konfirmasi Sudah Bayar
                </button>
            </form>

            {{-- Batalkan --}}
            <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST" class="flex-1">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit"
                        @click.prevent="$dispatch('confirm', { message: 'Batalkan pesanan ini? Stok produk akan dikembalikan.', onConfirm: () => $el.closest('form') ? $el.closest('form').submit() : null })"
                        class="w-full bg-red-50 hover:bg-red-100 text-red-700 font-semibold py-3 rounded-xl text-sm transition border border-red-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batalkan Pesanan
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Items Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Item Pesanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Produk</th>
                        <th class="text-center py-3 px-4 text-xs font-medium text-gray-500">Qty</th>
                        <th class="text-right py-3 px-4 text-xs font-medium text-gray-500">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($transaction->transactionDetails as $detail)
                    <tr>
                        <td class="py-3 px-4 text-gray-700">{{ optional($detail->product)->name ?? '-' }}</td>
                        <td class="py-3 px-4 text-center text-gray-500">{{ $detail->quantity }}</td>
                        <td class="py-3 px-4 text-right font-medium text-gray-800">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-100">
                    <tr>
                        <td colspan="2" class="py-4 px-4 text-right text-sm font-semibold text-gray-700">Total</td>
                        <td class="py-4 px-4 text-right text-lg font-bold text-green-700">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Update Status (untuk status selain pending) --}}
    @if($transaction->status !== 'pending' && $transaction->status !== 'cancelled')
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Update Status</h3>
        <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST"
              class="flex gap-3">
            @csrf @method('PATCH')
            <select name="status"
                    class="flex-1 px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="paid" {{ $transaction->status === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="shipping" {{ $transaction->status === 'shipping' ? 'selected' : '' }}>Shipping</option>
                <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
                Update
            </button>
        </form>
    </div>
    @endif

    {{-- Back --}}
    <div class="flex justify-start">
        <a href="{{ route('transactions.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Transaksi
        </a>
    </div>

</div>

@endsection
