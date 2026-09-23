@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Detail Panen</h2>
        <a href="{{ route('harvests.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Informasi Panen</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">Batch Code Penanaman</th>
                            <td>{{ optional($harvest->planting)->batch_code }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Tanaman</th>
                            <td>{{ optional(optional($harvest->planting)->plantType)->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Panen</th>
                            <td>{{ \Carbon\Carbon::parse($harvest->harvest_date)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Panen</th>
                            <td>{{ $harvest->total_yield_quantity }}</td>
                        </tr>
                        <tr>
                            <th>Berat Total (kg)</th>
                            <td>{{ number_format($harvest->total_yield_weight, 2, ',', '.') }} kg</td>
                        </tr>
                        <tr>
                            <th>Grade</th>
                            <td>
                                @if($harvest->quality_grade == 'Grade A')
                                    <span class="badge bg-success">{{ $harvest->quality_grade }}</span>
                                @elseif($harvest->quality_grade == 'Grade B')
                                    <span class="badge bg-warning text-dark">{{ $harvest->quality_grade }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $harvest->quality_grade }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $harvest->notes ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Produk Terbuat Otomatis</strong>
        </div>
        <div class="card-body">
            @if($harvest->product)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">Nama Produk</th>
                                <td>{{ $harvest->product->name }}</td>
                            </tr>
                            <tr>
                                <th>Stok (Kuantitas)</th>
                                <td>{{ $harvest->product->stock }}</td>
                            </tr>
                            <tr>
                                <th>Harga Jual per Unit</th>
                                <td>Rp {{ number_format($harvest->product->price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($harvest->product->status == 'available')
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $harvest->product->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">Belum ada produk yang tertaut dengan panen ini.</div>
            @endif
        </div>
    </div>
</div>
@endsection
