<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
                        ->with('transactionDetails.product')
                        ->latest()
                        ->paginate(10);

        return view('store.orders', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);
        $transaction->load('transactionDetails.product');
        
        return view('store.order-show', compact('transaction'));
    }

    public function cancel(Transaction $transaction)
    {
        abort_if($transaction->user_id !== Auth::id(), 403);
        
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan yang belum dibayar yang dapat dibatalkan.');
        }
        
        // Reverse stock
        $transaction->load('transactionDetails.product');
        \Illuminate\Support\Facades\DB::transaction(function () use ($transaction) {
            foreach ($transaction->transactionDetails as $detail) {
                if ($detail->product) {
                    $detail->product->increment('stock', $detail->quantity);
                }
            }
            $transaction->update(['status' => 'cancelled']);
        });
        
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
