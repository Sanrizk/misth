@extends('layouts.store')

@section('title', 'Keranjang Belanja - Toko Misth')

@section('content')
<h2 class="fw-bold mb-4">Keranjang Belanja</h2>

@if($cart && $cart->cartItems->count() > 0)
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th>Harga Satuan</th>
                                <th style="width: 150px;">Kuantitas</th>
                                <th>Subtotal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->cartItems as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(optional($item->product)->image_url)
                                                <img src="{{ optional($item->product)->image_url }}" alt="{{ optional($item->product)->name }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light text-success d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-image fs-4 opacity-50"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ optional($item->product)->name }}</h6>
                                                <small class="text-muted">Stok sisa: {{ optional($item->product)->stock }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format(optional($item->product)->price, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('store.cart.update') }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ optional($item->product)->stock }}" class="form-control form-control-sm text-center" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('store.cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white p-3 d-flex justify-content-between align-items-center border-top">
                <a href="{{ route('store.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
                </a>
                <form action="{{ route('store.cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-link text-danger text-decoration-none p-0" onclick="return confirm('Kosongkan keranjang?')">
                        <i class="bi bi-x-circle me-1"></i> Kosongkan Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Total Item</span>
                    <span>{{ $cart->cartItems->sum('quantity') }}</span>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-5">Total Harga</span>
                    <span class="fw-bold fs-4 text-success">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <a href="{{ route('store.checkout.index') }}" class="btn btn-success btn-lg w-100 fw-bold rounded-3">
                    Lanjut Checkout <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-center py-5">
    <div class="mb-4">
        <i class="bi bi-cart-x text-muted" style="font-size: 6rem;"></i>
    </div>
    <h3 class="fw-bold text-dark">Keranjang Belanja Kosong</h3>
    <p class="text-muted mb-4">Anda belum menambahkan produk apapun ke keranjang.</p>
    <a href="{{ route('store.index') }}" class="btn btn-success btn-lg px-4 rounded-pill fw-medium">
        Mulai Belanja <i class="bi bi-arrow-right ms-2"></i>
    </a>
</div>
@endif
@endsection
