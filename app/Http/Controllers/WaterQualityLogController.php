<?php

namespace App\Http\Controllers;

use App\Models\WaterQualityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WaterQualityLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate(['planting_id' => 'required|exists:plantings,id']);
        $logs = WaterQualityLog::where('planting_id', $request->planting_id)->get();
        return response()->json($logs);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'planting_id' => 'required|exists:plantings,id',
            'ph_level' => 'required|numeric',
            'tds_ppm' => 'required|integer',
            'water_temp' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $log = WaterQualityLog::create([
            'planting_id' => $validated['planting_id'],
            'checked_at' => now(),
            'ph_level' => $validated['ph_level'],
            'tds_ppm' => $validated['tds_ppm'],
            'water_temp' => $validated['water_temp'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json($log, 201);
    }
}

