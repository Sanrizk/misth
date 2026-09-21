<?php

namespace App\Http\Controllers;

use App\Models\PlantType;
use Illuminate\Http\Request;

class PlantTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plantTypes = PlantType::orderBy('created_at', 'desc')->paginate(10);
        return view('plant-types.index', compact('plantTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('plant-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:plant_types,name',
            'estimated_harvest_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        PlantType::create($validated);

        return redirect()->route('plant-types.index')
            ->with('success', 'Plant type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $plantType = PlantType::findOrFail($id);
        return view('plant-types.edit', compact('plantType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $plantType = PlantType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:plant_types,name,' . $plantType->id,
            'estimated_harvest_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $plantType->update($validated);

        return redirect()->route('plant-types.index')
            ->with('success', 'Plant type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plantType = PlantType::findOrFail($id);
        $plantType->delete();

        return redirect()->route('plant-types.index')
            ->with('success', 'Plant type deleted successfully.');
    }
}
