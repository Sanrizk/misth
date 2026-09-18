@extends('layouts.app')

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

