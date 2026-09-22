@extends('layouts.app')

@section('content')
<h2>Tambah Log Perawatan</h2>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('maintenance-logs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Penanaman</label>
                <select name="penanaman_id" class="form-select @error('penanaman_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Penanaman --</option>
                    @foreach($plantings as $planting)
                        <option value="{{ $planting->id }}" {{ old('penanaman_id') == $planting->id ? 'selected' : '' }}>
                            {{ $planting->batch_code }} - {{ $planting->plantType->name }}
                        </option>
                    @endforeach
                </select>
                @error('penanaman_id')
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
                <select name="action_type" class="form-select @error('action_type') is-invalid @enderror" required>
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
            <div class="mb-3">
                <label class="form-label">Nutrisi PPM (optional)</label>
                <input type="number" name="nutrients_ppm" class="form-control @error('nutrients_ppm') is-invalid @enderror" min="0">
                @error('nutrients_ppm')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Catatan (optional)</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2"></textarea>
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

@section('content')
<h2>Add Perawatan</h2>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('maintenance-logs.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Planting Batch</label>
                <select name="planting_id" class="form-select" required>
                    <option value="">-- Select Active Planting --</option>
                    @foreach($activePlantings ?? [] as $p)
                        <option value="{{ $p->id }}">{{ $p->batch_code }} - {{ $p->plantType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Activity Date</label>
                <input type="datetime-local" name="activity_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Action Type</label>
                <input type="text" name="action_type" class="form-control" placeholder="e.g. Trim leaves, Add nutrients" required>
            </div>
            <div class="mb-3">
                <label>Nutrients (PPM)</label>
                <input type="number" name="nutrients_ppm" class="form-control">
            </div>
            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('maintenance-logs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

