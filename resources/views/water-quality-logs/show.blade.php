@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Catatan Kualitas Air</h2>

    <div class="card mt-4">
        <div class="card-header">
            Informasi Catatan Kualitas Air
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>Batch Code</th>
                        <td>{{ optional($waterQualityLog->planting)->batch_code }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Tanaman</th>
                        <td>{{ optional(optional($waterQualityLog->planting)->plantType)->name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Cek</th>
                        <td>{{ date('Y-m-d H:i', strtotime($waterQualityLog->checked_at)) }}</td>
                    </tr>
                    <tr>
                        <th>pH Level</th>
                        <td>{{ $waterQualityLog->ph_level }}</td>
                    </tr>
                    <tr>
                        <th>TDS PPM</th>
                        <td>{{ number_format($waterQualityLog->tds_ppm, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Suhu Air</th>
                        <td>{{ $waterQualityLog->water_temp }}</td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td>{{ $waterQualityLog->notes }}</td>
                    </tr>
                </table>
            </div>
            
            <a href="{{ route('water-quality-logs.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
