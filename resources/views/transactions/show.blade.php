@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Detail Transaksi</h2>
    <a href="{{ route('transactions.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
    <div class="flex justify-between items-start border-b border-gray-100 pb-4 mb-4">
        <div>
            <p class="text-sm text-gray-500">Invoice Number</p>
            <h3 class="text-xl font-bold text-gray-800 font-mono">{{ $transaction->invoice_number }}</h3>
        </div>
        @php
            $badgeClass = 'bg-gray-100 text-gray-700';
            if($transaction->status == 'pending') $badgeClass = 'bg-yellow-100 text-yellow-700';
            elseif($transaction->status == 'paid') $badgeClass = 'bg-blue-100 text-blue-700';
            elseif($transaction->status == 'shipping') $badgeClass = 'bg-purple-100 text-purple-700';
            elseif($transaction->status == 'completed') $badgeClass = 'bg-green-100 text-green-700';
            elseif($transaction->status == 'cancelled') $badgeClass = 'bg-red-100 text-red-700';
        @endphp
        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">
            {{ ucfirst($transaction->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm mb-6">
        <div>
            <p class="text-gray-500 mb-1">Pelanggan</p>
            <p class="font-semibold text-gray-800">{{ optional($transaction->user)->name }}</p>
        </div>
        <div>
            <p class="text-gray-500 mb-1">Metode Pembayaran</p>
            <p class="font-semibold text-gray-800">{{ $transaction->payment_method }}</p>
        </div>
        <div>
            <p class="text-gray-500 mb-1">Tanggal Pesanan</p>
            <p class="font-semibold text-gray-800">{{ $transaction->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="bg-gray-50 rounded-xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
        <span class="text-sm font-medium text-gray-700">Update Status Pesanan:</span>
        <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST" class="flex w-full md:w-auto items-center gap-2">
            @csrf
            @method('PATCH')
            <select name="status" class="flex-1 md:w-48 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="shipping" {{ $transaction->status == 'shipping' ? 'selected' : '' }}>Shipping</option>
                <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Update
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h4 class="font-bold text-gray-800">Item Transaksi</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-medium">
                <tr>
                    <th class="py-3 px-6">No</th>
                    <th class="py-3 px-6">Produk</th>
                    <th class="py-3 px-6 text-right">Harga</th>
                    <th class="py-3 px-6 text-center">Kuantitas</th>
                    <th class="py-3 px-6 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($transaction->transactionDetails as $index => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6">{{ $index + 1 }}</td>
                        <td class="py-4 px-6 font-medium">{{ optional($detail->product)->name }}</td>
                        <td class="py-4 px-6 text-right">Rp {{ number_format(optional($detail->product)->price, 0, ',', '.') }}</td>
                        <td class="py-4 px-6 text-center">{{ $detail->quantity }}</td>
                        <td class="py-4 px-6 text-right font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">Tidak ada item dalam transaksi ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-100">
                <tr>
                    <th colspan="4" class="py-4 px-6 text-right text-gray-600">Grand Total</th>
                    <th class="py-4 px-6 text-right text-xl font-bold text-green-700">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
