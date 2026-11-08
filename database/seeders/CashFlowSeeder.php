<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashFlow;
use App\Models\User;
use Carbon\Carbon;

class CashFlowSeeder extends Seeder
{
    public function run(): void
    {
        // Catatan: transaksi arus kas dari pembelian sudah digenerate di PurchaseSeeder (reference_type = 'purchase').
        // Di sini kita lengkapi dengan transaksi operasional bulanan:
        // 1. Pemasukan Penjualan Hasil Panen rutin tiap bulan (Januari - November 2026)
        // 2. Pengeluaran Rutin Listrik, Air, Gaji, Logistik, dsb.

        $keuanganUser = User::whereHas('role', function($q) {
            $q->whereIn('name', ['keuangan', 'admin']);
        })->first() ?? User::first();

        $cashSeq = 1;

        for ($month = 1; $month <= 11; $month++) {
            $yearMonthStr = '2026' . sprintf('%02d', $month);

            // --- 1. PEMASUKAN BULANAN (Inflows) ---
            // Penjualan Panen Sayuran Hidroponik (2 - 4 transaksi per bulan)
            $salesCount = rand(2, 4);
            for ($s = 1; $s <= $salesCount; $s++) {
                $day = rand(4, ($month === 2 ? 27 : ($month === 11 ? 7 : 28)));
                $date = Carbon::create(2026, $month, $day);

                $saleAmount = rand(15, 60) * 100000; // Rp 1.500.000 - Rp 6.000.000
                $cropName = fake()->randomElement(['Selada Keriting', 'Pakcoy Hidroponik', 'Bayam Merah', 'Kangkung Super', 'Sawi Manis']);

                CashFlow::create([
                    'cash_number' => 'KAS-' . $date->format('Ymd') . '-' . sprintf('%03d', $cashSeq++),
                    'transaction_date' => $date->toDateString(),
                    'type' => 'in',
                    'category' => 'Penjualan Panen',
                    'amount' => $saleAmount,
                    'user_id' => $keuanganUser->id ?? null,
                    'reference_type' => 'manual',
                    'reference_id' => null,
                    'description' => "Penjualan grosir hasil panen {$cropName} pekan ke-{$s}",
                ]);
            }

            // Sesekali ada pemasukan lain (Modal/Investasi/Penjualan Bibit)
            if ($month === 1 || $month === 6) {
                $date = Carbon::create(2026, $month, 2);
                CashFlow::create([
                    'cash_number' => 'KAS-' . $date->format('Ymd') . '-' . sprintf('%03d', $cashSeq++),
                    'transaction_date' => $date->toDateString(),
                    'type' => 'in',
                    'category' => ($month === 1) ? 'Investasi / Modal' : 'Subsidi / Hibah',
                    'amount' => ($month === 1) ? 15000000 : 5000000,
                    'user_id' => $keuanganUser->id ?? null,
                    'reference_type' => 'manual',
                    'reference_id' => null,
                    'description' => ($month === 1) ? 'Injeksi modal awal operasional greenhouse 2026' : 'Penerimaan dana stimulus pertanian ramah lingkungan',
                ]);
            }

            // --- 2. PENGELUARAN BULANAN (Outflows) ---
            // Tagihan Listrik & Air Tandon (setiap akhir bulan atau tanggal 20-an)
            $utilDate = Carbon::create(2026, $month, min($month === 11 ? 6 : 25, 28));
            CashFlow::create([
                'cash_number' => 'KAS-' . $utilDate->format('Ymd') . '-' . sprintf('%03d', $cashSeq++),
                'transaction_date' => $utilDate->toDateString(),
                'type' => 'out',
                'category' => 'Listrik & Air',
                'amount' => rand(650, 1200) * 1000, // Rp 650.000 - Rp 1.200.000
                'user_id' => $keuanganUser->id ?? null,
                'reference_type' => 'manual',
                'reference_id' => null,
                'description' => "Pembayaran tagihan listrik PLN & pompa air hidroponik bulan " . $utilDate->translatedFormat('F Y'),
            ]);

            // Upah / Gaji Petani & Tenaga Kebun
            $salaryDate = Carbon::create(2026, $month, min($month === 11 ? 5 : 28, 28));
            CashFlow::create([
                'cash_number' => 'KAS-' . $salaryDate->format('Ymd') . '-' . sprintf('%03d', $cashSeq++),
                'transaction_date' => $salaryDate->toDateString(),
                'type' => 'out',
                'category' => 'Gaji & Upah Petani',
                'amount' => 4500000, // Rp 4.500.000
                'user_id' => $keuanganUser->id ?? null,
                'reference_type' => 'manual',
                'reference_id' => null,
                'description' => "Honor operasional perawatan & pemanenan tim kebun bulan " . $salaryDate->translatedFormat('F Y'),
            ]);

            // Pemeliharaan & Perlengkapan Greenhouse
            if (rand(1, 10) <= 6) {
                $maintDate = Carbon::create(2026, $month, rand(10, min($month === 11 ? 7 : 20, 20)));
                CashFlow::create([
                    'cash_number' => 'KAS-' . $maintDate->format('Ymd') . '-' . sprintf('%03d', $cashSeq++),
                    'transaction_date' => $maintDate->toDateString(),
                    'type' => 'out',
                    'category' => 'Pemeliharaan Greenhouse',
                    'amount' => rand(200, 750) * 1000,
                    'user_id' => $keuanganUser->id ?? null,
                    'reference_type' => 'manual',
                    'reference_id' => null,
                    'description' => 'Servis pompa sirkulasi nutrisi & penggantian pipa selang fitting',
                ]);
            }
        }
    }
}
