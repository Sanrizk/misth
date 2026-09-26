@extends('layouts.store')

@section('title', $product->name . ' - Toko Misth')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('store.index') }}" class="text-success text-decoration-none">Toko</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
</nav>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="row g-0">
        <div class="col-md-5">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" class="img-fluid h-100 w-100" alt="{{ $product->name }}" style="object-fit: cover; min-height: 400px;">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light h-100 w-100 text-success" style="min-height: 400px;">
                    <i class="bi bi-image" style="font-size: 5rem; opacity: 0.2;"></i>
                </div>
            @endif
        </div>
        <div class="col-md-7">
            <div class="card-body p-5">
                <div class="mb-2">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 me-2">
                        Grade {{ optional($product->harvest)->quality_grade ?? 'A' }}
                    </span>
                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                        <i class="bi bi-flower1"></i> {{ optional(optional($product->harvest)->planting)->plantType->name ?? 'Sayuran Hidroponik' }}
                    </span>
                </div>
                
                <h1 class="fw-bold my-3">{{ $product->name }}</h1>
                <h2 class="text-success fw-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-2">Deskripsi Produk</h5>
                    <p class="text-muted line-height-lg">{{ $product->description ?? 'Sayuran hidroponik segar berkualitas tinggi, dipanen hari ini dari kebun greenhouse modern kami. Bebas pestisida dan kaya akan nutrisi.' }}</p>
                </div>

                <div class="mb-4 p-3 bg-light rounded-3 d-flex align-items-center gap-4">
                    <div>
                        <span class="text-muted d-block small mb-1">Stok Tersedia</span>
                        <span class="fw-bold fs-5">{{ $product->stock }} <small class="fw-normal text-muted fs-6">Unit</small></span>
                    </div>
                    <div class="border-start ps-4">
                        <span class="text-muted d-block small mb-1">Status</span>
                        @if($product->stock > 0)
                            <span class="badge bg-success">Tersedia</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </div>
                </div>

                @if($product->stock > 0)
                    <form action="{{ route('store.cart.add') }}" method="POST" class="d-flex gap-3 align-items-end">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div style="width: 120px;">
                            <label class="form-label text-muted small fw-bold">Jumlah</label>
                            <input type="number" name="quantity" class="form-control form-control-lg text-center" value="1" min="1" max="{{ $product->stock }}" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg px-4 flex-grow-1 fw-bold rounded-3">
                            <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-lg px-4 w-100 fw-bold rounded-3" disabled>
                        Stok Habis
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
