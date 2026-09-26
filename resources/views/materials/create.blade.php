@extends('layouts.app')

@section('titleTab', 'Tambah Bahan')
@section('titleDash', 'Tambah Bahan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-bold mb-0">Form Tambah Bahan</h5>
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>

                <div class="alert alert-info py-2">
                    <i class="bi bi-info-circle me-1"></i> Kode bahan akan di-generate otomatis oleh sistem.
                </div>

                <form action="{{ route('materials.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Bahan</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="nutrient" {{ old('category') == 'nutrient' ? 'selected' : '' }}>Nutrisi / Pupuk</option>
                                <option value="pesticide" {{ old('category') == 'pesticide' ? 'selected' : '' }}>Pestisida</option>
                                <option value="operational" {{ old('category') == 'operational' ? 'selected' : '' }}>Operasional</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" placeholder="Contoh: liter, kg, botol" value="{{ old('unit') }}" required>
                            @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Awal</label>
                            <input type="number" step="0.01" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Minimum Stok</label>
                            <input type="number" step="0.01" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', 0) }}" required>
                            @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga per Satuan (Rp)</label>
                            <input type="number" step="0.01" name="price_per_unit" class="form-control @error('price_per_unit') is-invalid @enderror" value="{{ old('price_per_unit', 0) }}" required>
                            @error('price_per_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Bahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
