@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Produk</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', optional($product)->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ old('price', optional($product)->price) }}" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', optional($product)->stock) }}" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', optional($product)->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="image_url" class="form-label">URL Gambar</label>
                    <input type="url" name="image_url" id="image_url" class="form-control" value="{{ old('image_url', optional($product)->image_url) }}">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="available" {{ old('status', optional($product)->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="out_of_stock" {{ old('status', optional($product)->status) == 'out_of_stock' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
