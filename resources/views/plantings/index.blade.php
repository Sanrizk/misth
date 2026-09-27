@extends('layouts.app')
@section('title', 'Penanaman')

@section('styles')
<style>
.planting-card {
    border-radius: 14px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.planting-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important;
}
.planting-card .card-header {
    background: transparent;
    padding: 1rem 1rem 0.5rem;
}
.planting-card .progress {
    background-color: #e9ecef;
}
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-tree me-2"></i>Penanaman</h4>
    <a href="{{ route('plantings.create') }}" class="btn btn-success btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tambah Penanaman
    </a>
</div>

<div class="row g-3">
    @forelse($plantings as $planting)
    <div class="col-12 col-sm-4 col-md-3">
        <div class="card planting-card h-100 shadow-sm border-0">

            {{-- Header: Nama Tanaman + Batch --}}
            <div class="card-header d-flex justify-content-between align-items-start border-0 pb-0">
                <div>
                    <h6 class="fw-bold mb-0 text-success">
                        {{ optional($planting->plantType)->name ?? '-' }}
                    </h6>
                    <small class="text-muted">{{ $planting->batch_code }}</small>
                </div>
                {{-- Status Badge --}}
                <span class="badge 
                    @if($planting->status === 'in_progress') bg-primary
                    @elseif($planting->status === 'harvested') bg-success
                    @else bg-danger @endif">
                    {{ $planting->status === 'in_progress' ? 'Proses' : ($planting->status === 'harvested' ? 'Panen' : 'Gagal') }}
                </span>
            </div>

            <div class="card-body pt-2">

                {{-- Info Row --}}
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i>Semai</small>
                    <small class="fw-semibold">{{ \Carbon\Carbon::parse($planting->start_date)->format('d M Y') }}</small>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <small class="text-muted"><i class="bi bi-clock me-1"></i>Est. Panen</small>
                    <small class="fw-semibold">
                        {{ optional($planting->plantType)->estimated_harvest_days ?? '-' }} Hari
                    </small>
                </div>

                {{-- Progress Bar (UI only, static 0% for now) --}}
                <div class="mb-1 d-flex justify-content-between">
                    <small class="text-muted">Progress</small>
                    <small class="fw-semibold text-success">0%</small>
                </div>
                <div class="progress mb-3" style="height: 8px; border-radius: 99px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: 0%;" 
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>

                <hr class="my-2">

                {{-- Action Buttons Row 1: Show, Edit, Delete --}}
                <div class="row g-1 mb-1 text-center">
                    <div class="col-4">
                        <a href="{{ route('plantings.show', $planting->id) }}" 
                           class="btn btn-sm btn-outline-info w-100" title="Detail">
                            <i class="bi bi-eye d-block"></i>
                            <span style="font-size: 0.65rem;">Detail</span>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('plantings.edit', $planting->id) }}" 
                           class="btn btn-sm btn-outline-warning w-100" title="Edit">
                            <i class="bi bi-pencil d-block"></i>
                            <span style="font-size: 0.65rem;">Edit</span>
                        </a>
                    </div>
                    <div class="col-4">
                        <form action="{{ route('plantings.destroy', $planting->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus penanaman ini?')" 
                                    class="btn btn-sm btn-outline-danger w-100" title="Hapus">
                                <i class="bi bi-trash d-block"></i>
                                <span style="font-size: 0.65rem;">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Action Buttons Row 2: Perawatan, Kualitas Air, Panen --}}
                <div class="row g-1 text-center">
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-secondary w-100" title="Perawatan">
                            <i class="bi bi-clipboard-check d-block"></i>
                            <span style="font-size: 0.65rem;">Perawatan</span>
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-primary w-100" title="Kualitas Air">
                            <i class="bi bi-droplet-half d-block"></i>
                            <span style="font-size: 0.65rem;">Kualitas Air</span>
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-success w-100" title="Panen">
                            <i class="bi bi-basket d-block"></i>
                            <span style="font-size: 0.65rem;">Panen</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-tree fs-1 d-block mb-2"></i>
            Belum ada data penanaman.
            <a href="{{ route('plantings.create') }}">Tambah sekarang</a>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-end mt-4">
    {{ $plantings->links('pagination::bootstrap-5') }}
</div>

@endsection
