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
}
