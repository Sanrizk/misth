@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Perawatan (Maintenance Logs)</h2>
    <a href="{{ route('maintenance-logs.create') }}" class="btn btn-primary">Add New</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Planting Batch</th>
                        <th>Action Type</th>
                        <th>Nutrients (PPM)</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs ?? [] as $log)
                    <tr>
                        <td>{{ $log->activity_date }}</td>
                        <td>{{ $log->planting->batch_code ?? 'N/A' }}</td>
                        <td>{{ $log->action_type }}</td>
                        <td>{{ $log->nutrients_ppm ?? '-' }}</td>
                        <td>{{ $log->notes }}</td>
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

