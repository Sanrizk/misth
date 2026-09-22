@extends('layouts.app')

@section('title', 'Penanaman')

@section('content')
    @include('layouts.partials.flash')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Penanaman</h1>
        <a href="{{ route('plantings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Penanaman
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode Batch</th>
                    <th>Jenis Tanaman</th>
                    <th>Petani</th>
                    <th>Jumlah Bibit</th>
                    <th>Tanggal Mulai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plantings as $index => $planting)
                    <tr>
                        <td>{{ $plantings->firstItem() + $index }}</td>
                        <td>{{ $planting->batch_code }}</td>
                        <td>{{ $planting->plantType->name }}</td>
                        <td>{{ $planting->user->name }}</td>
                        <td>{{ $planting->quantity_seeds }}</td>
                        <td>{{ $planting->start_date->format('d M Y') }}</td>
                        <td>
                            @php
                                $statusMap = ['in_progress' => 'bg-primary', 'harvested' => 'bg-success', 'failed' => 'bg-danger'];
                            @endphp
                            <span class="badge {{ $statusMap[$planting->status] ?? 'bg-secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $planting->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('plantings.show', $planting->id) }}" class="btn btn-sm btn-info me-1">
                                <i class="bi bi-eye"></i> Show
                            </a>
                            <a href="{{ route('plantings.edit', $planting->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('plantings.destroy', $planting->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah yakin menghapus penanaman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data penanaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $plantings->links('pagination::bootstrap-5') }}
    </div>
@endsection
