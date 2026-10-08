@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')

<div x-data="scannerApp()" x-init="init()">

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Transaksi</h2>
</div>

{{-- Search Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-4 mb-4">

    <div class="flex gap-3">

        {{-- Invoice Search --}}
        <div class="flex-1 relative">
            <input type="text"
                   x-model.debounce.300ms="search"
                   x-ref="searchInput"
                   placeholder="Cari nomor invoice atau scan barcode..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Filter Status --}}
        <select x-model="statusFilter"
                class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="terbayarkan">Terbayarkan</option>
            <option value="batal">Batal</option>
        </select>

        {{-- Barcode Scanner Button --}}
        <button type="button" @click="startScanner()"
                class="bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
            Scan
        </button>

    </div>

    {{-- Barcode Scanner interface will appear below table --}}

</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Invoice</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Customer</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Total</th>
                                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Status</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Tanggal</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 relative">
                <template x-for="trx in transactions" :key="trx.id">
                    <tr class="hover:bg-gray-50 transition-colors" :class="trx.status === 'pending' ? 'bg-yellow-50/30' : ''">
                        <td class="py-3 px-4 font-mono text-xs text-gray-600" x-text="trx.invoice_number"></td>
                        <td class="py-3 px-4 text-gray-700" x-text="trx.user ? trx.user.name : '-'"></td>
                        <td class="py-3 px-4 font-semibold text-gray-800" x-text="'Rp ' + parseInt(trx.total_amount).toLocaleString('id-ID')"></td>
                                                <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                                :class="{
                                    'bg-yellow-100 text-yellow-700': trx.status === 'pending',
                                    'bg-green-100 text-green-700': trx.status === 'terbayarkan',
                                    'bg-red-100 text-red-700': trx.status === 'batal'
                                }"
                                x-text="trx.status.charAt(0).toUpperCase() + trx.status.slice(1)">
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-400 text-xs" x-text="new Date(trx.created_at).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'})"></td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                @if(Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'petani')
                                    <button @click="openStatusModal(trx)" class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition">Detail & Status</button>
                                @else
                                    <button @click="openStatusModal(trx)" class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition">Detail</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="transactions.length === 0 && !tableLoading" x-cloak>
                    <td colspan="6" class="py-8 text-center text-gray-400 text-sm">Tidak ada data transaksi.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between" x-show="links.length > 3" x-cloak>
        <div class="flex flex-wrap gap-1">
            <template x-for="(link, index) in links" :key="index">
                <button @click.prevent="if(link.url) fetchData(link.url)"
                        x-html="link.label"
                        :disabled="!link.url || link.active"
                        :class="{
                            'bg-green-600 text-white font-medium': link.active,
                            'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200': !link.active && link.url,
                            'bg-gray-50 text-gray-400 border border-gray-100 cursor-not-allowed': !link.url
                        }"
                        class="px-3 py-1.5 min-w-[2rem] text-sm rounded-lg transition-colors">
                </button>
            </template>
        </div>
    </div>
</div>

