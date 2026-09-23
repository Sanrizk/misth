<?php

namespace App\Http\Controllers;

use App\Models\WaterQualityLog;
use App\Models\Planting;
use Illuminate\Http\Request;

class WaterQualityLogController extends Controller
{
    public function index()
    {
        $waterQualityLogs = WaterQualityLog::with('planting.plantType')->paginate(10);
        return view('water-quality-logs.index', compact('waterQualityLogs'));
    }

    public function create()
    {
        $plantings = Planting::with('plantType')->where('status', 'in_progress')->get();
        return view('water-quality-logs.create', compact('plantings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'planting_id' => 'required|exists:plantings,id',
            'checked_at' => 'required|date',
            'ph_level' => 'required|numeric|between:0,14',
            'tds_ppm' => 'required|integer|min:0',
            'water_temp' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        WaterQualityLog::create($validated);

        return redirect()->route('water-quality-logs.index')->with('success', 'Catatan kualitas air berhasil ditambahkan.');
    }

    public function show($id)
    {
        $waterQualityLog = WaterQualityLog::with('planting.plantType')->findOrFail($id);
        return view('water-quality-logs.show', compact('waterQualityLog'));
    }

    public function destroy($id)
    {
        $waterQualityLog = WaterQualityLog::findOrFail($id);
        $waterQualityLog->delete();

        return redirect()->route('water-quality-logs.index')->with('success', 'Catatan kualitas air berhasil dihapus.');
    }
}
