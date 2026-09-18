@extends('layouts.app')

@section('content')
<h2>Add Kualitas Air</h2>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('water-quality-logs.store') }}" method="POST">
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
                <label>Checked At</label>
                <input type="datetime-local" name="checked_at" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>pH Level</label>
                <input type="number" step="0.1" name="ph_level" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>TDS (PPM)</label>
                <input type="number" name="tds_ppm" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Water Temp (°C)</label>
                <input type="number" step="0.1" name="water_temp" class="form-control">
            </div>
            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('water-quality-logs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

