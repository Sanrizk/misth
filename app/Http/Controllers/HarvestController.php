<?php

namespace App\Http\Controllers;

use App\Models\Harvest;
use App\Models\Planting;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class HarvestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'planting_id' => 'required|exists:plantings,id',
            'harvest_date' => 'required|date',
            'total_yield_quantity' => 'required|integer|min:1',
            'total_yield_weight' => 'required|numeric|min:0',
            'quality_grade' => 'required|in:Grade A,Grade B,Grade C',
            'notes' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $result = DB::transaction(function () use ($validated) {
            $planting = Planting::with('plantType')->findOrFail($validated['planting_id']);

            $harvest = Harvest::create([
                'planting_id' => $planting->id,
                'harvest_date' => $validated['harvest_date'],
                'total_yield_quantity' => $validated['total_yield_quantity'],
                'total_yield_weight' => $validated['total_yield_weight'],
                'quality_grade' => $validated['quality_grade'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $planting->update(['status' => 'harvested']);

            $productName = "{$planting->plantType->name} Segar Hidroponik";

            $product = Product::create([
                'harvest_id' => $harvest->id,
                'name' => $productName,
                'stock' => $validated['total_yield_quantity'],
                'price' => $validated['price'],
                'status' => 'available',
            ]);

            return [
                'harvest' => $harvest,
                'product' => $product
            ];
        });

        return response()->json($result, 201);
    }
}

