@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Transaksi</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Invoice</th>
                    <th>Pelanggan</th>
                    <th>Total Harga (Rupiah)</th>
                    <th>Status</th>
                    <th>Metode Pembayaran</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $transactions->firstItem() + $index }}</td>
                        <td>{{ $transaction->invoice_number }}</td>
                        <td>{{ optional($transaction->user)->name }}</td>
                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $badgeClass = 'bg-secondary';
                                if($transaction->status == 'pending') $badgeClass = 'bg-warning text-dark';
                                elseif($transaction->status == 'paid') $badgeClass = 'bg-primary';
                                elseif($transaction->status == 'shipping') $badgeClass = 'bg-info';
                                elseif($transaction->status == 'completed') $badgeClass = 'bg-success';
                                elseif($transaction->status == 'cancelled') $badgeClass = 'bg-danger';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($transaction->status) }}</span>
                        </td>
                        <td>{{ $transaction->payment_method }}</td>
                        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada transaksi ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $transactions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
