@extends('layouts.store')

@section('title', 'Checkout - Toko Misth')

@section('content')
<h2 class="fw-bold mb-4">Checkout</h2>

<form action="{{ route('store.checkout.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                @foreach($cart->cartItems as $item)
                                    <tr class="border-bottom">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                @if(optional($item->product)->image_url)
                                                    <img src="{{ optional($item->product)->image_url }}" alt="{{ optional($item->product)->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="rounded bg-light text-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image opacity-50"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ optional($item->product)->name }}</h6>
                                                    <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format(optional($item->product)->price, 0, ',', '.') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Metode Pembayaran</h5>
                    
                    <div class="row g-3">
                        @foreach($paymentMethods as $index => $method)
                            <div class="col-sm-6">
                                <div class="form-check border rounded-3 p-3 position-relative" style="cursor: pointer;" onclick="document.getElementById('method{{ $index }}').click()">
                                    <input class="form-check-input ms-0 me-2" type="radio" name="payment_method" id="method{{ $index }}" value="{{ $method }}" required {{ old('payment_method') == $method ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium stretched-link" for="method{{ $index }}">
                                        {{ $method }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('payment_method')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Total Pembayaran</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal ({{ $cart->cartItems->count() }} Produk)</span>
                        <span class="fw-medium">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Biaya Pengiriman</span>
                        <span class="fw-medium">Gratis</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-5">Total Bayar</span>
                        <span class="fw-bold fs-3 text-success">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-3">
                        <i class="bi bi-shield-lock me-1"></i> Bayar Sekarang
                    </button>
                    
                    <p class="text-center text-muted small mt-3 mb-0">
                        Dengan menekan tombol bayar, Anda menyetujui syarat & ketentuan yang berlaku.
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
