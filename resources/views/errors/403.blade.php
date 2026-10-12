@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Dibatasi')
@section('badge_bg', 'bg-amber-50 dark:bg-amber-950/40 text-amber-500 dark:text-amber-400')
@section('code_badge', 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300')

@section('icon')
<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
</svg>
@endsection

@section('message', 'Maaf, Anda tidak memiliki hak akses atau izin untuk mengunjungi halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.')

