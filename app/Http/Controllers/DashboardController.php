<?php

namespace App\Http\Controllers;

use App\Models\Planting;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Harvest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $activePlantings = Planting::where('status', 'in_progress')->count();
        $availableProducts = Product::where('status', 'available')->where('stock', '>', 0)->count();
        $transactionsToday = Transaction::whereDate('created_at', Carbon::today())->count();
        $harvestsThisMonth = Harvest::whereMonth('harvest_date', Carbon::now()->month)->count();
        
        $recentTransactions = Transaction::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.index', compact(
            'activePlantings', 
            'availableProducts', 
            'transactionsToday', 
            'harvestsThisMonth', 
            'recentTransactions'
        ));
    }
}

