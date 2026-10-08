<?php

namespace App\Http\Controllers;

use App\Models\Planting;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Harvest;
use App\Models\Purchase;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role->name === 'customer') {
            return redirect()->route('store.index');
        }

        $activePlantings = Planting::where('status', 'in_progress')->count();
        $availableProducts = Product::where('status', 'available')->where('stock', '>', 0)->count();
        $transactionsToday = Transaction::whereDate('created_at', Carbon::today())->count();
        $harvestsThisMonth = Harvest::whereMonth('harvest_date', Carbon::now()->month)->count();
        
        $recentTransactions = Transaction::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        // 1. Line Chart: Harvests (Last 6 Months)
        $harvestData = Harvest::selectRaw('SUM(total_yield_weight) as total, MONTH(harvest_date) as month')
            ->where('harvest_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $harvestChart = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $harvestChart['labels'][] = $m->translatedFormat('M Y');
            $harvestChart['data'][] = $harvestData[$m->month] ?? 0;
        }

        // 2. Pie Chart: Top Products Sold
        $topProducts = TransactionDetail::selectRaw('product_id, SUM(quantity) as total_qty')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $productChart = ['labels' => [], 'data' => []];
        foreach ($topProducts as $item) {
            $productChart['labels'][] = $item->product ? $item->product->name : 'Unknown';
            $productChart['data'][] = (float) $item->total_qty;
        }

        // 3. Bar Chart: Sales vs Purchases (Last 6 Months)
        $salesData = Transaction::selectRaw('SUM(total_amount) as total, MONTH(created_at) as month')
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $purchasesData = Purchase::selectRaw('SUM(total_amount) as total, MONTH(purchase_date) as month')
            ->where('purchase_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->pluck('total', 'month')->toArray();

        $financeChart = ['labels' => [], 'sales' => [], 'purchases' => []];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $financeChart['labels'][] = $m->translatedFormat('M Y');
            $financeChart['sales'][] = $salesData[$m->month] ?? 0;
            $financeChart['purchases'][] = $purchasesData[$m->month] ?? 0;
        }

        return view('dashboard.index', compact(
            'activePlantings', 
            'availableProducts', 
            'transactionsToday', 
            'harvestsThisMonth', 
            'recentTransactions',
            'harvestChart',
            'productChart',
            'financeChart'
        ));
    }
}