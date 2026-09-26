@extends('layouts.app')

@section('titleTab', 'Bahan')
@section('titleDash', 'Bahan')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title fw-bold mb-0">Daftar Bahan & Nutrisi</h5>
            <a href="{{ route('materials.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Tambah Bahan
            </a>
        </div>

        @if($materials->contains(fn($m) => $m->is_low_stock))
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    <strong>Perhatian!</strong> ⚠️ Ada bahan yang stoknya menipis! Segera lakukan pengadaan.
                </div>
            </div>
        @endif

        <form action="{{ route('materials.index') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="all">Semua Kategori</option>
                    <option value="nutrient" {{ request('category') == 'nutrient' ? 'selected' : '' }}>Nutrisi / Pupuk</option>
                    <option value="pesticide" {{ request('category') == 'pesticide' ? 'selected' : '' }}>Pestisida</option>
                    <option value="operational" {{ request('category') == 'operational' ? 'selected' : '' }}>Operasional</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Min Stok</th>
                        <th>Harga/Unit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr>
                            <td>{{ $loop->iteration + $materials->firstItem() - 1 }}</td>
                            <td><span class="badge bg-secondary">{{ $material->code }}</span></td>
                            <td class="fw-semibold">{{ $material->name }}</td>
                            <td>
                                @if($material->category === 'nutrient')
                                    <span class="badge bg-primary">Nutrisi</span>
                                @elseif($material->category === 'pesticide')
                                    <span class="badge bg-warning text-dark">Pestisida</span>
                                @else
                                    <span class="badge bg-secondary">Operasional</span>
                                @endif
                            </td>
                            <td>{{ $material->unit }}</td>
                            <td class="{{ $material->is_low_stock ? 'text-danger fw-bold' : '' }}">
                                {{ $material->stock }}
                            </td>
                            <td>{{ $material->min_stock }}</td>
                            <td>Rp {{ number_format($material->price_per_unit, 0, ',', '.') }}</td>
                            <td>
                                @if($material->status === 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('materials.show', $material->id) }}" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bahan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-3 text-muted">Belum ada data bahan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $materials->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
