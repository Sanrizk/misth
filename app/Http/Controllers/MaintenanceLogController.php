<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Planting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenanceLogs = MaintenanceLog::with(['planting.plantType', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('maintenance-logs.index', compact('maintenanceLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only plantings that are still in progress
        $plantings = Planting::with('plantType')
            ->where('status', 'in_progress')
            ->orderBy('batch_code')
            ->get();

        return view('maintenance-logs.create', compact('plantings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penanaman_id' => 'required|exists:plantings,id',
            'activity_date' => 'required|date',
            'action_type' => 'required|string|max:100',
            'nutrients_ppm' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        MaintenanceLog::create($validated);

        return redirect()
            ->route('maintenance-logs.index')
            ->with('success', 'Log perawatan berhasil ditambahkan');
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

        return redirect()
            ->route('maintenance-logs.index')
            ->with('success', 'Log perawatan berhasil dihapus');
    }
}
