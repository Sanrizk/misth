@extends('layouts.app')

@section('title', 'Jenis Tanaman')

@section('content')
    @include('layouts.partials.flash')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Jenis Tanaman</h1>
        <a href="{{ route('plant-types.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Jenis Tanaman
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Hari Panen (perkiraan)</th>
                    <th>Deskripsi</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plantTypes as $index => $plantType)
                    <tr>
                        <td>{{ $plantTypes->firstItem() + $index }}</td>
                        <td>{{ $plantType->name }}</td>
                        <td>{{ $plantType->estimated_harvest_days }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($plantType->description, 50) }}</td>
                        <td>{{ $plantType->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('plant-types.edit', $plantType->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('plant-types.destroy', $plantType->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis tanaman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data jenis tanaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $plantTypes->links() }}
    </div>
@endsection
