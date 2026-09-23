@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Daftar Panen</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('harvests.create') }}" class="btn btn-primary">Tambah Panen</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Batch Code</th>
                            <th>Jenis Tanaman</th>
                            <th>Tanggal Panen</th>
                            <th>Jumlah</th>
                            <th>Berat (kg)</th>
                            <th>Grade</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($harvests as $index => $harvest)
                            <tr>
                                <td>{{ $harvests->firstItem() + $index }}</td>
                                <td>{{ optional($harvest->planting)->batch_code }}</td>
                                <td>{{ optional(optional($harvest->planting)->plantType)->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($harvest->harvest_date)->format('d/m/Y') }}</td>
                                <td>{{ $harvest->total_yield_quantity }}</td>
                                <td>{{ number_format($harvest->total_yield_weight, 2, ',', '.') }}</td>
                                <td>
                                    @if($harvest->quality_grade == 'Grade A')
                                        <span class="badge bg-success">{{ $harvest->quality_grade }}</span>
                                    @elseif($harvest->quality_grade == 'Grade B')
                                        <span class="badge bg-warning text-dark">{{ $harvest->quality_grade }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $harvest->quality_grade }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('harvests.show', $harvest->id) }}" class="btn btn-info btn-sm text-white">Show</a>
                                    <form action="{{ route('harvests.destroy', $harvest->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data panen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $harvests->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
