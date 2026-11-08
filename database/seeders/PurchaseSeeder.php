<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Material;
use App\Models\Supplier;
use App\Models\User;
use App\Models\CashFlow;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        Purchase::truncate();
        PurchaseItem::truncate();

        $materials = Material::all();
        $suppliers = Supplier::all();
        $persediaanUser = User::whereHas('role', function($q) {
            $q->whereIn('name', ['persediaan', 'admin']);
        })->first() ?? User::first();

        if ($materials->isEmpty() || $suppliers->isEmpty()) {
            return;
        }

        $invCounter = 1;

        // Distribusikan pengadaan dari Januari sampai November 2026 (1-2 per bulan)
        for ($month = 1; $month <= 11; $month++) {
            $poCount = ($month === 11) ? 2 : rand(1, 2);

            for ($p = 0; $p < $poCount; $p++) {
                $day = rand(1, ($month === 2 ? 28 : ($month === 11 ? 7 : 28)));
                $purchaseDate = Carbon::create(2026, $month, $day);

                // Status logic: pembelian sebelum Oktober kebanyakan sudah diterima (received), sebagian confirmed / draft
                if ($month <= 9) {
                    $status = (rand(1, 10) <= 8) ? 'received' : 'confirmed';
                } elseif ($month === 10) {
                    $status = (rand(1, 10) <= 6) ? 'received' : 'confirmed';
                } else {
                    $status = fake()->randomElement(['draft', 'confirmed', 'received']);
                }

                $supplier = $suppliers->random();
                $invoiceNumber = 'INV-2026' . sprintf('%02d', $month) . '-' . sprintf('%03d', $invCounter++);

                $purchase = Purchase::create([
                    'supplier_id' => $supplier->id,
                    'user_id' => $persediaanUser->id ?? 1,
                    'purchase_date' => $purchaseDate->toDateString(),
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => 0,
                    'status' => $status,
                    'notes' => 'Pengadaan rutin bahan nutrisi & perlengkapan periode ' . $purchaseDate->translatedFormat('F Y'),
                ]);

                $itemCount = rand(2, 4);
                $selectedMaterials = $materials->random(min($itemCount, $materials->count()));
                $totalAmount = 0;

                foreach ($selectedMaterials as $mat) {
                    $qty = rand(5, 30);
                    $price = $mat->price_per_unit > 0 ? $mat->price_per_unit : rand(15000, 75000);
                    $subtotal = $qty * $price;
                    $totalAmount += $subtotal;

                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'material_id' => $mat->id,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'subtotal' => $subtotal,
                    ]);

                    // Jika status received, tambah stok bahan
                    if ($status === 'received') {
                        $mat->stock += $qty;
                        if ($mat->stock > 0 && $mat->status === 'inactive') {
                            $mat->status = 'active';
                        }
                        $mat->save();
                    }
                }

                $purchase->update(['total_amount' => $totalAmount]);

                // Jika status confirmed atau received, otomatis catat kas keluar terintegrasi
                if (in_array($status, ['confirmed', 'received'])) {
                    CashFlow::create([
                        'cash_number' => 'KAS-BELI-' . $invoiceNumber,
                        'transaction_date' => $purchaseDate->toDateString(),
                        'type' => 'out',
                        'category' => 'Pembelian Bahan',
                        'amount' => $totalAmount,
                        'user_id' => $persediaanUser->id ?? null,
                        'reference_type' => 'purchase',
                        'reference_id' => $purchase->id,
                        'description' => "Pengadaan bahan [{$invoiceNumber}] dari {$supplier->name}",
                    ]);
                }
            }
        }
    }
}
