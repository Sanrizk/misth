<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        return view('scanner.index');
    }

    // AJAX endpoint — cari transaksi by invoice number
    public function find(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string',
        ]);

        $transaction = Transaction::with(['user', 'transactionDetails.product'])
                        ->where('invoice_number', $request->invoice_number)
                        ->first();

        if (!$transaction) {
            return response()->json([
                'found' => false,
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'found' => true,
            'transaction' => [
                'id'             => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'status'         => $transaction->status,
                'total_amount'   => $transaction->total_amount,
                'total_formatted'=> 'Rp ' . number_format($transaction->total_amount, 0, ',', '.'),
                'payment_method' => $transaction->payment_method,
                'created_at'     => $transaction->created_at->format('d M Y H:i'),
                'customer'       => [
                    'name'  => optional($transaction->user)->name,
                    'phone' => optional($transaction->user)->phone,
                ],
                'items' => $transaction->transactionDetails->map(fn($d) => [
                    'name'      => optional($d->product)->name ?? '-',
                    'quantity'  => $d->quantity,
                    'subtotal'  => 'Rp ' . number_format($d->subtotal, 0, ',', '.'),
                ]),
            ]
        ]);
    }

    // AJAX endpoint — update status transaksi
    public function confirm(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:paid,cancelled',
        ]);

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah diproses sebelumnya.'
            ], 422);
        }

        if ($request->status === 'cancelled') {
            \DB::transaction(function () use ($transaction, $request) {
                foreach ($transaction->transactionDetails as $detail) {
                    $product = $detail->product;
                    if ($product) {
                        $product->increment('stock', $detail->quantity);
                        if ($product->status === 'out_of_stock') {
                            $product->update(['status' => 'available']);
                        }
                    }
                }
                $transaction->update(['status' => $request->status]);
            });
        } else {
            $transaction->update(['status' => $request->status]);
        }

        return response()->json([
            'success' => true,
            'status'  => $request->status,
            'message' => $request->status === 'paid'
                ? 'Pembayaran berhasil dikonfirmasi!'
                : 'Pesanan berhasil dibatalkan.',
        ]);
    }
}
