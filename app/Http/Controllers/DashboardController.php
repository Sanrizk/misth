<?php

namespace App\Http\Controllers;

use App\Models\Planting;
use App\Models\Harvest;
use App\Models\Purchase;
use App\Models\Material;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $activePlantings = Planting::where('status', 'in_progress')->count();
        $harvestedPlantings = Planting::where('status', 'harvested')->count();
        $totalMaterials = Material::count();
        $harvestsThisMonth = Harvest::whereMonth('harvest_date', Carbon::now()->month)->count();

        $isSqlite = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite';
        $monthFunc = $isSqlite ? "strftime('%m', harvest_date)" : "MONTH(harvest_date)";
        
        $harvestData = Harvest::selectRaw("SUM(total_yield_weight) as total, {$monthFunc} as month")
            ->where('harvest_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $harvestChart = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $harvestChart['labels'][] = $m->translatedFormat('M Y');
            $harvestChart['data'][] = $harvestData[(int) $m->format('m')] ?? 0;
        }

        // Purchases Trend (Last 6 Months)
        $monthFuncPurchase = $isSqlite ? "strftime('%m', purchase_date)" : "MONTH(purchase_date)";
        $purchasesData = Purchase::selectRaw("SUM(total_amount) as total, {$monthFuncPurchase} as month")
            ->where('purchase_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $financeChart = ['labels' => [], 'purchases' => []];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $financeChart['labels'][] = $m->translatedFormat('M Y');
            $financeChart['purchases'][] = $purchasesData[(int) $m->format('m')] ?? 0;
        }

        // Recent Purchases
        $recentPurchases = Purchase::with(['supplier', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'activePlantings', 
            'harvestedPlantings', 
            'totalMaterials', 
            'harvestsThisMonth', 
            'recentPurchases',
            'harvestChart',
            'financeChart'
        ));
    }
}
