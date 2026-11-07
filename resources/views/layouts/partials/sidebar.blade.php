<div class="flex flex-col h-full">

    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-green-700">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-wide flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Misth Logo" class="w-8 h-8 rounded-full object-cover shadow-sm ring-2 ring-green-400/40">
            <span>Misth</span>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">

        @php $role = Auth::user()->role->name ?? ''; @endphp

        {{-- Dashboard (Semua Role) --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('dashboard') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg> 
            Dashboard
        </a>

        {{-- Master Data Accordion (Admin & Persediaan) --}}
        @if(in_array($role, ['admin', 'persediaan']))
        <div x-data="{ open: {{ request()->routeIs('suppliers.*', 'plant-types.*', 'materials.*', 'users.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-green-800 text-green-100">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                    </svg>
                    Master Data
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-cloak x-show="open" x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 max-h-64"
             x-transition:enter-end="opacity-100 max-h-64"
             x-transition:leave="transition-all ease-in duration-200"
             x-transition:leave-start="opacity-100 max-h-64"
             x-transition:leave-end="opacity-0 max-h-0"
             class="pl-4 pr-1 py-1 space-y-1 overflow-hidden">
                <a href="{{ route('suppliers.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('suppliers.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg> 
                    Supplier
                </a>
                @if($role === 'admin')
                <a href="{{ route('plant-types.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('plant-types.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg> 
                    Jenis Tanaman
                </a>
                @endif
                <a href="{{ route('materials.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('materials.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg> 
                    Bahan Baku
                </a>
                @if($role === 'admin')
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('users.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg> 
                    User
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Transaksi / Operasional Accordion --}}
        @if(in_array($role, ['admin', 'petani', 'persediaan', 'keuangan', 'pimpinan']))
        <div x-data="{ open: {{ request()->routeIs('purchases.*', 'plantings.*', 'finance.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-green-800 text-green-100">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Transaksi
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-cloak x-show="open" x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 max-h-64"
             x-transition:enter-end="opacity-100 max-h-64"
             x-transition:leave="transition-all ease-in duration-200"
             x-transition:leave-start="opacity-100 max-h-64"
             x-transition:leave-end="opacity-0 max-h-0"
             class="pl-4 pr-1 py-1 space-y-1 overflow-hidden">
                @if(in_array($role, ['admin', 'persediaan']))
                <a href="{{ route('purchases.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('purchases.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg> 
                    Pembelian
                </a>
                @endif
                @if(in_array($role, ['admin', 'petani', 'pimpinan']))
                <a href="{{ route('plantings.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('plantings.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg> 
                    Penanaman
                </a>
                @endif
                @if(in_array($role, ['admin', 'keuangan', 'pimpinan']))
                <a href="{{ route('finance.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('finance.*') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg> 
                    Keuangan (Kas)
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Menu Stok (Admin, Persediaan, Pimpinan) --}}
        @if(in_array($role, ['admin', 'persediaan', 'pimpinan']))
        <div x-data="{ open: {{ request()->routeIs('stocks.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-green-800 text-green-100">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Stok
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-cloak x-show="open" x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 max-h-64"
             x-transition:enter-end="opacity-100 max-h-64"
             x-transition:leave="transition-all ease-in duration-200"
             x-transition:leave-start="opacity-100 max-h-64"
             x-transition:leave-end="opacity-0 max-h-0"
             class="pl-4 pr-1 py-1 space-y-1 overflow-hidden">
                <a href="{{ route('stocks.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('stocks.index') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg> 
                    Overview Stok
                </a>
                <a href="{{ route('stocks.summary') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('stocks.summary') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg> 
                    Stok Keseluruhan
                </a>
                <a href="{{ route('stocks.mutations') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('stocks.mutations') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg> 
                    Mutasi Stok
                </a>
                <a href="{{ route('stocks.opname') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('stocks.opname') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg> 
                    Stok Opname
                </a>
            </div>
        </div>
        @endif

        {{-- Laporan Accordion (Admin & Pimpinan) --}}
        @if(in_array($role, ['admin', 'pimpinan']))
        <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-green-800 text-green-100">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Laporan
                </div>
                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-cloak x-show="open" x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 max-h-64"
             x-transition:enter-end="opacity-100 max-h-64"
             x-transition:leave="transition-all ease-in duration-200"
             x-transition:leave-start="opacity-100 max-h-64"
             x-transition:leave-end="opacity-0 max-h-0"
             class="pl-4 pr-1 py-1 space-y-1 overflow-hidden">
                <a href="{{ route('reports.plantings') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.plantings') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg> 
                    Laporan Penanaman
                </a>
                <a href="{{ route('reports.harvests') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.harvests') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg> 
                    Laporan Panen
                </a>
                <a href="{{ route('reports.materials') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.materials') ? 'bg-green-700 text-white' : 'text-green-200 hover:text-white hover:bg-green-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg> 
                    Penggunaan Bahan
                </a>
            </div>
        </div>
        @endif

    </nav>

</div>
