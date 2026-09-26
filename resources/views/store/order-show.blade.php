@extends('layouts.store')

@section('title', 'Detail Pesanan - Toko Misth')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Detail Pesanan</h2>
    <a href="{{ route('store.orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Pesanan
    </a>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0">Daftar Produk</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga Satuan</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->transactionDetails as $detail)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(optional($detail->product)->image_url)
                                                <img src="{{ optional($detail->product)->image_url }}" alt="{{ optional($detail->product)->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light text-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image opacity-50"></i>
                                                </div>
                                            @endif
                                            <h6 class="mb-0 fw-bold">{{ optional($detail->product)->name ?? 'Produk Dihapus' }}</h6>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format(optional($detail->product)->price ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end pe-4 fw-bold">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Info Transaksi</h5>
                
                <div class="mb-3">
                    <span class="text-muted d-block small mb-1">Nomor Invoice</span>
                    <span class="fw-bold">{{ $transaction->invoice_number }}</span>
                </div>
                
                <div class="mb-3">
                    <span class="text-muted d-block small mb-1">Tanggal Pesanan</span>
                    <span class="fw-medium">{{ $transaction->created_at->format('d F Y, H:i') }}</span>
                </div>
                
                <div class="mb-3">
                    <span class="text-muted d-block small mb-1">Status Pembayaran</span>
                    @if($transaction->status === 'pending')
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                    @elseif($transaction->status === 'paid')
                        <span class="badge bg-info px-3 py-2 rounded-pill">Dibayar</span>
                    @elseif($transaction->status === 'shipping')
                        <span class="badge bg-primary px-3 py-2 rounded-pill">Pengiriman</span>
                    @elseif($transaction->status === 'completed')
                        <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                    @elseif($transaction->status === 'cancelled')
                        <span class="badge bg-danger px-3 py-2 rounded-pill">Dibatalkan</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($transaction->status) }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <span class="text-muted d-block small mb-1">Metode Pembayaran</span>
                    <span class="fw-medium"><i class="bi bi-wallet2 me-1 text-success"></i> {{ $transaction->payment_method ?? '-' }}</span>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span class="fw-bold fs-5">Total Pembayaran</span>
                    <span class="fw-bold fs-4 text-success">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
