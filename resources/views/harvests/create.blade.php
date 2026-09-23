@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Data Panen</h2>

    <div class="alert alert-warning">
        Menyimpan data panen akan otomatis membuat produk di toko dan mengubah status penanaman menjadi harvested.
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('harvests.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="planting_id" class="form-label">Penanaman</label>
                    <select class="form-select" id="planting_id" name="planting_id" required>
                        <option value="">-- Pilih Penanaman --</option>
                        @foreach($plantings as $planting)
                            <option value="{{ $planting->id }}" {{ old('planting_id') == $planting->id ? 'selected' : '' }}>
                                {{ $planting->batch_code }} - {{ optional($planting->plantType)->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="harvest_date" class="form-label">Tanggal Panen</label>
                    <input type="date" class="form-control" id="harvest_date" name="harvest_date" value="{{ old('harvest_date', date('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="total_yield_quantity" class="form-label">Jumlah Panen</label>
                    <input type="number" class="form-control" id="total_yield_quantity" name="total_yield_quantity" value="{{ old('total_yield_quantity') }}" min="1" required>
                </div>

                <div class="mb-3">
                    <label for="total_yield_weight" class="form-label">Berat Total (kg)</label>
                    <input type="number" step="0.01" class="form-control" id="total_yield_weight" name="total_yield_weight" value="{{ old('total_yield_weight') }}" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="quality_grade" class="form-label">Grade</label>
                    <select class="form-select" id="quality_grade" name="quality_grade" required>
                        <option value="">-- Pilih Grade --</option>
                        <option value="Grade A" {{ old('quality_grade') == 'Grade A' ? 'selected' : '' }}>Grade A</option>
                        <option value="Grade B" {{ old('quality_grade') == 'Grade B' ? 'selected' : '' }}>Grade B</option>
                        <option value="Grade C" {{ old('quality_grade') == 'Grade C' ? 'selected' : '' }}>Grade C</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga Jual per Unit</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" min="0" required>
                    <div class="form-text">Harga ini digunakan untuk produk yang dibuat secara otomatis.</div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('harvests.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
