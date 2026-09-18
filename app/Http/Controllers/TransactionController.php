<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function index(): JsonResponse
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('transactionDetails.product')
            ->get();
            
        return response()->json($transactions);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $transaction = DB::transaction(function () use ($validated) {
            $userId = Auth::id();
            $timestamp = time();
            $invoiceNumber = "INV-{$timestamp}-{$userId}";

            $transaction = Transaction::create([
                'user_id' => $userId,
                'invoice_number' => $invoiceNumber,
                'total_amount' => 0,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $product->stock -= $item['quantity'];
                
                if ($product->stock === 0) {
                    $product->status = 'out_of_stock';
                }
                
                $product->save();

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ]);
            }

            $transaction->update(['total_amount' => $totalAmount]);

            return $transaction->load('transactionDetails');
        });

        return response()->json($transaction, 201);
    }

    public function updateStatus(Request $request, Transaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipping,completed,cancelled',
        ]);

        $transaction->update(['status' => $validated['status']]);

        return response()->json($transaction);
    }
}

