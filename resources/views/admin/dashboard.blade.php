@extends('layouts.admin')

@section('title', 'Admin Dashboard - ' . config('app.name'))
@section('page-title', 'Admin Panel')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Visits -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Toplam Ziyaret
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($stats['total_visits']) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Unique Visitors -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Benzersiz Ziyaretçi
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($stats['unique_visitors']) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Total Messages -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Toplam Mesaj
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($stats['total_messages']) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Unread Messages -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                    Okunmamış Mesaj
                                </dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($stats['unread_messages']) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Hızlı Erişim</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.page-history') }}" class="block p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="font-medium text-gray-900 dark:text-white">Sayfa Ziyaretlerini Görüntüle</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Detaylı ziyaret loglarını inceleyin</div>
                        </a>
                        <a href="{{ route('admin.contact-messages') }}" class="block p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="font-medium text-gray-900 dark:text-white">İletişim Mesajlarını Görüntüle</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Gelen mesajları okuyun</div>
                        </a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Sistem Bilgisi</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Laravel Versiyon:</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ app()->version() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">PHP Versiyon:</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ PHP_VERSION }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Ortam:</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ config('app.env') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
@endsection
