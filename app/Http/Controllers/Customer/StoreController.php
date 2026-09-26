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
        $cartCount = collect(session('cart', []))->sum('quantity');

        return view('store.index', compact('products', 'plantTypes', 'cartCount'));
    }

    public function show($id)
    {
        $product = Product::with('harvest.planting.plantType')
                    ->where('status', 'available')
                    ->findOrFail($id);

        $cartCount = collect(session('cart', []))->sum('quantity');

        return view('store.product-show', compact('product', 'cartCount'));
    }
}
