@extends('layouts.app')

@section('content')
<h2>Tambah Log Perawatan</h2>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('maintenance-logs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Penanaman</label>
                <select name="planting_id" class="form-select @error('planting_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Penanaman --</option>
                    @foreach($plantings as $planting)
                        <option value="{{ $planting->id }}" {{ old('planting_id') == $planting->id ? 'selected' : '' }}>
                            {{ $planting->batch_code }} - {{ optional($planting->plantType)->name }}
                        </option>
                    @endforeach
                </select>
                @error('planting_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Aktivitas</label>
                <input type="datetime-local" name="activity_date" class="form-control @error('activity_date') is-invalid @enderror" required>
                @error('activity_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis Tindakan</label>
                <select name="action_type" id="action_type" 
                        class="form-select @error('action_type') is-invalid @enderror" required>
                    <option value="">-- Pilih Tindakan --</option>
                    <option value="Pemberian Nutrisi AB Mix" {{ old('action_type') == 'Pemberian Nutrisi AB Mix' ? 'selected' : '' }}>Pemberian Nutrisi AB Mix</option>
                    <option value="Penyemprotan Pestisida Nabati" {{ old('action_type') == 'Penyemprotan Pestisida Nabati' ? 'selected' : '' }}>Penyemprotan Pestisida Nabati</option>
                    <option value="Pengecekan Akar" {{ old('action_type') == 'Pengecekan Akar' ? 'selected' : '' }}>Pengecekan Akar</option>
                    <option value="Pemangkasan Daun" {{ old('action_type') == 'Pemangkasan Daun' ? 'selected' : '' }}>Pemangkasan Daun</option>
                    <option value="Lainnya" {{ old('action_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('action_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Bahan Section --}}
            <div class="mb-3" id="bahan-section" style="display:none;">
                <label class="form-label">Bahan yang Digunakan</label>
                <div id="bahan-list">
                    {{-- Row bahan akan di-render oleh JS --}}
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nutrisi PPM (optional)</label>
                <input type="number" name="nutrients_ppm" class="form-control @error('nutrients_ppm') is-invalid @enderror" min="0">
                @error('nutrients_ppm')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan (optional)</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('maintenance-logs.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Mapping tindakan ke bahan (gunakan ID dari database)
    const materials = @json($materials);

    const actionMaterialMap = {
        'Pemberian Nutrisi AB Mix': ['AB Mix Part A', 'AB Mix Part B'],
        'Penyemprotan Pestisida Nabati': ['Pestisida Nabati'],
        'Pengecekan Akar': [],
        'Pemangkasan Daun': [],
        'Lainnya': null, // null = pilih manual
    };

    const actionTypeSelect = document.getElementById('action_type');
    const bahanSection = document.getElementById('bahan-section');
    const bahanList = document.getElementById('bahan-list');

    actionTypeSelect.addEventListener('change', function () {
        const selected = this.value;
        const mapped = actionMaterialMap[selected];
        bahanList.innerHTML = '';

        if (!selected || mapped?.length === 0) {
            // Tidak ada bahan
            bahanSection.style.display = 'none';
            return;
        }

        bahanSection.style.display = 'block';

        if (mapped === null) {
            // Lainnya: pilih manual + qty
            bahanList.innerHTML = buildManualRow();
        } else {
            // Auto-mapped: tampilkan bahan readonly
            mapped.forEach((name, i) => {
                const mat = materials.find(m => m.name === name);
                if (!mat) return;
                bahanList.innerHTML += buildReadonlyRow(mat, i);
            });
        }
    });

    function buildReadonlyRow(mat, i) {
        return `
        <div class="row mb-2 align-items-center">
            <div class="col-md-6">
                <input type="hidden" name="material_ids[]" value="${mat.id}">
                <input type="text" class="form-control" value="${mat.name} (${mat.unit})" readonly>
            </div>
            <div class="col-md-4">
                <input type="number" name="quantities[]" class="form-control" 
                       placeholder="Jumlah (${mat.unit})" step="0.01" min="0.01" required>
            </div>
            <div class="col-md-2 text-muted small">Stok: ${mat.stock} ${mat.unit}</div>
        </div>`;
    }

    function buildManualRow() {
        const options = materials.map(m => 
            `<option value="${m.id}">${m.name} (${m.unit}) - Stok: ${m.stock}</option>`
        ).join('');

        return `
        <div class="row mb-2 align-items-center">
            <div class="col-md-6">
                <select name="material_ids[]" class="form-select">
                    <option value="">-- Pilih Bahan --</option>
                    ${options}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="quantities[]" class="form-control" 
                       placeholder="Jumlah" step="0.01" min="0.01">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addManualRow()">+ Tambah</button>
            </div>
        </div>`;
    }

    function addManualRow() {
        const options = materials.map(m => 
            `<option value="${m.id}">${m.name} (${m.unit}) - Stok: ${m.stock}</option>`
        ).join('');

        const row = document.createElement('div');
        row.className = 'row mb-2 align-items-center';
        row.innerHTML = `
            <div class="col-md-6">
                <select name="material_ids[]" class="form-select">
                    <option value="">-- Pilih Bahan --</option>
                    ${options}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="quantities[]" class="form-control" 
                       placeholder="Jumlah" step="0.01" min="0.01">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-outline-danger" 
                        onclick="this.closest('.row').remove()">Hapus</button>
            </div>`;
        bahanList.appendChild(row);
    }
</script>
@endsection
