<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Planting;
use App\Models\Material;
use App\Models\MaterialUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenanceLogs = MaintenanceLog::with(['planting.plantType', 'user'])
            ->latest('activity_date')
            ->paginate(10);

        return view('maintenance-logs.index', compact('maintenanceLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plantings = Planting::with('plantType')
                        ->where('status', 'in_progress')
                        ->get();

        $materials = Material::where('status', 'active')->get();

        return view('maintenance-logs.create', compact('plantings', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'planting_id'   => 'required|exists:plantings,id',
            'activity_date' => 'required|date',
            'action_type'   => 'required|string|max:100',
            'nutrients_ppm' => 'nullable|integer|min:0',
            'notes'         => 'nullable|string',
            'material_ids'   => 'nullable|array',
            'material_ids.*' => 'exists:materials,id',
            'quantities'     => 'nullable|array',
            'quantities.*'   => 'numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $validated['user_id'] = Auth::id();

            $log = MaintenanceLog::create(collect($validated)
                    ->except(['material_ids', 'quantities'])->toArray());

            if (!empty($validated['material_ids'])) {
                foreach ($validated['material_ids'] as $index => $materialId) {
                    $qty = $validated['quantities'][$index] ?? 0;
                    if ($qty <= 0) continue;

                    $material = Material::findOrFail($materialId);

                    if ($material->stock < $qty) {
                        throw new \Exception("Stok {$material->name} tidak mencukupi.");
                    }

                    MaterialUsage::create([
                        'maintenance_log_id' => $log->id,
                        'material_id'        => $materialId,
                        'quantity_used'      => $qty,
                    ]);

                    $material->decrement('stock', $qty);
                }
            }
        });

        return redirect()->back()
                         ->with('success', 'Log perawatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $maintenanceLog = MaintenanceLog::with(['planting.plantType', 'user'])
            ->findOrFail($id);

        return view('maintenance-logs.show', compact('maintenanceLog'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);
        $maintenanceLog->delete();

        return redirect()->back()
            ->with('success', 'Log perawatan berhasil dihapus.');
    }
}
