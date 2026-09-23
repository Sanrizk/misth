@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Catatan Kualitas Air</h2>

    <div class="alert alert-info">Hanya penanaman aktif yang bisa dicatat kualitas airnya</div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('water-quality-logs.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="planting_id" class="form-label">Penanaman</label>
            <select name="planting_id" id="planting_id" class="form-select" required>
                <option value="">Pilih Penanaman</option>
                @foreach($plantings as $planting)
                    <option value="{{ $planting->id }}" {{ old('planting_id') == $planting->id ? 'selected' : '' }}>
                        {{ $planting->batch_code }} - {{ optional($planting->plantType)->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="checked_at" class="form-label">Tanggal Cek</label>
            <input type="datetime-local" name="checked_at" id="checked_at" class="form-control" value="{{ old('checked_at') }}" required>
        </div>

        <div class="mb-3">
            <label for="ph_level" class="form-label">pH Level</label>
            <input type="number" step="0.01" name="ph_level" id="ph_level" class="form-control" value="{{ old('ph_level') }}" required>
        </div>

        <div class="mb-3">
            <label for="tds_ppm" class="form-label">TDS PPM</label>
            <input type="number" name="tds_ppm" id="tds_ppm" class="form-control" value="{{ old('tds_ppm') }}" required>
        </div>

        <div class="mb-3">
            <label for="water_temp" class="form-label">Suhu Air</label>
            <input type="number" step="0.1" name="water_temp" id="water_temp" class="form-control" value="{{ old('water_temp') }}">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('water-quality-logs.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
