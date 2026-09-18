@extends('layouts.app')

@section('content')
<h2>Edit Produk</h2>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('products.update', $product->id ?? 0) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name ?? '' }}" required>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price ?? '' }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock ?? '' }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-select" required>
                        <option value="available" {{ (isset($product) && $product->status == 'available') ? 'selected' : '' }}>Available</option>
                        <option value="out_of_stock" {{ (isset($product) && $product->status == 'out_of_stock') ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label>Image URL</label>
                <input type="url" name="image_url" class="form-control" value="{{ $product->image_url ?? '' }}">
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $product->description ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

