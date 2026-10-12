<?php

namespace App\Http\Controllers;

use App\Models\PlantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PlantTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PlantType::query();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }
        
        $plantTypes = $query->orderBy('created_at', 'desc')->paginate(10);
        
        if ($request->wantsJson()) {
            return response()->json($plantTypes);
        }
        
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['description'] = $validated['description'] ?? '';

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;
            $path = $request->file('image')->storeAs('plant-types', $randomName, 'public');
            $validated['image'] = $path;
        }

        PlantType::create($validated);

        return redirect()->route('plant-types.index')
            ->with('success', 'Jenis tanaman berhasil ditambahkan.');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['description'] = $validated['description'] ?? '';

        if ($request->hasFile('image')) {
            if ($plantType->image && Storage::disk('public')->exists($plantType->image)) {
                Storage::disk('public')->delete($plantType->image);
            }
            $extension = $request->file('image')->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;
            $path = $request->file('image')->storeAs('plant-types', $randomName, 'public');
            $validated['image'] = $path;
        }

        $plantType->update($validated);

        return redirect()->route('plant-types.index')
            ->with('success', 'Jenis tanaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plantType = PlantType::findOrFail($id);

        if ($plantType->image && Storage::disk('public')->exists($plantType->image)) {
            Storage::disk('public')->delete($plantType->image);
        }

        $plantType->delete();

        return redirect()->route('plant-types.index')
            ->with('success', 'Jenis tanaman berhasil dihapus.');
    }
}
