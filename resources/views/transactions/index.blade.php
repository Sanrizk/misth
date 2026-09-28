@extends('layouts.app')
@section('title', 'Daftar Transaksi')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Daftar Transaksi</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-4">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-medium">
                <tr>
                    <th class="py-3 px-4">Invoice</th>
                    <th class="py-3 px-4">Pelanggan</th>
                    <th class="py-3 px-4">Total</th>
                    <th class="py-3 px-4">Metode Pembayaran</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-mono text-gray-600">{{ $transaction->invoice_number }}</td>
                    <td class="py-3 px-4">{{ optional($transaction->user)->name }}</td>
                    <td class="py-3 px-4 font-semibold text-gray-800">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">{{ $transaction->payment_method }}</td>
                    <td class="py-3 px-4">
                        @php
                            $badgeClass = 'bg-gray-100 text-gray-700';
                            if($transaction->status == 'pending') $badgeClass = 'bg-yellow-100 text-yellow-700';
                            elseif($transaction->status == 'paid') $badgeClass = 'bg-blue-100 text-blue-700';
                            elseif($transaction->status == 'shipping') $badgeClass = 'bg-purple-100 text-purple-700';
                            elseif($transaction->status == 'completed') $badgeClass = 'bg-green-100 text-green-700';
                            elseif($transaction->status == 'cancelled') $badgeClass = 'bg-red-100 text-red-700';
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-xs text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('transactions.show', $transaction->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-sky-50 text-sky-600 hover:bg-sky-100 rounded-lg transition text-xs font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-400 text-sm">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $transactions->links('pagination::tailwind') }}
    </div>
</div>

@endsection
