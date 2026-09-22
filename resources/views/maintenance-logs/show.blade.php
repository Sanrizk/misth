@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Detail Log Perawatan</h2>
    <a href="{{ route('maintenance-logs.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-striped mb-0">
            <tbody>
                <tr>
                    <th style="width: 30%">Batch Code Penanaman</th>
                    <td>{{ optional($maintenanceLog->planting)->batch_code ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jenis Tanaman</th>
                    <td>{{ optional(optional($maintenanceLog->planting)->plantType)->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Petani / User</th>
                    <td>{{ optional($maintenanceLog->user)->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Aktivitas</th>
                    <td>{{ \Carbon\Carbon::parse($maintenanceLog->activity_date)->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Jenis Tindakan</th>
                    <td>{{ $maintenanceLog->action_type }}</td>
                </tr>
                <tr>
                    <th>Nutrisi (PPM)</th>
                    <td>{{ $maintenanceLog->nutrients_ppm ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Catatan (Notes)</th>
                    <td>{{ $maintenanceLog->notes ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

