<?php

namespace App\Http\Controllers;

use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CashFlowController extends Controller
{
    /**
     * Tampilkan Buku Kas (Arus Kas) dengan Filter Rentang Tanggal
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $type = $request->input('type'); // in, out, atau kosong
        $category = $request->input('category');
        $search = $request->input('search');

        $query = CashFlow::with('user')->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($type && in_array($type, ['in', 'out'])) {
            $query->where('type', $type);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('cash_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Hitung total ringkasan untuk periode yang difilter
        $totalIn = CashFlow::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'in')
            ->sum('amount');

        $totalOut = CashFlow::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('type', 'out')
            ->sum('amount');

        $netBalance = $totalIn - $totalOut;

        // Saldo kumulatif keseluruhan (dari awal sampai akhir periode filter)
        $cumulativeBalance = CashFlow::where('transaction_date', '<=', $endDate)
            ->selectRaw('SUM(CASE WHEN type = "in" THEN amount ELSE -amount END) as total')
            ->value('total') ?? 0;

        $cashFlows = $query->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->paginate(15);

        // Kategori yang sering digunakan
        $categories = CashFlow::select('category')->distinct()->pluck('category')->filter()->values();

        return view('finance.index', compact(
            'cashFlows',
            'startDate',
            'endDate',
            'type',
            'category',
            'search',
            'totalIn',
            'totalOut',
            'netBalance',
            'cumulativeBalance',
            'categories'
        ));
    }

    /**
     * Simpan Kas Masuk / Keluar Manual
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:in,out',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $cashNumber = 'KAS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        CashFlow::create([
            'cash_number' => $cashNumber,
            'transaction_date' => $validated['transaction_date'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'user_id' => Auth::id(),
            'reference_type' => 'manual',
            'reference_id' => null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('finance.index')->with('success', 'Transaksi kas berhasil dicatat.');
    }

    /**
     * Update Kas Manual
     */
    public function update(Request $request, CashFlow $cashFlow)
    {
        if ($cashFlow->reference_type !== 'manual') {
            return redirect()->route('finance.index')->with('error', 'Transaksi otomatis dari sistem (pesanan/pembelian) tidak dapat diubah secara manual di sini.');
        }

        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:in,out',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $cashFlow->update([
            'transaction_date' => $validated['transaction_date'],
            'type' => $validated['type'],
            'category' => $validated['category'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('finance.index')->with('success', 'Transaksi kas berhasil diperbarui.');
    }

    /**
     * Hapus Kas Manual
     */
    public function destroy(CashFlow $cashFlow)
    {
        if ($cashFlow->reference_type !== 'manual') {
            return redirect()->route('finance.index')->with('error', 'Transaksi otomatis dari sistem tidak dapat dihapus secara langsung dari buku kas.');
        }

        $cashFlow->delete();

        return redirect()->route('finance.index')->with('success', 'Transaksi kas berhasil dihapus.');
    }
}

