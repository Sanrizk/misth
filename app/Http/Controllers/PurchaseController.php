<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\Material;
use App\Models\Supplier;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user']);

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('supplier', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('purchase_date', [$request->start_date, $request->end_date]);
        }

        $purchases = $query->paginate(10);
        
        if ($request->wantsJson()) {
            return response()->json($purchases);
        }

        $suppliers = Supplier::all();
        $materials = Material::all();

        return view('purchases.index', compact('purchases', 'suppliers', 'materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id() ?? 1, // fallback to 1 for tests/seeder if needed, or simply auth()->id()
                'purchase_date' => $validated['purchase_date'],
                'notes' => $validated['notes'],
                'status' => 'draft',
                'invoice_number' => 'INV-' . time(),
            ]);

            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                $purchase->purchaseItems()->create([
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $purchase->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil ditambahkan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'purchaseItems.material']);
        return response()->json($purchase);
    }

    public function updateStatus(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,confirmed,received,cancelled',
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $purchase->status;

        if ($newStatus === $oldStatus) {
            return redirect()->route('purchases.index');
        }

        DB::transaction(function () use ($purchase, $newStatus, $oldStatus) {
            // When status changes to received
            if ($newStatus === 'received' && $oldStatus !== 'received') {
                foreach ($purchase->purchaseItems as $item) {
                    $material = $item->material;
                    $material->stock += $item->quantity;
                    if ($material->stock > 0 && $material->status === 'inactive') {
                        $material->status = 'active';
                    }
                    $material->save();
                }
            }

            // When status changes to cancelled from received
            if ($newStatus === 'cancelled' && $oldStatus === 'received') {
                foreach ($purchase->purchaseItems as $item) {
                    $material = $item->material;
                    $material->stock -= $item->quantity;
                    $material->save();
                }
            }

            $purchase->update(['status' => $newStatus]);
        });

        return redirect()->route('purchases.index')->with('success', 'Status pembelian diperbarui.');
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->status !== 'draft') {
            return redirect()->route('purchases.index')->with('error', 'Hanya pembelian draft yang bisa dihapus.');
        }

        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
