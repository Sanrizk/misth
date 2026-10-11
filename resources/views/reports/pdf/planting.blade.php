@extends('reports.pdf.layout')

@section('title', 'Laporan Penanaman')
@section('report-title', 'Laporan Penanaman')

@section('report-period')
    @if($startDate && $endDate)
        {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    @elseif($startDate)
        Mulai {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
    @elseif($endDate)
        Sampai {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    @else
        Semua Periode
    @endif
@endsection

@section('content')
    {{-- Summary Cards --}}
    <table class="summary-table" cellpadding="0" cellspacing="0">
        <tr>
            @foreach(['in_progress' => ['label' => 'Sedang Berjalan', 'border' => '#3b82f6'], 'harvested' => ['label' => 'Sudah Panen', 'border' => '#22c55e'], 'failed' => ['label' => 'Gagal', 'border' => '#ef4444']] as $status => $meta)
                @php $total = isset($summary[$status]) ? $summary[$status]->sum() : 0; @endphp
                <td style="width: 33.33%;">
                    <div class="summary-card" style="border-left-color: {{ $meta['border'] }};">
                        <div class="summary-title">{{ $meta['label'] }}</div>
                        <div class="summary-value">{{ $total }} <span style="font-size: 10px; font-weight: normal; color: #6b7280;">Siklus</span></div>
                    </div>
                </td>
            @endforeach
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 110px;">Batch Code</th>
                <th>Jenis Tanaman</th>
                <th style="width: 90px;">Tgl Tanam</th>
                <th style="width: 70px;" class="text-right">Jml Bibit</th>
                <th style="width: 80px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plantings as $index => $planting)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $planting->batch_code }}</td>
                    <td><strong>{{ $planting->plantType->name }}</strong></td>
                    <td>{{ $planting->start_date ? $planting->start_date->format('d M Y') : '-' }}</td>
                    <td class="text-right">{{ number_format($planting->quantity_seeds, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($planting->status === 'in_progress')
                            <span class="badge badge-blue">Berjalan</span>
                        @elseif($planting->status === 'harvested')
                            <span class="badge badge-green">Panen</span>
                        @else
                            <span class="badge badge-red">Gagal</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 18px; color: #9ca3af;">Tidak ada data penanaman pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

