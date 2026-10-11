<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Laporan')</title>
    <style>
        @page {
            margin: 24px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #16a34a;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #166534;
            letter-spacing: 0.5px;
        }
        .company-subtitle {
            font-size: 10px;
            color: #4b5563;
            margin-top: 2px;
        }
        .report-title-cell {
            text-align: right;
        }
        .report-title {
            font-size: 15px;
            font-weight: bold;
            color: #1f2937;
            text-transform: uppercase;
        }
        .report-meta {
            font-size: 9px;
            color: #6b7280;
            margin-top: 3px;
            line-height: 1.3;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }
        .summary-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #16a34a;
            border-radius: 4px;
            padding: 8px 12px;
        }
        .summary-title {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: bold;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-top: 2px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 7px 8px;
            border: 1px solid #d1d5db;
            text-align: left;
        }
        .data-table td {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
            vertical-align: middle;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
        }
        .badge-green { background-color: #dcfce7; color: #166534; }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .badge-red { background-color: #fee2e2; color: #991b1b; }
        .badge-yellow { background-color: #fef9c3; color: #854d0e; }
        .badge-gray { background-color: #f3f4f6; color: #374151; }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8.5px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
    </style>
    @yield('extra-style')
</head>
<body>
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align: middle;">
                <div class="company-name">Misth Management</div>
                <div class="company-subtitle">Sistem Monitoring & Otomasi Pertanian Modern</div>
            </td>
            <td class="report-title-cell" style="vertical-align: middle;">
                <div class="report-title">@yield('report-title')</div>
                <div class="report-meta">
                    Periode: <strong>@yield('report-period')</strong><br>
                    Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    @yield('content')

    <div class="footer">
        Dokumen ini dicetak otomatis dari Sistem Misth pada {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>

