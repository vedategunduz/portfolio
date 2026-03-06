@extends('errors.layout')

@section('title', '401 - ' . config('app.name'))
@section('heading', 'Oturum gerekli')
@section('message', 'Bu sayfayı görüntülemek için giriş yapmanız gerekiyor.')

{{-- @section('extra-link')
    <a href="{{ route('admin.login') }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-6 py-3 rounded-sm font-medium border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#D62113] hover:text-[#D62113] transition-all hover:scale-105 active:scale-95">
        <i data-lucide="log-in" class="w-4 h-4"></i>
        Admin Girişi
    </a>
@endsection --}}
