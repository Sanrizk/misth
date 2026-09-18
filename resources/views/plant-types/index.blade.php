@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Jenis Tanaman</h2>
    <a href="{{ route('plant-types.create') }}" class="btn btn-primary">Add New</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Harvest Days</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plantTypes ?? [] as $type)
                    <tr>
                        <td>{{ $type->id }}</td>
                        <td>{{ $type->name }}</td>
                        <td>{{ $type->estimated_harvest_days }} days</td>
                        <td>{{ Str::limit($type->description, 50) }}</td>
                        <td>
                            <a href="{{ route('plant-types.edit', $type->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('plant-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

