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
                        <button class="btn btn-sm btn-outline-secondary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#modalPerawatan{{ $planting->id }}"
                                title="Perawatan">
                            <i class="bi bi-clipboard-check d-block"></i>
                            <span style="font-size:0.65rem;">Perawatan</span>
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-primary w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#modalKualitasAir{{ $planting->id }}"
                                title="Kualitas Air">
                            <i class="bi bi-droplet-half d-block"></i>
                            <span style="font-size:0.65rem;">Kualitas Air</span>
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-sm btn-outline-success w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#modalPanen{{ $planting->id }}"
                                title="Panen">
                            <i class="bi bi-basket d-block"></i>
                            <span style="font-size:0.65rem;">Panen</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

<div class="modal fade" id="modalPerawatan{{ $planting->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h6 class="modal-title">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Perawatan — {{ $planting->batch_code }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- FORM PERAWATAN --}}
                <form action="{{ route('maintenance-logs.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="planting_id" value="{{ $planting->id }}">

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Tanggal Aktivitas</label>
                            <input type="datetime-local" name="activity_date"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Jenis Tindakan</label>
                            <select name="action_type" id="action_type_{{ $planting->id }}"
                                    class="form-select form-select-sm" required>
                                <option value="">-- Pilih --</option>
                                <option value="Pemberian Nutrisi AB Mix">Pemberian Nutrisi AB Mix</option>
                                <option value="Penyemprotan Pestisida Nabati">Penyemprotan Pestisida Nabati</option>
                                <option value="Pengecekan Akar">Pengecekan Akar</option>
                                <option value="Pemangkasan Daun">Pemangkasan Daun</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Nutrisi PPM (opsional)</label>
                            <input type="number" name="nutrients_ppm"
                                   class="form-control form-control-sm" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Catatan (opsional)</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="1"></textarea>
                        </div>
                    </div>

                    {{-- Bahan Section --}}
                    <div id="bahanSection_{{ $planting->id }}" class="mb-2" style="display:none;">
                        <label class="form-label form-label-sm">Bahan Digunakan</label>
                        <div id="bahanList_{{ $planting->id }}"></div>
                    </div>

                    <button type="submit" class="btn btn-secondary btn-sm w-100">
                        <i class="bi bi-save me-1"></i> Simpan Perawatan
                    </button>
                </form>

                <hr>

                {{-- LOG RIWAYAT PERAWATAN --}}
                <h6 class="fw-bold mb-2"><i class="bi bi-clock-history me-1"></i> Riwayat Perawatan</h6>
                @forelse($planting->maintenanceLogs->sortByDesc('activity_date') as $log)
                <div class="card card-body p-2 mb-2 bg-light border-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="fw-semibold">{{ $log->action_type }}</small><br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($log->activity_date)->format('d M Y H:i') }}
                                · {{ optional($log->user)->name }}
                            </small>
                            @if($log->nutrients_ppm)
                            <br><small class="text-info">PPM: {{ $log->nutrients_ppm }}</small>
                            @endif
                            @if($log->notes)
                            <br><small class="text-muted fst-italic">{{ $log->notes }}</small>
                            @endif
                            @if($log->materialUsages->count() > 0)
                            <br>
                            @foreach($log->materialUsages as $usage)
                            <span class="badge bg-light text-dark border me-1" style="font-size:0.65rem;">
                                {{ optional($usage->material)->name }} {{ $usage->quantity_used }} {{ optional($usage->material)->unit }}
                            </span>
                            @endforeach
                            @endif
                        </div>
                        <form action="{{ route('maintenance-logs.destroy', $log->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus log ini?')"
                                    class="btn btn-sm btn-outline-danger py-0 px-1">
                                <i class="bi bi-trash" style="font-size:0.7rem;"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center small">Belum ada riwayat perawatan.</p>
                @endforelse

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKualitasAir{{ $planting->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">
                    <i class="bi bi-droplet-half me-2"></i>
                    Kualitas Air — {{ $planting->batch_code }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- FORM KUALITAS AIR --}}
                <form action="{{ route('water-quality-logs.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="planting_id" value="{{ $planting->id }}">

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Tanggal Cek</label>
                            <input type="datetime-local" name="checked_at"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">pH Level (0-14)</label>
                            <input type="number" name="ph_level" step="0.1" min="0" max="14"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">TDS PPM</label>
                            <input type="number" name="tds_ppm" min="0"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Suhu Air °C (opsional)</label>
                            <input type="number" name="water_temp" step="0.1"
                                   class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-sm">Catatan (opsional)</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="1"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-save me-1"></i> Simpan Kualitas Air
                    </button>
                </form>

                <hr>

                {{-- LOG RIWAYAT KUALITAS AIR --}}
                <h6 class="fw-bold mb-2"><i class="bi bi-clock-history me-1"></i> Riwayat Kualitas Air</h6>
                @forelse($planting->waterQualityLogs->sortByDesc('checked_at') as $log)
                <div class="card card-body p-2 mb-2 bg-light border-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="fw-semibold">
                                pH: {{ $log->ph_level }}
                                <span class="badge 
                                    @if($log->ph_level < 6) bg-danger
                                    @elseif($log->ph_level <= 7) bg-success
                                    @else bg-warning text-dark @endif"
                                    style="font-size:0.6rem;">
                                    {{ $log->ph_level < 6 ? 'Asam' : ($log->ph_level <= 7 ? 'Normal' : 'Basa') }}
                                </span>
                            </small><br>
                            <small class="text-muted">
                                TDS: {{ $log->tds_ppm }} ppm
                                @if($log->water_temp) · Suhu: {{ $log->water_temp }}°C @endif
                            </small><br>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($log->checked_at)->format('d M Y H:i') }}
                            </small>
                            @if($log->notes)
                            <br><small class="fst-italic text-muted">{{ $log->notes }}</small>
                            @endif
                        </div>
                        <form action="{{ route('water-quality-logs.destroy', $log->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus log ini?')"
                                    class="btn btn-sm btn-outline-danger py-0 px-1">
                                <i class="bi bi-trash" style="font-size:0.7rem;"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center small">Belum ada riwayat kualitas air.</p>
                @endforelse

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPanen{{ $planting->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title">
                    <i class="bi bi-basket me-2"></i>
                    Panen — {{ $planting->batch_code }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                @if($planting->harvest)
                {{-- Sudah panen: tampilkan data panen --}}
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-1"></i>
                    Penanaman ini sudah dipanen pada
                    <strong>{{ \Carbon\Carbon::parse($planting->harvest->harvest_date)->format('d M Y') }}</strong>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <small class="text-muted">Jumlah Panen</small>
                        <p class="fw-bold mb-0">{{ $planting->harvest->total_yield_quantity }} unit</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Berat Total</small>
                        <p class="fw-bold mb-0">{{ $planting->harvest->total_yield_weight }} kg</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Grade</small>
                        <p class="mb-0">
                            <span class="badge 
                                @if($planting->harvest->quality_grade === 'Grade A') bg-success
                                @elseif($planting->harvest->quality_grade === 'Grade B') bg-warning text-dark
                                @else bg-danger @endif">
                                {{ $planting->harvest->quality_grade }}
                            </span>
                        </p>
                    </div>
                    @if($planting->harvest->notes)
                    <div class="col-12">
                        <small class="text-muted">Catatan</small>
                        <p class="mb-0 fst-italic">{{ $planting->harvest->notes }}</p>
                    </div>
                    @endif
                </div>

                @else
                {{-- Belum panen: tampilkan form --}}
                @if($planting->status === 'in_progress')
                <div class="alert alert-warning small">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Menyimpan panen akan otomatis membuat produk di toko dan mengubah status penanaman menjadi <strong>harvested</strong>.
                </div>
                <form action="{{ route('harvests.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="planting_id" value="{{ $planting->id }}">

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Tanggal Panen</label>
                            <input type="date" name="harvest_date"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Grade</label>
                            <select name="quality_grade" class="form-select form-select-sm" required>
                                <option value="">-- Pilih --</option>
                                <option value="Grade A">Grade A</option>
                                <option value="Grade B">Grade B</option>
                                <option value="Grade C">Grade C</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Jumlah Panen (unit)</label>
                            <input type="number" name="total_yield_quantity" min="1"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Berat Total (kg)</label>
                            <input type="number" name="total_yield_weight" step="0.01" min="0"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Harga Jual per Unit (Rp)</label>
                            <input type="number" name="price" min="0"
                                   class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Catatan (opsional)</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="1"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-basket me-1"></i> Eksekusi Panen
                    </button>
                </form>
                @else
                <div class="alert alert-danger small">
                    <i class="bi bi-x-circle me-1"></i>
                    Penanaman ini berstatus <strong>{{ $planting->status }}</strong>, tidak bisa dipanen.
                </div>
                @endif
                @endif

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

@section('scripts')
<script>
const materials = @json($materials);

const actionMaterialMap = {
    'Pemberian Nutrisi AB Mix': ['AB Mix Part A', 'AB Mix Part B'],
    'Penyemprotan Pestisida Nabati': ['Pestisida Nabati'],
    'Pengecekan Akar': [],
    'Pemangkasan Daun': [],
    'Lainnya': null,
};

document.querySelectorAll('[id^="action_type_"]').forEach(select => {
    const plantingId = select.id.replace('action_type_', '');
    const bahanSection = document.getElementById('bahanSection_' + plantingId);
    const bahanList = document.getElementById('bahanList_' + plantingId);

    select.addEventListener('change', function () {
        const mapped = actionMaterialMap[this.value];
        bahanList.innerHTML = '';

        if (!this.value || (Array.isArray(mapped) && mapped.length === 0)) {
            bahanSection.style.display = 'none';
            return;
        }

        bahanSection.style.display = 'block';

        if (mapped === null) {
            bahanList.innerHTML = buildManualRow();
        } else {
            mapped.forEach(name => {
                const mat = materials.find(m => m.name === name);
                if (mat) bahanList.innerHTML += buildReadonlyRow(mat);
            });
        }
    });
});

function buildReadonlyRow(mat) {
    return `
    <div class="row g-1 mb-1 align-items-center">
        <div class="col-7">
            <input type="hidden" name="material_ids[]" value="${mat.id}">
            <input type="text" class="form-control form-control-sm" value="${mat.name} (${mat.unit})" readonly>
        </div>
        <div class="col-3">
            <input type="number" name="quantities[]" class="form-control form-control-sm"
                   placeholder="Qty" step="0.01" min="0.01" required>
        </div>
        <div class="col-2 text-muted" style="font-size:0.7rem;">Stok: ${mat.stock}</div>
    </div>`;
}

function buildManualRow() {
    const options = materials.map(m =>
        `<option value="${m.id}">${m.name} (${m.unit})</option>`
    ).join('');
    return `
    <div class="row g-1 mb-1 align-items-center">
        <div class="col-7">
            <select name="material_ids[]" class="form-select form-select-sm">
                <option value="">-- Pilih Bahan --</option>
                ${options}
            </select>
        </div>
        <div class="col-3">
            <input type="number" name="quantities[]" class="form-control form-control-sm"
                   placeholder="Qty" step="0.01" min="0.01">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-outline-primary btn-sm"
                    onclick="addManualRow(this)">+</button>
        </div>
    </div>`;
}

function addManualRow(btn) {
    const options = materials.map(m =>
        `<option value="${m.id}">${m.name} (${m.unit})</option>`
    ).join('');
    const row = document.createElement('div');
    row.className = 'row g-1 mb-1 align-items-center';
    row.innerHTML = `
        <div class="col-7">
            <select name="material_ids[]" class="form-select form-select-sm">
                <option value="">-- Pilih Bahan --</option>
                ${options}
            </select>
        </div>
        <div class="col-3">
            <input type="number" name="quantities[]" class="form-control form-control-sm"
                   placeholder="Qty" step="0.01" min="0.01">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-outline-danger btn-sm"
                    onclick="this.closest('.row').remove()">−</button>
        </div>`;
    btn.closest('.row').parentNode.appendChild(row);
}
</script>
@endsection
