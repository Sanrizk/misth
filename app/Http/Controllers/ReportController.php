<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planting;
use App\Models\Harvest;
use App\Models\Transaction;
use App\Models\MaterialUsage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function plantingReport(Request $request)
    {
        if ($request->get('export') === 'pdf' || $request->has('pdf')) {
            return $this->plantingPdf($request);
        }

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

    public function plantingPdf(Request $request)
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

        $pdf = Pdf::loadView('reports.pdf.planting', compact('plantings', 'summary', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-penanaman-' . now()->format('YmdHis') . '.pdf';

        if ($request->get('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    public function harvestReport(Request $request)
    {
        if ($request->get('export') === 'pdf' || $request->has('pdf')) {
            return $this->harvestPdf($request);
        }

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

    public function harvestPdf(Request $request)
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

        $pdf = Pdf::loadView('reports.pdf.harvest', compact('harvests', 'summary', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-panen-' . now()->format('YmdHis') . '.pdf';

        if ($request->get('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    public function transactionReport(Request $request)
    {
        if ($request->get('export') === 'pdf' || $request->has('pdf')) {
            return $this->transactionPdf($request);
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Transaction::with('user');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $summary = $transactions->groupBy(function ($item) {
            return $item->created_at->format('Y-m');
        })->map(function ($monthGroup) {
            return [
                'total_revenue' => $monthGroup->whereIn('status', ['paid', 'completed', 'shipping', 'terbayarkan'])->sum('total_amount'),
                'total_transactions' => $monthGroup->count(),
            ];
        });

        return view('reports.transaction', compact('transactions', 'summary', 'startDate', 'endDate'));
    }

    public function transactionPdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Transaction::with('user');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $summary = $transactions->groupBy(function ($item) {
            return $item->created_at->format('Y-m');
        })->map(function ($monthGroup) {
            return [
                'total_revenue' => $monthGroup->whereIn('status', ['paid', 'completed', 'shipping', 'terbayarkan'])->sum('total_amount'),
                'total_transactions' => $monthGroup->count(),
            ];
        });

        $pdf = Pdf::loadView('reports.pdf.transaction', compact('transactions', 'summary', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-penjualan-' . now()->format('YmdHis') . '.pdf';

        if ($request->get('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    public function materialReport(Request $request)
    {
        if ($request->get('export') === 'pdf' || $request->has('pdf')) {
            return $this->materialPdf($request);
        }

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

    public function materialPdf(Request $request)
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

        $pdf = Pdf::loadView('reports.pdf.material', compact('usages', 'summary', 'startDate', 'endDate'))
            ->setPaper('a4', 'portrait');

        $filename = 'laporan-penggunaan-bahan-' . now()->format('YmdHis') . '.pdf';

        if ($request->get('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}
