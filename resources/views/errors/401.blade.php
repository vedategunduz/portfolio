@extends('errors.layout')

@section('title', '401 - ' . config('app.name'))
@section('heading', 'Oturum gerekli')
@section('message', 'Bu sayfayı görüntülemek için giriş yapmanız gerekiyor.')

@section('extra-link')
    <a href="{{ route('admin.login') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-sm font-medium border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#D62113] hover:text-[#D62113] transition-colors">
        Admin Girişi
    </a>
@endsection
