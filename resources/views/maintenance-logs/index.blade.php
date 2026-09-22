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
                        <th>No</th>
                        <th>Batch Code</th>
                        <th>Jenis Tanaman</th>
                        <th>Petani</th>
                        <th>Tanggal Aktivitas</th>
                        <th>Jenis Tindakan</th>
                        <th>Nutrisi PPM</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenanceLogs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ optional($log->planting)->batch_code ?? '-' }}</td>
                        <td>{{ optional(optional($log->planting)->plantType)->name ?? '-' }}</td>
                        <td>{{ optional($log->user)->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->activity_date)->format('d M Y H:i') }}</td>
                        <td>{{ $log->action_type }}</td>
                        <td>{{ $log->nutrients_ppm ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($log->notes ?? '-', 40) }}</td>
                        <td>
                            <a href="{{ route('maintenance-logs.show', $log->id) }}" class="btn btn-sm btn-info">Show</a>
                            <form action="{{ route('maintenance-logs.destroy', $log->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada data perawatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end mt-3">
    @if(method_exists($maintenanceLogs, 'links'))
        {{ $maintenanceLogs->links('pagination::bootstrap-5') }}
    @endif
</div>
@endsection
