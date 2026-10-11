@extends('reports.pdf.layout')

@section('title', 'Laporan Penjualan')
@section('report-title', 'Laporan Transaksi Penjualan')

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
                    <div class="summary-title">Total Pendapatan (Sukses)</div>
                    <div class="summary-value" style="color: #166534;">Rp {{ number_format($summary->sum('total_revenue'), 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="summary-card" style="border-left-color: #3b82f6;">
                    <div class="summary-title">Total Transaksi</div>
                    <div class="summary-value">{{ number_format($summary->sum('total_transactions'), 0, ',', '.') }} <span style="font-size: 10px; font-weight: normal; color: #6b7280;">Order</span></div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 100px;">Tanggal</th>
                <th style="width: 110px;">Invoice</th>
                <th>Pelanggan</th>
                <th style="width: 100px;" class="text-right">Total Transaksi</th>
                <th style="width: 75px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $trx)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $trx->created_at ? $trx->created_at->format('d M Y H:i') : '-' }}</td>
                    <td class="font-mono"><strong>{{ $trx->invoice_number }}</strong></td>
                    <td>{{ optional($trx->user)->name ?? 'Pelanggan Umum' }}</td>
                    <td class="text-right font-mono" style="font-weight: bold;">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @php
                            $status = strtolower($trx->status);
                            $isSuccess = in_array($status, ['terbayarkan', 'paid', 'completed', 'sukses']);
                            $isCancelled = in_array($status, ['batal', 'cancelled', 'cancel', 'failed']);
                            $isPending = in_array($status, ['pending', 'menunggu']);
                        @endphp
                        @if($isSuccess)
                            <span class="badge badge-green">{{ ucfirst($trx->status) }}</span>
                        @elseif($isPending)
                            <span class="badge badge-yellow">{{ ucfirst($trx->status) }}</span>
                        @elseif($isCancelled)
                            <span class="badge badge-red">{{ ucfirst($trx->status) }}</span>
                        @else
                            <span class="badge badge-blue">{{ ucfirst($trx->status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 18px; color: #9ca3af;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

