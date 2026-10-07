@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Laporan Sistem</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <a href="{{ route('reports.plantings') }}" class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition border border-transparent hover:border-green-200 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-green-700 transition">Laporan Penanaman</h3>
                    <p class="text-sm text-gray-500">Ringkasan status siklus tanam berdasarkan jenis tanaman.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.harvests') }}" class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition border border-transparent hover:border-green-200 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-green-700 transition">Laporan Panen</h3>
                    <p class="text-sm text-gray-500">Ringkasan hasil panen dan total berat per bulan.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.transactions') }}" class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition border border-transparent hover:border-green-200 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-green-700 transition">Laporan Penjualan</h3>
                    <p class="text-sm text-gray-500">Ringkasan total pendapatan dan transaksi per bulan.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.materials') }}" class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition border border-transparent hover:border-green-200 group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-green-700 transition">Laporan Penggunaan Bahan</h3>
                    <p class="text-sm text-gray-500">Ringkasan total bahan yang digunakan pada log perawatan.</p>
                </div>
            </div>
        </a>

    </div>
</div>
@endsection

