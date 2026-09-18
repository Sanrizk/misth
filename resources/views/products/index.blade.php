@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Produk (Products)</h2>
</div>

<div class="row">
    @forelse($products ?? [] as $product)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                    <span class="text-muted">No Image</span>
                </div>
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text text-muted">{{ Str::limit($product->description, 60) }}</p>
                <h6 class="text-primary">${{ number_format($product->price, 2) }}</h6>
                <div class="mb-2">
                    <span class="badge bg-secondary">Stock: {{ $product->stock }}</span>
                    @if($product->status == 'available')
                        <span class="badge bg-success">Available</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-white border-top-0">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary w-100">Edit Product</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><p>No products found.</p></div>
    @endforelse
</div>
@endsection

