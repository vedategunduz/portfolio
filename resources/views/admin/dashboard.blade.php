@extends('layouts.admin')

@section('title', 'Admin Dashboard - ' . config('app.name'))
@section('page-title', 'Admin Panel')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Visits -->
        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-6 transition-shadow hover:shadow-lg dark:shadow-black/20">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 rounded-sm bg-[#D62113]/10 dark:bg-[#D62113]/20 flex items-center justify-center">
                    <i data-lucide="eye" class="w-6 h-6 text-[#D62113]"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Toplam Ziyaret</p>
                    <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-0.5">{{ number_format($stats['total_visits']) }}</p>
                </div>
            </div>
        </div>

        <!-- Unique Visitors -->
        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-6 transition-shadow hover:shadow-lg dark:shadow-black/20">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 rounded-sm bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Benzersiz Ziyaretçi</p>
                    <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-0.5">{{ number_format($stats['unique_visitors']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Messages -->
        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-6 transition-shadow hover:shadow-lg dark:shadow-black/20">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 rounded-sm bg-violet-500/10 dark:bg-violet-500/20 flex items-center justify-center">
                    <i data-lucide="mail" class="w-6 h-6 text-violet-600 dark:text-violet-400"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Toplam Mesaj</p>
                    <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-0.5">{{ number_format($stats['total_messages']) }}</p>
                </div>
            </div>
        </div>

        <!-- Unread Messages -->
        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-6 transition-shadow hover:shadow-lg dark:shadow-black/20">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 rounded-sm bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center">
                    <i data-lucide="inbox" class="w-6 h-6 text-amber-600 dark:text-amber-400"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Okunmamış Mesaj</p>
                    <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-0.5">{{ number_format($stats['unread_messages']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links & System Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-6">
            <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Hızlı Erişim</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.page-history') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">Sayfa Ziyaretlerini Görüntüle</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">Detaylı ziyaret loglarını inceleyin</p>
                </a>
                <a href="{{ route('admin.contact-messages') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">İletişim Mesajlarını Görüntüle</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">Gelen mesajları okuyun</p>
                </a>
            </div>
        </div>

        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-6">
            <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Sistem Bilgisi</h3>
            <dl class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <dt class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Laravel</dt>
                    <dd class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ app()->version() }}</dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <dt class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">PHP</dt>
                    <dd class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ PHP_VERSION }}</dd>
                </div>
                <div class="flex justify-between items-center py-2">
                    <dt class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Ortam</dt>
                    <dd class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ config('app.env') }}</dd>
                </div>
            </dl>
        </div>
    </div>
@endsection
