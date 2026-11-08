<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order — {{ $purchase->invoice_number }}</title>
    <style>
        @page {
            margin: 28px 32px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 11px;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2.5px solid #16a34a;
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
        .po-title-cell {
            text-align: right;
            vertical-align: middle;
        }
        .po-main-title {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .po-invoice {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            font-weight: bold;
            color: #16a34a;
            margin-top: 3px;
        }

        /* Two Column Info Box */
        .info-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }
        .info-col {
            width: 50%;
            vertical-align: top;
        }
        .info-card {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 12px;
        }
        .info-card-left {
            margin-right: 6px;
            border-left: 3.5px solid #16a34a;
        }
        .info-card-right {
            margin-left: 6px;
            border-left: 3.5px solid #2563eb;
        }
        .info-card-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            border-bottom: 1px dashed #e5e7eb;
            padding-bottom: 4px;
        }
        .meta-table {
            width: 100%;
            font-size: 10.5px;
        }
        .meta-table td {
            padding: 2.5px 0;
            vertical-align: top;
        }
        .meta-label {
            width: 85px;
            color: #6b7280;
            font-weight: 500;
        }
        .meta-sep {
            width: 10px;
            color: #9ca3af;
            text-align: center;
        }
        .meta-value {
            color: #111827;
            font-weight: 600;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-draft { background-color: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; }
        .badge-confirmed { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-received { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-cancelled { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .items-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 7px 8px;
            border: 1px solid #d1d5db;
        }
        .items-table td {
            padding: 7px 8px;
            border: 1px solid #e5e7eb;
            font-size: 10px;
            vertical-align: middle;
        }
        .items-table tbody tr:nth-child(even) {
            background-color: #fcfdfd;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }

        /* Total Section */
        .total-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .total-box {
            background-color: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 6px;
            padding: 10px 14px;
            text-align: right;
        }
        .total-label {
            font-size: 11px;
            font-weight: bold;
            color: #166534;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #15803d;
            margin-left: 8px;
        }

        /* Notes Box */
        .notes-card {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 24px;
            font-size: 10px;
        }
        .notes-title {
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
            font-size: 8.5px;
            margin-bottom: 2px;
        }
        .notes-content {
            color: #78350f;
            font-style: italic;
        }

        /* Signatures */
        .sig-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .sig-col {
            width: 50%;
            vertical-align: top;
        }
        .sig-box {
            text-align: center;
            width: 200px;
        }
        .sig-box-right {
            margin-left: auto;
        }
        .sig-date {
            font-size: 10px;
            color: #4b5563;
            margin-bottom: 4px;
        }
        .sig-title {
            font-size: 10.5px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 55px;
        }
        .sig-name {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
            text-decoration: underline;
        }
        .sig-role {
            font-size: 9.5px;
            color: #6b7280;
            margin-top: 2px;
        }

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
</head>
<body>
    @php
        $logoPath = null;
        $candidatePaths = [
            function_exists('public_path') ? public_path('images/logo.png') : null,
            base_path('public/images/logo.png'),
            base_path('../public_html/images/logo.png'),
            base_path('public_html/images/logo.png'),
            isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] . '/images/logo.png' : null,
        ];
        foreach ($candidatePaths as $p) {
            if ($p && @file_exists($p)) {
                $logoPath = $p;
                break;
            }
        }
    @endphp

    {{-- KOP SURAT / HEADER --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align: middle;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        @if($logoPath)
                        <td style="vertical-align: middle; padding-right: 12px;">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" style="width: 44px; height: 44px; border-radius: 50%;">
                        </td>
                        @endif
                        <td style="vertical-align: middle;">
                            <div class="company-name">MISTH</div>
                            <div class="company-subtitle">Manajemen Inventori Stok dan Transaksi Hidroponik</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="po-title-cell">
                <div class="po-main-title">PURCHASE ORDER</div>
                <div class="po-invoice">{{ $purchase->invoice_number }}</div>
            </td>
        </tr>
    </table>

    {{-- INFORMASI PEMBELIAN & SUPPLIER DUA KOLOM --}}
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            {{-- KOLOM KIRI: Informasi Dokumen & Petugas --}}
            <td class="info-col">
                <div class="info-card info-card-left">
                    <div class="info-card-title">Informasi Dokumen & Petugas</div>
                    <table class="meta-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="meta-label">No. Invoice</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value font-mono">{{ $purchase->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Tanggal PO</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value">{{ \Carbon\Carbon::parse($purchase->purchase_date)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Status PO</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value">
                                @if($purchase->status === 'draft')
                                    <span class="badge badge-draft">Draft</span>
                                @elseif($purchase->status === 'confirmed')
                                    <span class="badge badge-confirmed">Confirmed</span>
                                @elseif($purchase->status === 'received')
                                    <span class="badge badge-received">Received</span>
                                @else
                                    <span class="badge badge-cancelled">{{ ucfirst($purchase->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="meta-label">Petugas</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value">{{ $purchase->user->name ?? 'Admin' }} ({{ $purchase->user->role->name ?? '-' }})</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Role Petugas</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value">{{ ucfirst($purchase->user->role->name ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Email Petugas</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value">{{ $purchase->user->email ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </td>

            {{-- KOLOM KANAN: Informasi Supplier --}}
            <td class="info-col">
                <div class="info-card info-card-right">
                    <div class="info-card-title">Informasi Supplier / Vendor</div>
                    <table class="meta-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="meta-label">Supplier</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value">{{ $purchase->supplier->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">No. Telepon</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value">{{ $purchase->supplier->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Alamat</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value">{{ $purchase->supplier->address ?? '-' }}</td>
                        </tr>
                        @if($purchase->notes)
                        <tr>
                            <td class="meta-label">Catatan</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-value" style="font-weight: normal; color: #4b5563;">{{ $purchase->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- TABEL ITEM BAHAN BAKU --}}
    <table class="items-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th style="width: 75px;">Kode</th>
                <th>Nama Bahan Baku</th>
                <th style="width: 80px;">Kategori</th>
                <th class="text-center" style="width: 55px;">Satuan</th>
                <th class="text-center" style="width: 45px;">Qty</th>
                <th class="text-right" style="width: 85px;">Harga Satuan</th>
                <th class="text-right" style="width: 95px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchase->purchaseItems as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $item->material->code ?? '-' }}</td>
                    <td style="font-weight: 600;">{{ $item->material->name ?? '-' }}</td>
                    <td>{{ ucfirst($item->material->category ?? '-') }}</td>
                    <td class="text-center">{{ $item->material->unit ?? '-' }}</td>
                    <td class="text-center" style="font-weight: 600;">{{ rtrim(rtrim(number_format($item->quantity, 2, ',', '.'), '0'), ',') }}</td>
                    <td class="text-right font-mono">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right font-mono" style="font-weight: 600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 16px; color: #9ca3af;">Tidak ada rincian item.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TOTAL PEMBELIAN BOX --}}
    <div class="total-box">
        <span class="total-label">TOTAL PEMBELIAN :</span>
        <span class="total-amount font-mono">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</span>
    </div>

    {{-- CATATAN TAMBAHAN JIKA ADA --}}
    @if($purchase->notes)
    <div class="notes-card">
        <div class="notes-title">Catatan Pembelian:</div>
        <div class="notes-content">"{{ $purchase->notes }}"</div>
    </div>
    @endif

    {{-- TANDA TANGAN / PENGESAHAN --}}
    <table class="sig-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="sig-col">
                <div class="sig-box">
                    <div class="sig-date">&nbsp;</div>
                    <div class="sig-title">Penerima / Vendor</div>
                    <div class="sig-name">{{ $purchase->supplier->name ?? 'Supplier' }}</div>
                    <div class="sig-role">Perwakilan Rekanan</div>
                </div>
            </td>
            <td class="sig-col">
                <div class="sig-box sig-box-right">
                    <div class="sig-date">{{ \Carbon\Carbon::parse($purchase->purchase_date)->translatedFormat('d F Y') }}</div>
                    <div class="sig-title">Disetujui Oleh</div>
                    <div class="sig-name">{{ $purchase->user->name ?? 'Petugas' }}</div>
                    <div class="sig-role">{{ ucfirst($purchase->user->role->name ?? 'Staff Persediaan') }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- FOOTER OTOMATIS --}}
    <div class="footer">
        Dokumen Purchase Order ini dicetak secara otomatis dari Sistem Misth pada {{ now()->translatedFormat('d/m/Y H:i:s') }} WIB
    </div>
</body>
</html>
