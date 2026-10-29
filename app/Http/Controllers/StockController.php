<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\StockAdjustment;
use App\Models\PurchaseItem;
use App\Models\MaterialUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StockController extends Controller
{
    /**
     * Dashboard / Halaman Utama Menu Stok
     */
    public function index()
    {
        $totalMaterials = Material::count();
        $activeMaterials = Material::where('status', 'active')->count();
        $lowStockCount = Material::whereColumn('stock', '<=', 'min_stock')->count();
        $totalValuation = Material::selectRaw('SUM(stock * price_per_unit) as total')->value('total') ?? 0;

        $recentAdjustments = StockAdjustment::with(['material', 'user'])->latest()->take(5)->get();

        return view('stocks.index', compact(
            'totalMaterials',
            'activeMaterials',
            'lowStockCount',
            'totalValuation',
            'recentAdjustments'
        ));
    }

    /**
     * Cek Stok Keseluruhan Bahan (Bisa filter rentang tanggal aktivitas stok & pencarian/kategori)
     */
    public function summary(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $category = $request->input('category');

        $materialsQuery = Material::query();

        if ($search) {
            $materialsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $materialsQuery->where('category', $category);
        }

        $materials = $materialsQuery->orderBy('name')->get();

        // Jika rentang tanggal dipilih, hitung in & out dalam periode tersebut
        $summaryData = $materials->map(function ($material) use ($startDate, $endDate) {
            $inQty = 0;
            $outQty = 0;

            if ($startDate && $endDate) {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();

                // 1. Pembelian masuk (status received)
                $inPurchase = PurchaseItem::where('material_id', $material->id)
                    ->whereHas('purchase', function ($q) use ($start, $end) {
                        $q->where('status', 'received')
                          ->whereBetween('purchase_date', [$start, $end]);
                    })->sum('quantity');

                // 2. Penyesuaian masuk (opname type = in)
                $inAdj = StockAdjustment::where('material_id', $material->id)
                    ->where('type', 'in')
                    ->whereBetween('adjustment_date', [$start, $end])
                    ->sum('quantity');

                $inQty = $inPurchase + $inAdj;

                // 3. Pemakaian perawatan (material usages)
                $outUsage = MaterialUsage::where('material_id', $material->id)
                    ->whereBetween('created_at', [$start, $end])
                    ->sum('quantity_used');

                // 4. Penyesuaian keluar (opname type = out)
                $outAdj = StockAdjustment::where('material_id', $material->id)
                    ->where('type', 'out')
                    ->whereBetween('adjustment_date', [$start, $end])
                    ->sum('quantity');

                $outQty = $outUsage + $outAdj;
            }

            return (object) [
                'id' => $material->id,
                'code' => $material->code,
                'name' => $material->name,
                'category' => $material->category,
                'unit' => $material->unit,
                'current_stock' => $material->stock,
                'min_stock' => $material->min_stock,
                'price_per_unit' => $material->price_per_unit,
                'valuation' => $material->stock * $material->price_per_unit,
                'in_quantity' => $inQty,
                'out_quantity' => $outQty,
            ];
        });

        $categories = Material::select('category')->distinct()->pluck('category')->filter()->values();

        return view('stocks.summary', compact('summaryData', 'startDate', 'endDate', 'search', 'category', 'categories'));
    }

    /**
     * Mutasi Stok per Bahan (Kartu Stok dengan filter rentang tanggal & pilihan bahan)
     */
    public function mutations(Request $request)
    {
        $materials = Material::orderBy('name')->get();
        $selectedMaterialId = $request->input('material_id');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $selectedMaterial = null;
        $mutations = collect();

        if ($selectedMaterialId) {
            $selectedMaterial = Material::findOrFail($selectedMaterialId);

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            // 1. Ambil data Pembelian Masuk
            $purchases = PurchaseItem::where('material_id', $selectedMaterialId)
                ->whereHas('purchase', function ($q) use ($start, $end) {
                    $q->where('status', 'received')
                      ->whereBetween('purchase_date', [$start, $end]);
                })
                ->with('purchase.supplier')
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'date' => Carbon::parse($item->purchase->purchase_date),
                        'reference' => $item->purchase->purchase_number,
                        'source' => 'purchase',
                        'category' => 'Pembelian Masuk',
                        'in_quantity' => (float) $item->quantity,
                        'out_quantity' => 0,
                        'notes' => 'Supplier: ' . (optional($item->purchase->supplier)->name ?? '-'),
                    ];
                });

            // 2. Ambil data Pemakaian Perawatan
            $usages = MaterialUsage::where('material_id', $selectedMaterialId)
                ->whereBetween('created_at', [$start, $end])
                ->with('maintenanceLog.planting.plantType')
                ->get()
                ->map(function ($usage) {
                    $batchCode = optional(optional($usage->maintenanceLog)->planting)->batch_code ?? '-';
                    $plantName = optional(optional(optional($usage->maintenanceLog)->planting)->plantType)->name ?? '';
                    return (object) [
                        'date' => $usage->created_at,
                        'reference' => 'RAWAT-' . ($usage->maintenance_log_id ?? '-'),
                        'source' => 'usage',
                        'category' => 'Pemakaian Rawat',
                        'in_quantity' => 0,
                        'out_quantity' => (float) $usage->quantity_used,
                        'notes' => 'Batch: ' . $batchCode . ($plantName ? ' (' . $plantName . ')' : '') . ($usage->notes ? ' - ' . $usage->notes : ''),
                    ];
                });

            // 3. Ambil data Penyesuaian Stok (Stok Opname)
            $adjustments = StockAdjustment::where('material_id', $selectedMaterialId)
                ->whereBetween('adjustment_date', [$start, $end])
                ->get()
                ->map(function ($adj) {
                    return (object) [
                        'date' => Carbon::parse($adj->adjustment_date),
                        'reference' => $adj->adjustment_number,
                        'source' => $adj->type === 'in' ? 'opname_in' : 'opname_out',
                        'category' => $adj->type === 'in' ? 'Opname (Penambahan)' : 'Opname (Pengurangan)',
                        'in_quantity' => $adj->type === 'in' ? (float) $adj->quantity : 0,
                        'out_quantity' => $adj->type === 'out' ? (float) $adj->quantity : 0,
                        'notes' => ($adj->reason ? '[' . $adj->reason . '] ' : '') . ($adj->notes ?? ''),
                    ];
                });

            // Gabungkan dan urutkan berdasarkan tanggal ascending
            $mutations = $purchases->concat($usages)->concat($adjustments)->sortBy(function ($item) {
                return $item->date->timestamp;
            })->values();
        }

        $materialId = $selectedMaterialId;

        return view('stocks.mutations', compact('materials', 'selectedMaterial', 'mutations', 'startDate', 'endDate', 'materialId'));
    }

    /**
     * Halaman Stok Opname (Daftar & Form Penyesuaian Fisik)
     */
    public function opname(Request $request)
    {
        $materials = Material::where('status', 'active')->orderBy('name')->get();
        $materialId = $request->input('material_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = StockAdjustment::with(['material', 'user'])->latest();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('adjustment_date', [$startDate, $endDate]);
        }

        if ($request->filled('material_id')) {
            $query->where('material_id', $materialId);
        }

        $adjustments = $query->paginate(15);

        return view('stocks.opname', compact('materials', 'adjustments', 'materialId', 'startDate', 'endDate'));
    }

    /**
     * Simpan Data Penyesuaian Stok Opname
     */
    public function storeOpname(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'adjustment_date' => 'required|date',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:150',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $material = Material::lockForUpdate()->findOrFail($validated['material_id']);
            $stockBefore = $material->stock;

            if ($validated['type'] === 'in') {
                $stockAfter = $stockBefore + $validated['quantity'];
            } else {
                if ($stockBefore < $validated['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Jumlah pengurangan melebihi stok yang tersedia saat ini (' . $stockBefore . ' ' . $material->unit . ').',
                    ]);
                }
                $stockAfter = $stockBefore - $validated['quantity'];
            }

            // Update stok fisik di tabel materials
            $material->update(['stock' => $stockAfter]);

            // Catat log stok opname
            $adjustmentNumber = 'ADJ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            StockAdjustment::create([
                'adjustment_number' => $adjustmentNumber,
                'material_id' => $material->id,
                'user_id' => Auth::id(),
                'adjustment_date' => $validated['adjustment_date'],
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('stocks.opname')->with('success', 'Penyesuaian stok opname berhasil disimpan dan stok bahan telah diperbarui.');
    }

    /**
     * Update Penyesuaian Stok Opname (Rollback efek lama, terapkan efek baru)
     */
    public function updateOpname(Request $request, StockAdjustment $stockAdjustment)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'adjustment_date' => 'required|date',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:150',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $stockAdjustment) {
            // 1. Rollback penyesuaian lama pada bahan asalnya
            $oldMaterial = Material::lockForUpdate()->findOrFail($stockAdjustment->material_id);
            if ($stockAdjustment->type === 'in') {
                if ($oldMaterial->stock < $stockAdjustment->quantity) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Gagal mengubah: stok bahan "' . $oldMaterial->name . '" saat ini tidak mencukupi untuk membatalkan penambahan opname sebelumnya.',
                    ]);
                }
                $oldMaterial->decrement('stock', $stockAdjustment->quantity);
            } else {
                $oldMaterial->increment('stock', $stockAdjustment->quantity);
            }

            // 2. Terapkan penyesuaian baru pada target material (bisa sama atau beda jika diganti)
            $newMaterial = ($oldMaterial->id == $validated['material_id']) 
                ? $oldMaterial 
                : Material::lockForUpdate()->findOrFail($validated['material_id']);

            $stockBefore = $newMaterial->stock;

            if ($validated['type'] === 'in') {
                $stockAfter = $stockBefore + $validated['quantity'];
            } else {
                if ($stockBefore < $validated['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Jumlah pengurangan melebihi stok yang tersedia saat ini (' . $stockBefore . ' ' . $newMaterial->unit . ').',
                    ]);
                }
                $stockAfter = $stockBefore - $validated['quantity'];
            }

            $newMaterial->update(['stock' => $stockAfter]);

            // 3. Update record StockAdjustment
            $stockAdjustment->update([
                'material_id' => $newMaterial->id,
                'user_id' => Auth::id(),
                'adjustment_date' => $validated['adjustment_date'],
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('stocks.opname')->with('success', 'Data stok opname berhasil diperbarui dan stok bahan telah disesuaikan.');
    }

    /**
     * Hapus Penyesuaian Stok Opname (Kembalikan stok ke sebelum penyesuaian)
     */
    public function destroyOpname(StockAdjustment $stockAdjustment)
    {
        DB::transaction(function () use ($stockAdjustment) {
            $material = Material::lockForUpdate()->findOrFail($stockAdjustment->material_id);

            // Revert dampak stok opname
            if ($stockAdjustment->type === 'in') {
                if ($material->stock < $stockAdjustment->quantity) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'error' => 'Gagal menghapus: stok bahan "' . $material->name . '" saat ini tidak mencukupi untuk membatalkan penambahan opname tersebut.',
                    ]);
                }
                $material->decrement('stock', $stockAdjustment->quantity);
            } else {
                $material->increment('stock', $stockAdjustment->quantity);
            }

            $stockAdjustment->delete();
        });

        return redirect()->route('stocks.opname')->with('success', 'Catatan stok opname berhasil dihapus dan efek stok telah dikembalikan.');
    }
}
