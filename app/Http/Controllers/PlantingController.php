<?php

namespace App\Http\Controllers;

use App\Models\Planting;
use App\Models\PlantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class PlantingController extends Controller
{
    public function index(): JsonResponse
    {
        $plantings = Planting::where('status', 'in_progress')->get();
        return response()->json($plantings);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plant_type_id' => 'required|exists:plant_types,id',
            'quantity_seeds' => 'required|integer|min:1',
            'start_date' => 'required|date',
        ]);

        $plantType = PlantType::findOrFail($validated['plant_type_id']);
        $year = date('Y', strtotime($validated['start_date']));
        $increment = str_pad(Planting::whereYear('start_date', $year)->count() + 1, 4, '0', STR_PAD_LEFT);
        
        $batchCode = "BATCH-{$plantType->id}-{$year}-{$increment}";

        $planting = Planting::create([
            'plant_type_id' => $validated['plant_type_id'],
            'user_id' => Auth::id(),
            'batch_code' => $batchCode,
            'quantity_seeds' => $validated['quantity_seeds'],
            'start_date' => $validated['start_date'],
            'status' => 'in_progress',
        ]);

        return response()->json($planting, 201);
    }

    public function update(Request $request, Planting $planting): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:in_progress,harvested,failed',
        ]);

        $planting->update([
            'status' => $validated['status']
        ]);

        return response()->json($planting);
    }
}

