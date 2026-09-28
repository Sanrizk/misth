@extends('layouts.app')
@section('title', 'Detail User')
@section('content')

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-6">

        {{-- Avatar & Name --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $user->name }}</h2>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                    @if(optional($user->role)->name === 'admin') bg-purple-100 text-purple-700
                    @elseif(optional($user->role)->name === 'petani') bg-green-100 text-green-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ ucfirst(optional($user->role)->name ?? '-') }}
                </span>
            </div>
        </div>

        {{-- Detail Info --}}
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-xs text-gray-400">Email</span>
                <span class="text-sm font-medium text-gray-700">{{ $user->email }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-xs text-gray-400">No HP</span>
                <span class="text-sm font-medium text-gray-700">{{ $user->phone ?? '-' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-xs text-gray-400">Role</span>
                <span class="text-sm font-medium text-gray-700">{{ ucfirst(optional($user->role)->name ?? '-') }}</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-xs text-gray-400">Terdaftar</span>
                <span class="text-sm font-medium text-gray-700">
                    {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y H:i') }}
                </span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 mt-6">
            <a href="{{ route('users.index') }}"
               class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">
                Kembali
            </a>
            @if(Auth::user()->role->name === 'admin')
            <a href="{{ route('users.edit', $user->id) }}"
               class="flex-1 text-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                Edit User
            </a>
            @endif
        </div>

    </div>
</div>

@endsection
