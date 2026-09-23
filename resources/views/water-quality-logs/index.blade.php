@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Catatan Kualitas Air</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('water-quality-logs.create') }}" class="btn btn-primary">Tambah Catatan</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Batch Code</th>
                    <th>Jenis Tanaman</th>
                    <th>Tanggal Cek</th>
                    <th>pH Level</th>
                    <th>TDS PPM</th>
                    <th>Suhu Air</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($waterQualityLogs as $log)
                <tr>
                    <td>{{ $loop->iteration + ($waterQualityLogs->currentPage() - 1) * $waterQualityLogs->perPage() }}</td>
                    <td>{{ optional($log->planting)->batch_code }}</td>
                    <td>{{ optional(optional($log->planting)->plantType)->name }}</td>
                    <td>{{ date('Y-m-d H:i', strtotime($log->checked_at)) }}</td>
                    <td>
                        @if($log->ph_level < 6)
                            <span class="badge bg-danger">{{ $log->ph_level }}</span>
                        @elseif($log->ph_level <= 7)
                            <span class="badge bg-success">{{ $log->ph_level }}</span>
                        @else
                            <span class="badge bg-warning">{{ $log->ph_level }}</span>
                        @endif
                    </td>
                    <td>{{ number_format($log->tds_ppm, 0, ',', '.') }}</td>
                    <td>{{ $log->water_temp }}</td>
                    <td>{{ $log->notes }}</td>
                    <td>
                        <a href="{{ route('water-quality-logs.show', $log->id) }}" class="btn btn-info btn-sm">Show</a>
                        <form action="{{ route('water-quality-logs.destroy', $log->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $waterQualityLogs->links('pagination::bootstrap-5') }}
</div>
@endsection
