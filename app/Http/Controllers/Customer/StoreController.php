<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PlantType;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('harvest.planting.plantType')
                    ->where('status', 'available')
                    ->where('stock', '>', 0);

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->whereHas('harvest.planting.plantType', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        $products = $query->latest()->paginate(12);
        $plantTypes = PlantType::all();

        // 1. Popular products
        $popularProducts = Product::with('harvest.planting.plantType')
            ->withCount('transactionDetails')
            ->where('status', 'available')
            ->where('stock', '>', 0)
            ->orderByDesc('transaction_details_count')
            ->take(12)
            ->get();

        // 2. Upcoming harvests (Plantings >= 70% progress)
        $upcomingPlantings = \App\Models\Planting::with('plantType')
            ->where('status', 'in_progress')
            ->get()
            ->filter(function ($planting) {
                if (!$planting->plantType || !$planting->plantType->estimated_harvest_days) return false;
                $estDays = $planting->plantType->estimated_harvest_days;
                $start = $planting->start_date->timestamp;
                $now = now()->timestamp;
                $daysPassed = ($now - $start) / (60 * 60 * 24);
                $progress = round(($daysPassed / $estDays) * 100);
                if ($progress >= 70 && $progress < 100) {
                    $planting->progress_percentage = $progress;
                    return true;
                }
                return false;
            })->take(12);

        return view('store.index', compact('products', 'plantTypes', 'popularProducts', 'upcomingPlantings'));
    }

    public function show($id)
    {
        $product = Product::with('harvest.planting.plantType')
                    ->where('status', 'available')
                    ->findOrFail($id);

        return view('store.product-show', compact('product'));
    }
}
