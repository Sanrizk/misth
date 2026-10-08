@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')

<div x-data="userIndex()" class="space-y-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Manajemen User</h2>
        @if(Auth::user()->role->name === 'admin')
        <a href="{{ route('users.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
            + Tambah User
        </a>
        @endif
    </div>

    {{-- Filters & Search --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" x-model.debounce.300ms="search" placeholder="Cari nama, email, atau no HP..." 
                   class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <select x-model="roleFilter" @change="fetchData()"
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Semua Role</option>
            @foreach($roles as $role)
            <option value="{{ $role->name }}">
                {{ ucfirst($role->name) }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden relative">
        {{-- Loading Overlay --}}
        <div x-show="loading" class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">No</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Nama</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Email</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">No HP</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Role</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Terdaftar</th>
                        <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    <template x-for="(user, index) in users" :key="user.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-gray-400" x-text="index + 1 + (currentPage - 1) * perPage"></td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold shrink-0"
                                         x-text="user.name.substring(0, 1).toUpperCase()">
                                    </div>
                                    <span class="font-medium text-gray-800" x-text="user.name"></span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-gray-500" x-text="user.email"></td>
                            <td class="py-3 px-4 text-gray-500" x-text="user.phone || '-'"></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                                      :class="{
                                          'bg-purple-100 text-purple-700': user.role?.name === 'admin',
                                          'bg-green-100 text-green-700': user.role?.name === 'petani',
                                          'bg-blue-100 text-blue-700': user.role?.name !== 'admin' && user.role?.name !== 'petani'
                                      }" x-text="user.role ? user.role.name.charAt(0).toUpperCase() + user.role.name.slice(1) : '-'">
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-400 text-xs" x-text="formatDate(user.created_at)"></td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <a :href="`/users/${user.id}`"
                                       class="text-xs px-3 py-1 bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition">
                                        Detail
                                    </a>
                                    @if(Auth::user()->role->name === 'admin')
                                    <a :href="`/users/${user.id}/edit`"
                                       class="text-xs px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                                        Edit
                                    </a>
                                    <template x-if="user.id !== {{ Auth::id() }}">
                                        <form :action="`/users/${user.id}`" method="POST">
                                            @csrf @method('DELETE')
                                            <button @click.prevent="$dispatch('confirm', { message: 'Yakin hapus user ini?', onConfirm: () => $el.closest('form') ? $el.closest('form').submit() : null })"
                                                    class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </template>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="users.length === 0 && !loading" x-cloak>
                        <td colspan="7" class="py-8 text-center text-gray-400 text-xs">Belum ada data user.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Alpine Custom Pagination --}}
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
</div>

<script>
function userIndex() {
    return {
        users: {{ Js::from($users->items()) }},
        links: {{ Js::from($users->toArray()["links"]) }},
        search: '{{ request("search") }}',
        roleFilter: '{{ request("role") }}',
        loading: false,
        currentPage: {{ $users->currentPage() }},
        perPage: {{ $users->perPage() }},

        init() {
            this.$watch('search', value => {
                this.fetchData();
            });
        },

        fetchData(url = '{{ route("users.index") }}') {
            this.loading = true;
            
            const params = new URLSearchParams();
            if (this.search) params.append('search', this.search);
            if (this.roleFilter) params.append('role', this.roleFilter);

            const hasQueryParams = url.includes('?');
            const finalUrl = `${url}${hasQueryParams ? '&' : '?'}${params.toString()}`;

            fetch(finalUrl, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                this.users = data.data;
                this.links = data.links;
                this.currentPage = data.current_page;
                this.perPage = data.per_page;
            })
            .finally(() => {
                this.loading = false;
            });
        },
        
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        }
    }
}
</script>
@endsection