<div class="mt-8" x-show="showScannerInterface" x-cloak>
<div class="mx-auto transition-all duration-300" :class="transaction ? 'max-w-5xl' : 'max-w-lg'">

    {{-- Header --}}
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Scanner Kasir</h2>
        <p class="text-sm text-gray-400 mt-1">Scan QR Code customer untuk konfirmasi pembayaran</p>
    </div>

    <div class="flex flex-col md:flex-row gap-6 items-start">
        
        {{-- Kolom Kiri: Scanner --}}
        <div class="flex-1 w-full space-y-4">

            {{-- Scanner Card --}}
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        {{-- Tab: Kamera / Upload / Manual --}}
        <div class="flex border-b border-gray-100">
            <button @click="mode = 'camera'; startCamera()"
                    :class="mode === 'camera' ? 'border-b-2 border-green-600 text-green-700 font-semibold' : 'text-gray-400'"
                    class="flex-1 py-3 text-sm transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Kamera
            </button>
            <button @click="mode = 'upload'; stopCamera()"
                    :class="mode === 'upload' ? 'border-b-2 border-green-600 text-green-700 font-semibold' : 'text-gray-400'"
                    class="flex-1 py-3 text-sm transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Upload Foto
            </button>
            <button @click="mode = 'manual'; stopCamera()"
                    :class="mode === 'manual' ? 'border-b-2 border-green-600 text-green-700 font-semibold' : 'text-gray-400'"
                    class="flex-1 py-3 text-sm transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Manual
            </button>
        </div>

        <div class="p-5">

            {{-- MODE: Kamera --}}
            <div x-show="mode === 'camera'" class="w-full">
                <div class="relative bg-gray-900 rounded-2xl overflow-hidden mb-4" style="aspect-ratio: 1;">
                    <div id="qr-reader" class="w-full h-full object-cover bg-gray-900"></div>

                    {{-- Camera status --}}
                    <div class="absolute bottom-3 left-0 right-0 flex justify-center z-10 pointer-events-none">
                        <span x-show="scanning"
                              class="bg-black/50 text-white text-xs px-3 py-1 rounded-full">
                            Mendeteksi QR Code...
                        </span>
                        <span x-show="!scanning && mode === 'camera'"
                              class="bg-black/50 text-white text-xs px-3 py-1 rounded-full">
                            Kamera tidak aktif
                        </span>
                    </div>
                </div>

                <button @click="scanning ? stopCamera() : startCamera()"
                        :class="scanning ? 'bg-red-500 hover:bg-red-600' : 'bg-green-600 hover:bg-green-700'"
                        class="w-full text-white font-semibold py-3 rounded-xl text-sm transition">
                    <span x-text="scanning ? 'Hentikan Kamera' : 'Aktifkan Kamera'"></span>
                </button>
            </div>

            {{-- MODE: Upload Foto --}}
            <div x-show="mode === 'upload'">
                <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition mb-4">
                    <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-400">Klik untuk upload foto QR Code</p>
                    <p class="text-xs text-gray-300 mt-1">PNG, JPG, JPEG</p>
                    <input type="file" accept="image/*" class="hidden" @change="scanFromFile($event)">
                </label>

                {{-- Hidden element for file scanning --}}
                <div id="qr-reader-file" class="hidden"></div>

                {{-- Preview --}}
                <div x-show="uploadPreview" class="mb-4">
                    <img :src="uploadPreview" class="w-32 h-32 mx-auto rounded-xl object-cover border border-gray-100">
                </div>
            </div>

            {{-- MODE: Manual --}}
            <div x-show="mode === 'manual'">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Invoice</label>
                    <input type="text"
                           x-ref="manualInput"
                           x-model="manualInvoice"
                           @keyup.enter="searchManual()"
                           placeholder="INV-20260101120000-1"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-400 mt-1">Tekan Enter atau klik tombol cari</p>
                </div>
                <button @click="searchManual()"
                        :disabled="loading"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl text-sm transition disabled:opacity-50">
                    <span x-show="!loading">Cari Transaksi</span>
                    <span x-show="loading">Mencari...</span>
                </button>
            </div>

        </div>
    </div>

    {{-- Loading --}}
    <div x-show="loading" class="text-center py-4">
        <div class="inline-flex items-center gap-2 text-sm text-gray-500">
            <svg class="w-4 h-4 animate-spin text-green-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Memproses...
        </div>
    </div>

    {{-- Error --}}
    <div x-show="error" x-cloak
         class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-red-700" x-text="error"></p>
    </div>

        </div> {{-- End Kolom Kiri --}}

        {{-- Kolom Kanan: Result Card --}}
        <div x-show="transaction" x-cloak class="flex-1 w-full space-y-4">

        {{-- Transaction Info --}}
        <div class="bg-white rounded-2xl shadow-sm p-5">

            {{-- Status Badge --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Invoice</p>
                    <p class="font-mono font-bold text-gray-800 text-sm" x-text="transaction?.invoice_number"></p>
                </div>
                <span :class="{
                    'bg-yellow-100 text-yellow-700': transaction?.status === 'pending',
                    'bg-green-100 text-green-700': transaction?.status === 'terbayarkan' || transaction?.status === 'completed',
                    'bg-red-100 text-red-700': transaction?.status === 'batal',
                    'bg-blue-100 text-blue-700': transaction?.status === 'shipping',
                }" class="px-3 py-1 rounded-full text-xs font-semibold capitalize" x-text="transaction?.status">
                </span>
            </div>

            {{-- Customer Info --}}
            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm"
                         x-text="transaction?.customer?.name?.charAt(0)?.toUpperCase()">
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800" x-text="transaction?.customer?.name"></p>
                        <p class="text-xs text-gray-400" x-text="transaction?.customer?.phone ?? '-'"></p>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="space-y-2 mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Item Pesanan</p>
                <template x-for="item in transaction?.items" :key="item.name">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600" x-text="`${item.name} ×${item.quantity}`"></span>
                        <span class="font-medium text-gray-800" x-text="item.subtotal"></span>
                    </div>
                </template>
            </div>

            {{-- Total --}}
            <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                <span class="font-semibold text-gray-700">Total Pembayaran</span>
                <span class="text-xl font-bold text-green-700" x-text="transaction?.total_formatted"></span>
            </div>

            <p class="text-xs text-gray-400 mt-2" x-text="`Dipesan: ${transaction?.created_at}`"></p>
        </div>

        {{-- ACTION BUTTONS (hanya untuk status pending) --}}
        <div x-show="transaction?.status === 'pending'" class="space-y-3">

            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
                <p class="text-sm font-semibold text-yellow-800 mb-1">Menunggu Konfirmasi</p>
                <p class="text-xs text-yellow-600">Pastikan customer sudah menyerahkan uang sesuai total sebelum konfirmasi.</p>
            </div>

            {{-- Konfirmasi Bayar --}}
            <button @click="confirmTransaction('paid')"
                    :disabled="confirming"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl text-base transition disabled:opacity-50 flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <span x-show="!confirming">Konfirmasi Sudah Bayar</span>
                <span x-show="confirming">Memproses...</span>
            </button>

            {{-- Batalkan --}}
            <button @click="confirmTransaction('cancelled')"
                    :disabled="confirming"
                    class="w-full bg-white border border-red-200 hover:bg-red-50 text-red-600 font-semibold py-3 rounded-2xl text-sm transition disabled:opacity-50 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batalkan Pesanan
            </button>

        </div>

        {{-- Status sudah diproses --}}
        <div x-show="transaction?.status !== 'pending'" x-cloak>
            <div :class="{
                    'bg-green-50 border-green-200 text-green-700': transaction?.status === 'terbayarkan' || transaction?.status === 'completed',
                    'bg-red-50 border-red-200 text-red-700': transaction?.status === 'batal',
                    'bg-blue-50 border-blue-200 text-blue-700': transaction?.status === 'shipping',
                 }"
                 class="border rounded-2xl p-4 text-center">
                <p class="font-semibold text-sm" x-text="statusMessage()"></p>
            </div>
        </div>

        {{-- Success State setelah konfirmasi --}}
        <div x-show="confirmed" x-cloak
             class="bg-white rounded-2xl shadow-sm p-8 text-center">
            <div :class="confirmedStatus === 'terbayarkan' ? 'bg-green-100' : 'bg-red-100'"
                 class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg x-show="confirmedStatus === 'terbayarkan'" class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <svg x-show="confirmedStatus === 'batal'" class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="text-lg font-bold text-gray-800 mb-1" x-text="confirmedMessage"></p>
            <p class="text-sm text-gray-400 mb-6" x-text="transaction?.invoice_number"></p>
            <button @click="resetScanner()"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-xl text-sm transition">
                Scan QR Berikutnya
            </button>
        </div>

        {{-- Scan Lagi --}}
        <div x-show="!confirmed">
            <button @click="resetScanner()"
                    class="w-full text-center text-sm text-gray-400 hover:text-gray-600 py-2">
                ← Scan QR Code lain
            </button>
        </div>

    </div> {{-- End Kolom Kanan --}}

    </div> {{-- End Grid --}}
