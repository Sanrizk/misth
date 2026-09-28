<div class="flex flex-col h-full">

    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-green-700">
        <span class="text-xl font-bold tracking-wide flex items-center gap-2">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg> 
            Misth
        </span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

        @php $role = Auth::user()->role->name; @endphp

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('dashboard') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg> 
            Dashboard
        </a>

        @if($role !== 'customer')
        <a href="{{ route('plant-types.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('plant-types.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg> 
            Jenis Tanaman
        </a>

        <a href="{{ route('plantings.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('plantings.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg> 
            Penanaman
        </a>

        <a href="{{ route('materials.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('materials.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg> 
            Bahan
        </a>

        <a href="{{ route('products.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('products.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg> 
            Produk
        </a>

        <a href="{{ route('transactions.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('transactions.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg> 
            Transaksi
        </a>
        
        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('users.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg> 
            Manajemen User
        </a>
        @endif

        @if($role === 'customer')
        <a href="{{ route('store.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('store.index') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg> 
            Toko
        </a>
        <a href="{{ route('store.orders.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->routeIs('store.orders.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-800' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg> 
            Pesanan Saya
        </a>
        @endif

    </nav>

</div>
