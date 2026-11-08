<?php

namespace App\Http\Controllers;

use App\Models\Harvest;
use App\Models\Planting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HarvestController extends Controller
{
    public function index()
    {
        $harvests = Harvest::with('planting.plantType')->paginate(10);
        return view('harvests.index', compact('harvests'));
    }

    public function create()
    {
        $plantings = Planting::where('status', 'in_progress')->get();
        return view('harvests.create', compact('plantings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'planting_id' => 'required|exists:plantings,id',
            'harvest_date' => 'required|date',
            'total_yield_quantity' => 'required|integer|min:1',
            'total_yield_weight' => 'required|numeric|min:0',
            'quality_grade' => 'required|in:Grade A,Grade B,Grade C',
            'unit' => 'nullable|string|in:kg,ons,ikat,pcs,gram',
            'notes' => 'nullable|string',
            'is_finished' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request) {
            Harvest::create([
                'planting_id' => $validated['planting_id'],
                'harvest_date' => $validated['harvest_date'],
                'total_yield_quantity' => $validated['total_yield_quantity'],
                'total_yield_weight' => $validated['total_yield_weight'],
                'quality_grade' => $validated['quality_grade'],
                'unit' => $validated['unit'] ?? 'kg',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Jika checkbox panen habis dicentang, update status penanaman menjadi 'harvested'
            if ($request->boolean('is_finished')) {
                $planting = Planting::with('plantType')->findOrFail($validated['planting_id']);
                $planting->update(['status' => 'harvested']);
            }
        });

        return redirect()->back()->with('success', 'Data panen berhasil disimpan.');
    }

    public function show($id)
    {
        $harvest = Harvest::with(['planting.plantType'])->findOrFail($id);
        return view('harvests.show', compact('harvest'));
    }

    public function destroy($id)
    {
        $harvest = Harvest::findOrFail($id);
        $harvest->delete();

        return redirect()->back()->with('success', 'Data panen berhasil dihapus.');
    }
}
