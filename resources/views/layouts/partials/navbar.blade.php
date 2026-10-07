<div class="flex items-center justify-between px-6 h-16">

    {{-- Sidebar toggle (mobile) --}}
    <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    {{-- Page title --}}
    <h1 class="text-base font-semibold text-gray-700 hidden lg:block">
        @yield('title')
    </h1>

    {{-- Right: user dropdown --}}
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open"
                class="flex items-center gap-2 text-sm text-gray-700 hover:text-green-700">
            <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold text-xs">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="hidden sm:block">{{ Auth::user()->name }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open" @click.outside="open = false"
             class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
             x-transition x-cloak>
            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                Profil Saya
            </a>
            <hr class="my-1 border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    Keluar
                </button>
            </form>
        </div>
    </div>

</div>
