@extends('layouts.store')

@section('title', 'Pembayaran Berhasil - Toko Misth')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center">
            <div class="bg-success text-white py-5 px-4">
                <i class="bi bi-check-circle-fill" style="font-size: 5rem;"></i>
                <h2 class="fw-bold mt-3 mb-0">Pesanan Berhasil Dibuat!</h2>
            </div>
            
            <div class="card-body p-5">
                <p class="text-muted mb-1">Nomor Invoice</p>
                <h4 class="fw-bold text-dark mb-4">{{ $transaction->invoice_number }}</h4>
                
                <div class="border rounded-3 p-3 mb-4 bg-light text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tanggal Pesanan</span>
                        <span class="fw-medium">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Metode Pembayaran</span>
                        <span class="fw-medium">{{ $transaction->payment_method }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold">Total Pembayaran</span>
                        <span class="fw-bold fs-4 text-success">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="d-grid gap-3">
                    <a href="{{ route('store.orders.show', $transaction->id) }}" class="btn btn-primary btn-lg rounded-pill fw-bold">
                        Lihat Detail Pesanan
                    </a>
                    <a href="{{ route('store.index') }}" class="btn btn-outline-secondary rounded-pill fw-medium">
                        Kembali ke Toko
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
