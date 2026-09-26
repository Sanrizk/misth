@extends('layouts.store')

@section('title', 'Riwayat Pesanan - Toko Misth')

@section('content')
<h2 class="fw-bold mb-4">Riwayat Pesanan</h2>

@forelse($transactions as $transaction)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="fw-bold me-3"><i class="bi bi-receipt me-1"></i> {{ $transaction->invoice_number }}</span>
                <span class="text-muted small"><i class="bi bi-clock me-1"></i> {{ $transaction->created_at->format('d M Y, H:i') }}</span>
            </div>
            
            <div>
                @if($transaction->status === 'pending')
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Pembayaran</span>
                @elseif($transaction->status === 'paid')
                    <span class="badge bg-info px-3 py-2 rounded-pill">Dibayar</span>
                @elseif($transaction->status === 'shipping')
                    <span class="badge bg-primary px-3 py-2 rounded-pill">Dalam Pengiriman</span>
                @elseif($transaction->status === 'completed')
                    <span class="badge bg-success px-3 py-2 rounded-pill">Selesai</span>
                @elseif($transaction->status === 'cancelled')
                    <span class="badge bg-danger px-3 py-2 rounded-pill">Dibatalkan</span>
                @else
                    <span class="badge bg-secondary px-3 py-2 rounded-pill">{{ ucfirst($transaction->status) }}</span>
                @endif
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    @php $firstItem = $transaction->transactionDetails->first(); @endphp
                    @if($firstItem)
                        <div class="d-flex align-items-center gap-3 mb-2">
                            @if(optional($firstItem->product)->image_url)
                                <img src="{{ optional($firstItem->product)->image_url }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="rounded bg-light text-success d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-image fs-4 opacity-50"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="fw-bold mb-1">{{ optional($firstItem->product)->name ?? 'Produk Dihapus' }}</h6>
                                <span class="text-muted small">{{ $firstItem->quantity }} item x Rp {{ number_format(optional($firstItem->product)->price ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        @if($transaction->transactionDetails->count() > 1)
                            <p class="text-muted small ms-5 ps-4 mb-0">+ {{ $transaction->transactionDetails->count() - 1 }} produk lainnya</p>
                        @endif
                    @endif
                </div>
                <div class="col-md-4 mt-3 mt-md-0 text-md-end">
                    <div class="text-muted small mb-1">Total Belanja</div>
                    <div class="fw-bold fs-5 text-success mb-3">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                    <a href="{{ route('store.orders.show', $transaction->id) }}" class="btn btn-outline-success btn-sm rounded-pill px-4 fw-medium">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="bi bi-receipt text-muted" style="font-size: 6rem;"></i>
        </div>
        <h4 class="fw-bold text-dark">Belum Ada Pesanan</h4>
        <p class="text-muted mb-4">Anda belum pernah melakukan pesanan apapun. Mulai penuhi meja makan Anda dengan sayuran segar dari kami!</p>
        <a href="{{ route('store.index') }}" class="btn btn-success btn-lg px-4 rounded-pill fw-medium">
            Mulai Belanja <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
@endforelse

<div class="mt-4 d-flex justify-content-center">
    {{ $transactions->links('pagination::bootstrap-5') }}
</div>
@endsection
