@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi Telah Berakhir')
@section('badge_bg', 'bg-orange-50 dark:bg-orange-950/40 text-orange-500 dark:text-orange-400')
@section('code_badge', 'bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300')

@section('icon')
<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
</svg>
@endsection

@section('message', 'Sesi keamanan Anda telah kedaluwarsa karena tidak ada aktivitas dalam waktu lama. Silakan muat ulang halaman atau login kembali.')

