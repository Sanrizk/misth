@extends('layouts.app')

@section('content')
<h2>Add Jenis Tanaman</h2>
<div class="card mt-3">
    <div class="card-body">
        <form action="{{ route('plant-types.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Estimated Harvest Days</label>
                <input type="number" name="estimated_harvest_days" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('plant-types.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection

