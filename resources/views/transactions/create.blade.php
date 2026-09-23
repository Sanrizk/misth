@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Checkout Transaksi</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-3">Pilih Produk</h4>
                <div class="row">
                    @forelse($products as $index => $product)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text mb-1">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="card-text mb-3">Stok: {{ $product->stock }}</p>
                                    
                                    <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                                    <div class="input-group">
                                        <span class="input-group-text">Qty</span>
                                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control" value="{{ old('items.'.$index.'.quantity', 0) }}" min="0" max="{{ $product->stock }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning">Tidak ada produk yang tersedia saat ini.</div>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Ringkasan Pesanan</h4>
                        
                        <div class="mb-4">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="Transfer Bank" {{ old('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="COD" {{ old('payment_method') == 'COD' ? 'selected' : '' }}>COD (Cash on Delivery)</option>
                                <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                <option value="Dompet Digital" {{ old('payment_method') == 'Dompet Digital' ? 'selected' : '' }}>Dompet Digital</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Proses Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
