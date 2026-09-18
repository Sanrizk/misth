@extends('layouts.app')

@section('content')
<h2>Add Penanaman</h2>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('plantings.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Plant Type</label>
                <select name="plant_type_id" class="form-select" required>
                    <option value="">-- Select Plant Type --</option>
                    @foreach($plantTypes ?? [] as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Quantity (Seeds)</label>
                <input type="number" name="quantity_seeds" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('plantings.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

