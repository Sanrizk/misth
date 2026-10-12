@extends('errors.layout')

@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')
@section('badge_bg', 'bg-blue-50 dark:bg-blue-950/40 text-blue-500 dark:text-blue-400')
@section('code_badge', 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300')

@section('icon')
<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
</svg>
@endsection

@section('message', 'Halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau alamat URL yang Anda masukkan salah.')

