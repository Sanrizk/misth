@extends('reports.pdf.layout')

@section('title', 'Laporan Penggunaan Bahan')
@section('report-title', 'Laporan Penggunaan Bahan')

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
    {{-- Summary Table of Materials --}}
    @if(count($summary) > 0)
        <table class="data-table" cellpadding="0" cellspacing="0" style="margin-bottom: 16px;">
            <thead>
                <tr>
                    <th colspan="3" style="background-color: #e5e7eb; color: #1f2937;">Ringkasan Total Penggunaan per Bahan</th>
                </tr>
                <tr>
                    <th style="width: 30px;" class="text-center">No</th>
                    <th>Nama Bahan</th>
                    <th style="width: 140px;" class="text-right">Total Kuantitas</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($summary as $materialName => $data)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td><strong>{{ $materialName }}</strong></td>
                        <td class="text-right font-mono"><strong>{{ number_format($data['total_quantity'], 2, ',', '.') }}</strong> {{ $data['unit'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div style="font-size: 11px; font-weight: bold; margin-bottom: 6px; color: #374151;">Rincian Log Penggunaan Bahan</div>

    {{-- Data Table --}}
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 85px;">Tanggal Log</th>
                <th style="width: 100px;">Batch Tanam</th>
                <th>Bahan</th>
                <th style="width: 90px;" class="text-right">Kuantitas</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usages as $index => $usage)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $usage->maintenanceLog && $usage->maintenanceLog->activity_date ? \Carbon\Carbon::parse($usage->maintenanceLog->activity_date)->format('d M Y') : ($usage->created_at ? $usage->created_at->format('d M Y') : '-') }}</td>
                    <td class="font-mono">{{ optional(optional($usage->maintenanceLog)->planting)->batch_code ?? '-' }}</td>
                    <td><strong>{{ optional($usage->material)->name ?? 'Bahan Dihapus' }}</strong></td>
                    <td class="text-right font-mono">{{ number_format($usage->quantity_used, 2, ',', '.') }} {{ optional($usage->material)->unit }}</td>
                    <td style="color: #4b5563;">{{ $usage->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 18px; color: #9ca3af;">Tidak ada data penggunaan bahan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

