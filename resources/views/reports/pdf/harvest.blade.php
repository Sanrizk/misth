@extends('reports.pdf.layout')

@section('title', 'Laporan Panen')
@section('report-title', 'Laporan Hasil Panen')

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
            <td style="width: 50%;">
                <div class="summary-card" style="border-left-color: #16a34a;">
                    <div class="summary-title">Total Kuantitas Panen</div>
                    <div class="summary-value">{{ number_format($summary->sum('total_yield_quantity'), 0, ',', '.') }} <span style="font-size: 10px; font-weight: normal; color: #6b7280;">Item/Pcs</span></div>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="summary-card" style="border-left-color: #16a34a;">
                    <div class="summary-title">Total Berat Hasil Panen</div>
                    <div class="summary-value">{{ number_format($summary->sum('total_yield_weight'), 2, ',', '.') }} <span style="font-size: 10px; font-weight: normal; color: #6b7280;">Kg</span></div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 85px;">Tanggal</th>
                <th style="width: 100px;">Batch Tanam</th>
                <th>Tanaman</th>
                <th style="width: 75px;" class="text-right">Kuantitas</th>
                <th style="width: 80px;" class="text-right">Berat Total</th>
                <th style="width: 70px;" class="text-center">Kualitas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($harvests as $index => $harvest)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($harvest->harvest_date)->format('d M Y') }}</td>
                    <td class="font-mono">{{ optional($harvest->planting)->batch_code ?? '-' }}</td>
                    <td><strong>{{ optional(optional($harvest->planting)->plantType)->name ?? '-' }}</strong></td>
                    <td class="text-right">{{ number_format($harvest->total_yield_quantity, 0, ',', '.') }} pcs</td>
                    <td class="text-right font-mono">{{ number_format($harvest->total_yield_weight, 2, ',', '.') }} kg</td>
                    <td class="text-center">
                        <span class="badge badge-green">{{ $harvest->quality_grade ?? 'A' }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 18px; color: #9ca3af;">Tidak ada data panen pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

