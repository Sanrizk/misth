@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Transaction Detail: {{ $transaction->invoice_number ?? 'INV-XX' }}</h2>
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Back to List</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">Info</div>
            <div class="card-body">
                <p><strong>Customer:</strong> {{ $transaction->user->name ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ $transaction->created_at ?? '' }}</p>
                <p><strong>Payment Method:</strong> {{ $transaction->payment_method ?? '' }}</p>
                
                <hr>
                
                <form action="{{ route('transactions.update-status', $transaction->id ?? 0) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label class="form-label"><strong>Update Status</strong></label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ (isset($transaction) && $transaction->status == 'pending') ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ (isset($transaction) && $transaction->status == 'paid') ? 'selected' : '' }}>Paid</option>
                            <option value="shipping" {{ (isset($transaction) && $transaction->status == 'shipping') ? 'selected' : '' }}>Shipping</option>
                            <option value="completed" {{ (isset($transaction) && $transaction->status == 'completed') ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ (isset($transaction) && $transaction->status == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Status</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Items</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaction->transactionDetails ?? [] as $detail)
                            <tr>
                                <td>{{ $detail->product->name ?? 'Unknown Product' }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td class="text-end">${{ number_format($detail->subtotal, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3">No items.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Grand Total</th>
                                <th class="text-end h5 mb-0">${{ number_format($transaction->total_amount ?? 0, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

