<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'available')
                        ->where('stock', '>', 0)
                        ->with('harvest.planting.plantType')
                        ->latest()
                        ->take(6)
                        ->get();

        return view('landing', compact('products'));
    }
}
