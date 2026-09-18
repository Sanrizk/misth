@extends('layouts.app')

@section('content')
<h2>Add Panen</h2>
<div class="alert alert-info">
    Submitting this form will automatically create a Product in the store.
</div>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('harvests.store') }}" method="POST">
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
                <label>Harvest Date</label>
                <input type="date" name="harvest_date" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Total Yield Quantity (pcs)</label>
                    <input type="number" name="total_yield_quantity" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Total Yield Weight (kg)</label>
                    <input type="number" step="0.01" name="total_yield_weight" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Quality Grade</label>
                    <select name="quality_grade" class="form-select" required>
                        <option value="Grade A">Grade A</option>
                        <option value="Grade B">Grade B</option>
                        <option value="Grade C">Grade C</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Selling Price (per item)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('harvests.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

