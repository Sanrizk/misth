@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Active Plantings</h5>
                <h3>{{ $activePlantings ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Products Available</h5>
                <h3>{{ $availableProducts ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Transactions Today</h5>
                <h3>{{ $transactionsToday ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Harvests This Month</h5>
                <h3>{{ $harvestsThisMonth ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Recent Transactions</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions ?? [] as $tx)
                    <tr>
                        <td>{{ $tx->invoice_number }}</td>
                        <td>${{ number_format($tx->total_amount, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ $tx->status }}</span></td>
                        <td>{{ $tx->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4">No recent transactions.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

