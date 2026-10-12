@extends('errors.layout')

@section('code', '500')
@section('title', 'Kesalahan Server')
@section('badge_bg', 'bg-red-50 dark:bg-red-950/40 text-red-500 dark:text-red-400')
@section('code_badge', 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300')

@section('icon')
<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
</svg>
@endsection

@section('message', 'Terjadi gangguan internal pada server kami saat memproses permintaan Anda. Tim kami sedang berusaha memperbaikinya secepat mungkin.')

