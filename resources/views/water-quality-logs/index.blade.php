@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Kualitas Air (Water Quality)</h2>
    <a href="{{ route('water-quality-logs.create') }}" class="btn btn-primary">Add New</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Checked At</th>
                        <th>Planting Batch</th>
                        <th>pH Level</th>
                        <th>TDS (PPM)</th>
                        <th>Water Temp (°C)</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs ?? [] as $log)
                    <tr>
                        <td>{{ $log->checked_at }}</td>
                        <td>{{ $log->planting->batch_code ?? 'N/A' }}</td>
                        <td>{{ $log->ph_level }}</td>
                        <td>{{ $log->tds_ppm }}</td>
                        <td>{{ $log->water_temp ?? '-' }}</td>
                        <td>{{ $log->notes }}</td>
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

