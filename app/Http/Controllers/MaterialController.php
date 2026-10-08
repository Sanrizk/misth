<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $materials = $query->orderBy('name')->paginate(10);
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|in:nutrient,pesticide,operational',
            'unit' => 'required|string|max:30',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $lastMaterial = Material::orderBy('id', 'desc')->first();
        $nextId = $lastMaterial ? $lastMaterial->id + 1 : 1;
        $validated['code'] = 'MAT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Material::create($validated);

        return redirect()->route('materials.index')->with('success', 'Bahan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $material = Material::with('materialUsages.maintenanceLog.planting')->findOrFail($id);
        
        // logs from active plantings
        $maintenanceLogs = MaintenanceLog::with('planting')
            ->whereHas('planting', function($q) {
                $q->where('status', 'active');
            })
            ->orderBy('activity_date', 'desc')
            ->get();

        return view('materials.show', compact('material', 'maintenanceLogs'));
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|in:nutrient,pesticide,operational',
            'unit' => 'required|string|max:30',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $material->update($validated);

        return redirect()->route('materials.index')->with('success', 'Bahan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        try {
            $material->delete();
            return redirect()->route('materials.index')->with('success', 'Bahan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('materials.index')->with('error', 'Bahan ini tidak dapat dihapus karena masih digunakan pada data pembelian atau transaksi lain.');
            }
            return redirect()->route('materials.index')->with('error', 'Terjadi kesalahan saat menghapus bahan.');
        }
    }
}