</div> {{-- End mx-auto --}}
</div> {{-- End showScannerInterface --}}

<template x-teleport="body">
    <div x-cloak x-show="showStatusModal" x-transition 
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @click.self="showStatusModal = false">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-xl flex flex-col">
            <form :action="selectedTransaction ? '/transactions/' + selectedTransaction.id + '/status' : ''" method="POST">
                @csrf
                @method('PATCH')
                <div class="px-6 py-5 bg-white">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Detail & Status Pesanan</h3>
                        <button type="button" @click="showStatusModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <template x-if="selectedTransaction">
                        <div>
                            <div class="mb-4 text-sm text-gray-600 space-y-1">
                                <p><span class="font-medium text-gray-500 inline-block w-24">Invoice:</span> <span class="font-mono text-gray-800" x-text="selectedTransaction.invoice_number"></span></p>
                                <p><span class="font-medium text-gray-500 inline-block w-24">Pelanggan:</span> <span class="font-medium text-gray-800" x-text="selectedTransaction.user?.name || '-'"></span></p>
                                <p><span class="font-medium text-gray-500 inline-block w-24">Tanggal:</span> <span x-text="new Date(selectedTransaction.created_at).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'})"></span></p>
                                <p><span class="font-medium text-gray-500 inline-block w-24">Total:</span> <span class="font-bold text-green-700" x-text="'Rp ' + parseInt(selectedTransaction.total_amount).toLocaleString('id-ID')"></span></p>
                            </div>

                            <div class="border border-gray-100 rounded-xl overflow-hidden mb-2">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 font-medium">
                                        <tr>
                                            <th class="py-2 px-3">Produk</th>
                                            <th class="py-2 px-3">Qty</th>
                                            <th class="py-2 px-3">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 text-gray-700">
                                        <template x-for="item in selectedTransaction.transaction_details" :key="item.id">
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="py-2 px-3" x-text="item.product?.name || '-'"></td>
                                                <td class="py-2 px-3" x-text="item.quantity + ' ' + (item.product?.unit || '')"></td>
                                                <td class="py-2 px-3 font-medium text-gray-800" x-text="'Rp ' + parseInt(item.subtotal).toLocaleString('id-ID')"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex flex-wrap gap-3 sm:flex-row-reverse border-t border-gray-100">
                    @if(Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'petani')
                    <template x-if="selectedTransaction?.status === 'pending'">
                        <button type="submit" name="status" value="terbayarkan" class="w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-xl hover:bg-green-700 shadow-sm focus:outline-none">
                            Konfirmasi Terbayarkan
                        </button>
                    </template>
                    <template x-if="selectedTransaction?.status === 'pending'">
                        <button type="submit" name="status" value="batal" class="w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-sm focus:outline-none" @click.prevent="$dispatch('confirm', { message: 'Batalkan pesanan ini?', onConfirm: () => $el.closest('form').submit() })">
                            Batalkan
                        </button>
                    </template>
                    @endif
                    <button type="button" @click="showStatusModal = false" class="w-full sm:w-auto inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 shadow-sm focus:outline-none">
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

