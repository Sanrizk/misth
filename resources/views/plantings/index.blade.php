@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Penanaman (Plantings)</h2>
    <a href="{{ route('plantings.create') }}" class="btn btn-primary">Add New</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Batch Code</th>
                        <th>Plant Type</th>
                        <th>Farmer</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plantings ?? [] as $planting)
                    <tr>
                        <td>{{ $planting->batch_code }}</td>
                        <td>{{ $planting->plantType->name ?? 'N/A' }}</td>
                        <td>{{ $planting->user->name ?? 'N/A' }}</td>
                        <td>{{ $planting->start_date }}</td>
                        <td>
                            @if($planting->status == 'in_progress')
                                <span class="badge bg-primary">In Progress</span>
                            @elseif($planting->status == 'harvested')
                                <span class="badge bg-success">Harvested</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('plantings.show', $planting->id) }}" class="btn btn-sm btn-info text-white">Detail</a>
                            <form action="{{ route('plantings.destroy', $planting->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this planting?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

