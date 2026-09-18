@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Panen (Harvests)</h2>
    <a href="{{ route('harvests.create') }}" class="btn btn-primary">Add New</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Planting Batch</th>
                        <th>Yield (Qty)</th>
                        <th>Yield (Weight)</th>
                        <th>Grade</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($harvests ?? [] as $harvest)
                    <tr>
                        <td>{{ $harvest->harvest_date }}</td>
                        <td>{{ $harvest->planting->batch_code ?? 'N/A' }}</td>
                        <td>{{ $harvest->total_yield_quantity }}</td>
                        <td>{{ $harvest->total_yield_weight }} kg</td>
                        <td>
                            @if($harvest->quality_grade == 'Grade A')
                                <span class="badge bg-success">A</span>
                            @elseif($harvest->quality_grade == 'Grade B')
                                <span class="badge bg-primary">B</span>
                            @else
                                <span class="badge bg-warning text-dark">C</span>
                            @endif
                        </td>
                        <td>{{ $harvest->notes }}</td>
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

