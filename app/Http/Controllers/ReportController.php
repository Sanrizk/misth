<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planting;
use App\Models\Harvest;
use App\Models\Transaction;
use App\Models\MaterialUsage;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function plantingReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Planting::with('plantType');

        if ($startDate && $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate]);
        }

        $plantings = $query->orderBy('start_date', 'desc')->get();

        $summary = $plantings->groupBy('status')->map(function ($statusGroup) {
            return $statusGroup->groupBy('plantType.name')->map(function ($plantTypeGroup) {
                return $plantTypeGroup->count();
            });
        });

        return view('reports.planting', compact('plantings', 'summary', 'startDate', 'endDate'));
    }

    public function harvestReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Harvest::with('planting.plantType');

        if ($startDate && $endDate) {
            $query->whereBetween('harvest_date', [$startDate, $endDate]);
        }

        $harvests = $query->orderBy('harvest_date', 'desc')->get();

        $summary = $harvests->groupBy(function ($item) {
            return Carbon::parse($item->harvest_date)->format('Y-m');
        })->map(function ($monthGroup) {
            return [
                'total_yield_quantity' => $monthGroup->sum('total_yield_quantity'),
                'total_yield_weight' => $monthGroup->sum('total_yield_weight'),
            ];
        });

        return view('reports.harvest', compact('harvests', 'summary', 'startDate', 'endDate'));
    }

    public function transactionReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Transaction::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $summary = $transactions->groupBy(function ($item) {
            return $item->created_at->format('Y-m');
        })->map(function ($monthGroup) {
            return [
                'total_revenue' => $monthGroup->whereIn('status', ['paid', 'completed', 'shipping'])->sum('total_amount'),
                'total_transactions' => $monthGroup->count(),
            ];
        });

        return view('reports.transaction', compact('transactions', 'summary', 'startDate', 'endDate'));
    }

    public function materialReport(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = MaterialUsage::with(['material', 'maintenanceLog.planting']);

        if ($startDate && $endDate) {
            $query->whereHas('maintenanceLog', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('activity_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            });
        }

        $usages = $query->get()->sortByDesc(function($usage) {
            return $usage->maintenanceLog->activity_date ?? now();
        });

        $summary = $usages->groupBy('material.name')->map(function ($group) {
            return [
                'total_quantity' => $group->sum('quantity_used'),
                'unit' => optional($group->first()->material)->unit ?? '',
            ];
        });

        return view('reports.material', compact('usages', 'summary', 'startDate', 'endDate'));
    }
}
