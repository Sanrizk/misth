@extends('layouts.app')

@section('titleTab', 'Detail Bahan')
@section('titleDash', 'Detail Bahan')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Informasi Bahan</h5>
                    <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                </div>
                
                @if($material->is_low_stock)
                    <span class="badge bg-danger mb-3"><i class="bi bi-exclamation-triangle"></i> Stok Menipis</span>
                @endif
                
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="text-muted">Kode</td>
                        <td class="fw-semibold">{{ $material->code }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td class="fw-semibold">{{ $material->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kategori</td>
                        <td>
                            @if($material->category === 'nutrient')
                                <span class="badge bg-primary">Nutrisi</span>
                            @elseif($material->category === 'pesticide')
                                <span class="badge bg-warning text-dark">Pestisida</span>
                            @else
                                <span class="badge bg-secondary">Operasional</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($material->status === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Harga/Unit</td>
                        <td class="fw-semibold">Rp {{ number_format($material->price_per_unit, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <div class="mt-3 p-3 bg-light rounded text-center">
                    <h3 class="mb-0 {{ $material->is_low_stock ? 'text-danger' : 'text-success' }} fw-bold">{{ $material->stock }} <span class="fs-6 text-muted">{{ $material->unit }}</span></h3>
                    <small class="text-muted">Stok Tersedia (Min: {{ $material->min_stock }})</small>
                </div>
                
                @if($material->description)
                <div class="mt-4">
                    <h6 class="fw-semibold">Deskripsi</h6>
                    <p class="text-muted small">{{ $material->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Riwayat Pemakaian</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Log Perawatan (Batch)</th>
                                <th>Jumlah Dipakai</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($material->materialUsages()->latest()->get() as $usage)
                                <tr>
                                    <td>{{ $usage->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('maintenance-logs.show', $usage->maintenance_log_id) }}" class="text-decoration-none">
                                            {{ optional($usage->maintenanceLog)->activity_date }} 
                                            <span class="badge bg-secondary ms-1">{{ optional(optional($usage->maintenanceLog)->planting)->batch_code }}</span>
                                        </a>
                                    </td>
                                    <td class="fw-semibold text-danger">-{{ $usage->quantity_used }} {{ $material->unit }}</td>
                                    <td>{{ $usage->notes ?: '-' }}</td>
                                    <td>
                                        <form action="{{ route('material-usages.destroy', $usage->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat pemakaian dan kembalikan stok?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus Pemakaian"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Belum ada riwayat pemakaian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Catat Pemakaian Manual</h5>
                <form action="{{ route('material-usages.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="material_id" value="{{ $material->id }}">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Log Perawatan</label>
                            <select name="maintenance_log_id" class="form-select @error('maintenance_log_id') is-invalid @enderror" required>
                                <option value="" disabled selected>-- Pilih Log Perawatan Aktif --</option>
                                @foreach($maintenanceLogs as $log)
                                    <option value="{{ $log->id }}">Log: {{ $log->activity_date }} (Batch: {{ optional($log->planting)->batch_code }})</option>
                                @endforeach
                            </select>
                            @error('maintenance_log_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Dipakai ({{ $material->unit }})</label>
                            <input type="number" step="0.01" name="quantity_used" class="form-control @error('quantity_used') is-invalid @enderror" min="0.01" max="{{ $material->stock }}" required>
                            @error('quantity_used') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary fw-bold" {{ $material->stock <= 0 ? 'disabled' : '' }}>
                        <i class="bi bi-journal-plus me-1"></i> Catat Pemakaian
                    </button>
                    @if($material->stock <= 0)
                        <small class="text-danger d-block mt-1">Stok habis, tidak dapat digunakan.</small>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
