@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Transaksi (Transactions)</h2>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Invoice Number</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions ?? [] as $tx)
                    <tr>
                        <td>{{ $tx->invoice_number }}</td>
                        <td>{{ $tx->user->name ?? 'N/A' }}</td>
                        <td>${{ number_format($tx->total_amount, 2) }}</td>
                        <td>
                            @if($tx->status == 'pending') <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($tx->status == 'paid') <span class="badge bg-info text-white">Paid</span>
                            @elseif($tx->status == 'shipping') <span class="badge bg-primary">Shipping</span>
                            @elseif($tx->status == 'completed') <span class="badge bg-success">Completed</span>
                            @else <span class="badge bg-danger">Cancelled</span> @endif
                        </td>
                        <td>{{ $tx->payment_method }}</td>
                        <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('transactions.show', $tx->id) }}" class="btn btn-sm btn-info text-white">View Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">No transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

