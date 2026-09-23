@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Produk</h1>

    <div class="card mt-4">
        <div class="card-body">
            @if(optional($product)->image_url)
                <div class="mb-4 text-center">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 400px;">
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 250px;">Nama Produk</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>
                            <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge {{ $product->status == 'available' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $product->status == 'available' ? 'Tersedia' : 'Habis' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ optional($product)->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Asal Panen</th>
                        <td>
                            Kode Batch: {{ optional($product->harvest)->batch_code ?? '-' }} <br>
                            Jenis Tanaman: {{ optional(optional(optional($product->harvest)->planting)->plantType)->name ?? '-' }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
