<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialUsageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'maintenance_log_id' => 'required|exists:maintenance_logs,id',
            'material_id' => 'required|exists:materials,id',
            'quantity_used' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $material = Material::lockForUpdate()->findOrFail($validated['material_id']);
            
            if ($material->stock < $validated['quantity_used']) {
                DB::rollBack();
                return back()->with('error', 'Stok bahan tidak mencukupi.');
            }

            MaterialUsage::create($validated);
            
            $material->stock -= $validated['quantity_used'];
            $material->save();

            DB::commit();
            return back()->with('success', 'Pemakaian bahan berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $usage = MaterialUsage::lockForUpdate()->findOrFail($id);
            $material = Material::lockForUpdate()->findOrFail($usage->material_id);
            
            $material->stock += $usage->quantity_used;
            $material->save();
            
            $usage->delete();

            DB::commit();
            return back()->with('success', 'Catatan pemakaian berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
