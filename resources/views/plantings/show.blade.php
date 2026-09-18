@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Detail Penanaman: {{ $planting->batch_code ?? 'BATCH-XX' }}</h2>
    <a href="{{ route('plantings.index') }}" class="btn btn-secondary">Back</a>
</div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info">Info</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#maintenance">Maintenance Logs</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#water">Water Quality</button>
    </li>
</ul>
<div class="tab-content border border-top-0 p-3 bg-white" id="myTabContent">
    <div class="tab-pane fade show active" id="info">
        <p><strong>Plant Type:</strong> {{ $planting->plantType->name ?? 'N/A' }}</p>
        <p><strong>Start Date:</strong> {{ $planting->start_date ?? '' }}</p>
        <p><strong>Status:</strong> {{ $planting->status ?? '' }}</p>
        <p><strong>Quantity:</strong> {{ $planting->quantity_seeds ?? 0 }}</p>
    </div>
    <div class="tab-pane fade" id="maintenance">
        <ul>
            @forelse($planting->maintenanceLogs ?? [] as $log)
                <li>{{ $log->activity_date }}: {{ $log->action_type }}</li>
            @empty
                <li>No maintenance logs found.</li>
            @endforelse
        </ul>
    </div>
    <div class="tab-pane fade" id="water">
        <ul>
            @forelse($planting->waterQualityLogs ?? [] as $log)
                <li>{{ $log->checked_at }}: pH {{ $log->ph_level }}, TDS {{ $log->tds_ppm }}</li>
            @empty
                <li>No water quality logs found.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

