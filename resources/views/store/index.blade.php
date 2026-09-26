@extends('layouts.store')

@section('title', 'Toko Misth')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-0">Produk Segar Kami</h2>
        <p class="text-muted">Hasil panen hidroponik terbaik hari ini</p>
    </div>
    <div class="col-md-6">
        <form action="{{ route('store.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Cari sayuran..." value="{{ request('search') }}">
            <select name="category" class="form-select" style="max-width: 200px;">
                <option value="">Semua Kategori</option>
                @foreach($plantTypes as $type)
                    <option value="{{ $type->name }}" {{ request('category') == $type->name ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<div class="row row-cols-2 row-cols-md-4 g-4">
    @forelse($products as $product)
        <div class="col">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden" style="transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 0.125rem 0.25rem rgba(0,0,0,0.075)';">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light text-success fw-bold" style="height: 180px;">
                        <i class="bi bi-image fs-1 opacity-25"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold mb-0">{{ $product->name }}</h5>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill">
                            Grade {{ optional($product->harvest)->quality_grade ?? 'A' }}
                        </span>
                    </div>
                    
                    <p class="text-muted small mb-3">
                        <i class="bi bi-flower1 text-success me-1"></i> 
                        {{ optional(optional($product->harvest)->planting)->plantType->name ?? 'Sayuran Hidroponik' }}
                    </p>
                    
                    <div class="mt-auto">
                        <h4 class="fw-bold text-success mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-secondary rounded-pill">Sisa: {{ $product->stock }}</span>
                        </div>
                        <div class="d-grid gap-2">
                            <form action="{{ route('store.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-success w-100 rounded-pill fw-medium">
                                    <i class="bi bi-cart-plus me-1"></i> Tambah Keranjang
                                </button>
                            </form>
                            <a href="{{ route('store.product.show', $product->id) }}" class="btn btn-light text-success w-100 rounded-pill fw-medium">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-basket text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">Produk Tidak Ditemukan</h4>
            <p class="text-muted">Coba ubah kata kunci pencarian atau kategori Anda.</p>
        </div>
    @endforelse
</div>

<div class="mt-5 d-flex justify-content-center">
    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endsection
