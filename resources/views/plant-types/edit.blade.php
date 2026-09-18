@extends('layouts.app')

@section('content')
<h2>Edit Jenis Tanaman</h2>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('plant-types.update', $plantType->id ?? 0) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $plantType->name ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label>Estimated Harvest Days</label>
                <input type="number" name="estimated_harvest_days" class="form-control" value="{{ $plantType->estimated_harvest_days ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" required>{{ $plantType->description ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('plant-types.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

