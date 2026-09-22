<?php

namespace App\Http\Controllers;

use App\Models\Planting;
use App\Models\PlantType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlantingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plantings = Planting::with(['plantType', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('plantings.index', compact('plantings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plantTypes = PlantType::all();
        $users = User::whereHas('role', fn($q) => $q->where('name', 'petani'))->get();
        return view('plantings.create', compact('plantTypes', 'users'));
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

        // Generate unique batch code: BATCH-{PLANTTYPE}-{YEAR}-{INCREMENT}
        $plantType = PlantType::findOrFail($validated['plant_type_id']);
        $year = now()->year;
        $base = 'BATCH-' . Str::upper(Str::slug($plantType->name, '_')) . "-{$year}";
        $latest = Planting::where('batch_code', 'like', "$base-%")->latest('id')->first();
        $increment = $latest ? ((int) Str::afterLast($latest->batch_code, '-') + 1) : 1;
        $batchCode = $base . '-' . str_pad($increment, 2, '0', STR_PAD_LEFT);

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
