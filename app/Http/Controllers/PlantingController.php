<?php

namespace App\Http\Controllers;

use App\Models\Planting;
use App\Models\PlantType;
use App\Models\User;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlantingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Planting::with([
            'plantType',
            'user',
            'maintenanceLogs.user',
            'maintenanceLogs.materialUsages.material',
            'waterQualityLogs',
            'harvest',
            'harvests',
        ])->latest();

        if ($request->filled('search')) {
            $query->where('batch_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('plantType', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $plantings = $query->paginate(12);

        if ($request->wantsJson()) {
            return response()->json($plantings);
        }

        $materials = Material::where('status', 'active')->get();
        $plantTypes = PlantType::all();
        $users = User::whereHas('role', fn($q) => $q->where('name', 'petani'))->get();

        return view('plantings.index', compact('plantings', 'materials', 'plantTypes', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('plantings.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plant_type_id' => 'required|exists:plant_types,id',
            'user_id'       => 'required|exists:users,id',
            'quantity_seeds'=> 'required|integer|min:1',
            'start_date'    => 'required|date',
        ]);

        // Generate unique batch code: BATCH-{UNIQUE_4_ALPHANUM}-{YEAR}-{INCREMENT_OR_NUMBER} (contoh: BATCH-TW19-2026-57)
        $year = now()->year;
        do {
            $uniqueStr = Str::upper(Str::random(2)) . rand(10, 99); // 2 huruf + 2 angka (misal: TW19)
            $suffix = str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
            $batchCode = "BATCH-{$uniqueStr}-{$year}-{$suffix}";
        } while (Planting::where('batch_code', $batchCode)->exists());

        Planting::create(array_merge($validated, [
            'batch_code' => $batchCode,
            'status' => 'in_progress',
        ]));

        return redirect()->route('plantings.index')->with('success', 'Penanaman berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $planting = Planting::with([
            'plantType',
            'user',
            'maintenanceLogs',
            'waterQualityLogs',
        ])->findOrFail($id);
        return view('plantings.show', compact('planting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $planting = Planting::findOrFail($id);
        $plantTypes = PlantType::all();
        $users = User::whereHas('role', fn($q) => $q->where('name', 'petani'))->get();
        return view('plantings.edit', compact('planting', 'plantTypes', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'plant_type_id' => 'required|exists:plant_types,id',
            'user_id'       => 'required|exists:users,id',
            'quantity_seeds'=> 'required|integer|min:1',
            'start_date'    => 'required|date',
            'status'        => 'required|in:in_progress,harvested,failed',
        ]);

        $planting = Planting::findOrFail($id);
        $planting->update($validated);

        return redirect()->route('plantings.index')->with('success', 'Penanaman berhasil diperbarui');
    }

    /**
     * Update only the status of a planting.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:in_progress,harvested,failed',
        ]);
        $planting = Planting::findOrFail($id);
        $planting->update(['status' => $validated['status']]);
        return redirect()->route('plantings.show', $planting->id)->with('success', 'Status penanaman diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $planting = Planting::findOrFail($id);
        $planting->delete();
        return redirect()->route('plantings.index')->with('success', 'Penanaman berhasil dihapus');
    }
}
