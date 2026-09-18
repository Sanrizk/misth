<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class MaintenanceLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate(['planting_id' => 'required|exists:plantings,id']);
        $logs = MaintenanceLog::where('planting_id', $request->planting_id)->get();
        return response()->json($logs);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'planting_id' => 'required|exists:plantings,id',
            'action_type' => 'required|string|max:100',
            'nutrients_ppm' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $log = MaintenanceLog::create([
            'planting_id' => $validated['planting_id'],
            'user_id' => Auth::id(),
            'activity_date' => now(),
            'action_type' => $validated['action_type'],
            'nutrients_ppm' => $validated['nutrients_ppm'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json($log, 201);
    }
}

