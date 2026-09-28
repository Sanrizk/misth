@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Manajemen User</h2>
    @if(Auth::user()->role->name === 'admin')
    <a href="{{ route('users.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
        + Tambah User
    </a>
    @endif
</div>

{{-- Filter Role --}}
<form method="GET" action="{{ route('users.index') }}" class="flex gap-3 mb-4">
    <select name="role" onchange="this.form.submit()"
            class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <option value="">Semua Role</option>
        @foreach($roles as $role)
        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
            {{ ucfirst($role->name) }}
        </option>
        @endforeach
    </select>
</form>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
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
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-gray-500">{{ $user->email }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $user->phone ?? '-' }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            @if(optional($user->role)->name === 'admin') bg-purple-100 text-purple-700
                            @elseif(optional($user->role)->name === 'petani') bg-green-100 text-green-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ ucfirst(optional($user->role)->name ?? '-') }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-400 text-xs">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <a href="{{ route('users.show', $user->id) }}"
                               class="text-xs px-3 py-1 bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition">
                                Detail
                            </a>
                            @if(Auth::user()->role->name === 'admin')
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="text-xs px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                                Edit
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Yakin hapus user ini?')"
                                        class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                    Hapus
                                </button>
                            </form>
                            @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-400 text-xs">Belum ada data user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $users->links('pagination::tailwind') }}
    </div>
</div>

@endsection