</div> {{-- End x-data --}}

@endsection

@section('scripts')
<script>
function scannerApp() {
    return {
        mode: 'camera',
        search: '',
        statusFilter: '{{ request('status') }}',
        transactions: {{ Js::from($transactions->items()) }},
        links: {{ Js::from($transactions->toArray()['links']) }},
        tableLoading: false,
        
        init() {
            this.$watch('search', value => this.fetchData());
            this.$watch('statusFilter', value => this.fetchData());
        },
        
        fetchData(url = '{{ route('transactions.index') }}') {
            this.tableLoading = true;
            const params = new URLSearchParams();
            if (this.search) params.append('search', this.search);
            if (this.statusFilter) params.append('status', this.statusFilter);
            
            const finalUrl = url.includes('?') ? `${url}&${params.toString()}` : `${url}?${params.toString()}`;
            
            fetch(finalUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.transactions = data.data;
                this.links = data.links;
                this.tableLoading = false;
            })
            .catch(() => { this.tableLoading = false; });
        },

        showScannerInterface: false,
        scanning: false,
        loading: false,
        confirming: false,
        confirmed: false,
        confirmedStatus: null,
        confirmedMessage: '',
        error: null,
        transaction: null,
        manualInvoice: '',
        selectedTransaction: null,
        showStatusModal: false,
        openStatusModal(trx) {
            this.selectedTransaction = trx;
            this.showStatusModal = true;
        },
        uploadPreview: null,
        html5QrCode: null,



        async startScanner() {
            this.showScannerInterface = true;
            this.startCamera();
        },

        async startCamera() {
            this.error = null;
            this.scanning = true;

            try {
                if (!this.html5QrCode) {
                    this.html5QrCode = new window.Html5Qrcode("qr-reader");
                }

                await this.html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText, decodedResult) => {
                        this.stopCamera();
                        this.findTransaction(decodedText);
                    },
                    (errorMessage) => {
                        // ignore background errors
                    }
                );
            } catch (err) {
                this.error = 'Tidak bisa mengakses kamera. Gunakan mode Upload atau Manual.';
                this.scanning = false;
            }
        },

        stopCamera() {
            this.scanning = false;
            if (this.html5QrCode && this.html5QrCode.isScanning) {
                this.html5QrCode.stop().catch(err => console.error("Error stopping qr code", err));
            }
        },

        async scanFromFile(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.uploadPreview = URL.createObjectURL(file);
            this.error = null;
            this.loading = true;

            try {
                const html5QrCodeFile = new window.Html5Qrcode("qr-reader-file");
                const decodedText = await html5QrCodeFile.scanFile(file, true);
                await this.findTransaction(decodedText);
            } catch (e) {
                this.error = 'QR Code tidak terdeteksi dalam foto. Coba foto yang lebih jelas.';
            } finally {
                this.loading = false;
            }
        },

        async searchManual() {
            if (!this.manualInvoice.trim()) return;
            await this.findTransaction(this.manualInvoice.trim());
        },

        async findTransaction(invoiceNumber) {
            this.loading = true;
            this.error = null;
            this.transaction = null;

            try {
                const response = await fetch('{{ route('transactions.scanner.find') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ invoice_number: invoiceNumber }),
                });

                const data = await response.json();

                if (data.found) {
                    this.transaction = data.transaction;
                } else {
                    this.error = data.message ?? 'Transaksi tidak ditemukan.';
                }
            } catch (err) {
                this.error = 'Gagal menghubungi server. Periksa koneksi internet.';
            } finally {
                this.loading = false;
            }
        },

        confirmTransaction(status) {
            const message = status === 'terbayarkan' 
                ? `Konfirmasi pembayaran ${this.transaction.total_formatted} dari ${this.transaction.customer.name}?`
                : 'Batalkan pesanan ini? Stok akan dikembalikan.';
                
            window.dispatchEvent(new CustomEvent('confirm', {
                detail: {
                    title: 'Konfirmasi Transaksi',
                    message: message,
                    type: status === 'terbayarkan' ? 'info' : 'warning',
                    onConfirm: async () => {
                        this.confirming = true;
                        try {
                            const response = await fetch(`/transactions/scanner/confirm/${this.transaction.id}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({ status }),
                            });

                            const data = await response.json();

                            if (data.success) {
                                this.confirmed = true;
                                this.confirmedStatus = status;
                                this.confirmedMessage = data.message;
                                this.transaction.status = status;
                            } else {
                                this.error = data.message;
                            }
                        } catch (err) {
                            this.error = 'Gagal memproses konfirmasi.';
                        } finally {
                            this.confirming = false;
                        }
                    }
                }
            }));
        },

        statusMessage() {
            const messages = {
                'terbayarkan': 'Pembayaran sudah dikonfirmasi',
                'completed': 'Pesanan selesai',
                'batal': 'Pesanan telah dibatalkan',
                'shipping': 'Pesanan sedang dikirim',
            };
            return messages[this.transaction?.status] ?? 'Status tidak diketahui';
        },

        resetScanner() {
            this.transaction = null;
            this.confirmed = false;
            this.confirmedStatus = null;
            this.confirmedMessage = '';
            this.error = null;
            this.manualInvoice = '';
            this.uploadPreview = null;
            this.mode = 'camera';
        },
    }
}
</script>

<style>
/* CSS overrides for html5-qrcode */
#qr-reader {
    border: none !important;
}
#qr-reader video {
    object-fit: cover !important;
}
#qr-reader__dashboard_section_csr span {
    color: white !important;
}
#qr-reader__dashboard_section_swaplink {
    color: white !important;
}
</style>
@endsection
