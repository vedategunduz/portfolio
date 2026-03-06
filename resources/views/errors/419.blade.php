@extends('errors.layout')

@section('title', '419 - ' . config('app.name'))
@section('heading', 'Oturum süresi doldu')
@section('message', 'Güvenlik nedeniyle sayfa yenilendi. Lütfen işleminizi tekrarlayın.')

@section('extra-link')
    <a href="javascript:location.reload()" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-6 py-3 rounded-sm font-medium border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#D62113] hover:text-[#D62113] transition-all hover:scale-105 active:scale-95">
        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        Sayfayı Yenile
    </a>
@endsection
