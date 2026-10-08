@php $role = Auth::user()->role->name ?? ''; @endphp
<div class="fixed bottom-0 left-0 right-0 bg-green-900 border-t border-green-800 z-50 lg:hidden flex justify-around items-center h-16 px-2 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.3)]"
     x-data="{ activeMenu: null }">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] sm:text-xs font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-green-300 hover:text-white' }}">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Home
    </a>

    @if($role !== 'customer')
    {{-- Master Data --}}
    <div class="relative flex flex-col items-center justify-center w-full h-full text-[10px] sm:text-xs font-medium transition-colors {{ request()->routeIs('suppliers.*', 'plant-types.*', 'materials.*', 'users.*') ? 'text-white' : 'text-green-300 hover:text-white' }}"
         @click.outside="if(activeMenu === 'master') activeMenu = null">
        
        <button @click="activeMenu = activeMenu === 'master' ? null : 'master'" class="flex flex-col items-center justify-center w-full h-full outline-none focus:outline-none">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
            </svg>
            Data
        </button>

        {{-- Floating Menu --}}
        <div x-cloak x-show="activeMenu === 'master'" 
             x-transition:enter="transition ease-out duration-200 origin-bottom"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150 origin-bottom"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-green-800 border border-green-700 rounded-2xl shadow-xl p-2 flex flex-col gap-1 z-50 text-left">
            <div class="px-2 py-1 text-[10px] font-bold text-green-300 uppercase tracking-wider">Master Data</div>
            <a href="{{ route('suppliers.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> Supplier
            </a>
            <a href="{{ route('plant-types.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> Jenis Tanaman
            </a>
            <a href="{{ route('materials.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg> Bahan
            </a>
            <a href="{{ route('users.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> User
            </a>
        </div>
    </div>

    {{-- Transaksi --}}
    <div class="relative flex flex-col items-center justify-center w-full h-full text-[10px] sm:text-xs font-medium transition-colors {{ request()->routeIs('purchases.*', 'plantings.*', 'products.*', 'transactions.*') ? 'text-white' : 'text-green-300 hover:text-white' }}"
         @click.outside="if(activeMenu === 'trx') activeMenu = null">
        
        <button @click="activeMenu = activeMenu === 'trx' ? null : 'trx'" class="flex flex-col items-center justify-center w-full h-full outline-none focus:outline-none">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Transaksi
        </button>

        {{-- Floating Menu --}}
        <div x-cloak x-show="activeMenu === 'trx'" 
             x-transition:enter="transition ease-out duration-200 origin-bottom"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150 origin-bottom"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-green-800 border border-green-700 rounded-2xl shadow-xl p-2 flex flex-col gap-1 z-50 text-left">
            <div class="px-2 py-1 text-[10px] font-bold text-green-300 uppercase tracking-wider">Transaksi</div>
            <a href="{{ route('purchases.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Pembelian
            </a>
            <a href="{{ route('plantings.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Penanaman
            </a>
            <a href="{{ route('products.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg> Produk
            </a>
            <a href="{{ route('transactions.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Pesanan
            </a>
        </div>
    </div>
    @endif

    @if($role === 'customer')
    <a href="{{ route('store.orders.index') }}" class="flex flex-col items-center justify-center w-full h-full text-[10px] sm:text-xs font-medium transition-colors {{ request()->routeIs('store.orders.*') ? 'text-white' : 'text-green-300 hover:text-white' }}">
        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        Pesanan
    </a>
    @endif

    {{-- Lainnya --}}
    <div class="relative flex flex-col items-center justify-center w-full h-full text-[10px] sm:text-xs font-medium transition-colors {{ request()->routeIs('reports.*', 'store.index') ? 'text-white' : 'text-green-300 hover:text-white' }}"
         @click.outside="if(activeMenu === 'lain') activeMenu = null">
        
        <button @click="activeMenu = activeMenu === 'lain' ? null : 'lain'" class="flex flex-col items-center justify-center w-full h-full outline-none focus:outline-none">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
            </svg>
            Lainnya
        </button>

        {{-- Floating Menu --}}
        <div x-cloak x-show="activeMenu === 'lain'" 
             x-transition:enter="transition ease-out duration-200 origin-bottom"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150 origin-bottom"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="absolute bottom-full right-2 mb-2 w-48 bg-green-800 border border-green-700 rounded-2xl shadow-xl p-2 flex flex-col gap-1 z-50 text-left">
            <div class="px-2 py-1 text-[10px] font-bold text-green-300 uppercase tracking-wider">Lainnya</div>
            @if($role !== 'customer')
            <a href="{{ route('reports.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> Laporan
            </a>
            @endif
            <a href="{{ route('store.index') }}" class="px-3 py-2 text-sm rounded-xl hover:bg-green-700 text-white flex items-center gap-3 transition-colors">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Ke Toko
            </a>
        </div>
    </div>
</div>