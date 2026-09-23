@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Detail Transaksi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <strong>Informasi Invoice: {{ $transaction->invoice_number }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Pelanggan:</strong> {{ optional($transaction->user)->name }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ $transaction->payment_method }}</p>
            <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
            <p><strong>Status:</strong> 
                @php
                    $badgeClass = 'bg-secondary';
                    if($transaction->status == 'pending') $badgeClass = 'bg-warning text-dark';
                    elseif($transaction->status == 'paid') $badgeClass = 'bg-primary';
                    elseif($transaction->status == 'shipping') $badgeClass = 'bg-info';
                    elseif($transaction->status == 'completed') $badgeClass = 'bg-success';
                    elseif($transaction->status == 'cancelled') $badgeClass = 'bg-danger';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ ucfirst($transaction->status) }}</span>
            </p>

            <form action="{{ route('transactions.updateStatus', $transaction->id) }}" method="POST" class="d-flex align-items-center mt-3">
                @csrf
                @method('PATCH')
                <label for="status" class="me-2"><strong>Ubah Status:</strong></label>
                <select name="status" id="status" class="form-select w-auto me-2">
                    <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $transaction->status == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="shipping" {{ $transaction->status == 'shipping' ? 'selected' : '' }}>Shipping</option>
                    <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
            </form>
        </div>
    </div>

    <h4 class="mb-3">Item Transaksi</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Kuantitas</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaction->transactionDetails as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($detail->product)->name }}</td>
                        <td>Rp {{ number_format(optional($detail->product)->price, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada item dalam transaksi ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Grand Total</th>
                    <th>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
