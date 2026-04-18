@extends('layouts.admin')

@section('title', __('messages.blog_analytics.page_title') . ' - ' . config('app.name'))
@section('page-title', __('messages.blog_analytics.page_title'))

@section('content')
    <x-admin.card class="p-6 mb-6" id="blog-analytics-overview" data-endpoint="{{ route('admin.analytics.overview.data') }}">
        <div class="flex flex-wrap items-end gap-3 mb-6">
            <div>
                <label for="analytics-date-from" class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">{{ __('messages.blog_analytics.date_from') }}</label>
                <input id="analytics-date-from" type="date" class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent text-sm px-3 py-2">
            </div>
            <div>
                <label for="analytics-date-to" class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">{{ __('messages.blog_analytics.date_to') }}</label>
                <input id="analytics-date-to" type="date" class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent text-sm px-3 py-2">
            </div>
            <label class="inline-flex items-center gap-2 text-xs text-[#706f6c] dark:text-[#8F8F8B]">
                <input id="analytics-include-bots" type="checkbox" class="rounded border-[#e3e3e0] dark:border-[#3E3E3A]">
                {{ __('messages.blog_analytics.include_bots') }}
            </label>
            <button id="analytics-refresh" type="button" class="px-4 py-2 text-xs font-semibold rounded-sm border border-[#D62113] text-[#D62113] hover:bg-[#D62113]/10 transition">
                {{ __('messages.blog_analytics.refresh') }}
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <x-admin.stat-card :label="__('messages.blog_analytics.total_views')" iconColor="red">
                <x-slot:icon><i data-lucide="eye" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-total-views">0</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.unique_visitors')" iconColor="emerald">
                <x-slot:icon><i data-lucide="users" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-unique-visitors">0</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.completed_read_pct')" iconColor="violet">
                <x-slot:icon><i data-lucide="check" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-completed-rate">0%</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.engaged_read_pct')" iconColor="amber">
                <x-slot:icon><i data-lucide="activity" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-engaged-rate">0%</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.returning_visitor_pct')" iconColor="emerald">
                <x-slot:icon><i data-lucide="refresh-cw" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-returning-rate">0%</span></x-slot:valueSlot>
            </x-admin.stat-card>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-4">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.blog_analytics.avg_active_read') }}</p>
                <p id="analytics-avg-active" class="text-xl font-semibold mt-1">0 {{ __('messages.blog_analytics.seconds_abbr') }}</p>
            </div>
            <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-4">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.blog_analytics.avg_total_time') }}</p>
                <p id="analytics-avg-total" class="text-xl font-semibold mt-1">0 {{ __('messages.blog_analytics.seconds_abbr') }}</p>
            </div>
            <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-4">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.blog_analytics.avg_scroll') }}</p>
                <p id="analytics-avg-scroll" class="text-xl font-semibold mt-1">0%</p>
            </div>
        </div>

        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#F4F4F2] dark:bg-[#161615]">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.trend_date') }}</th>
                        <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.trend_views') }}</th>
                        <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.trend_engaged') }}</th>
                        <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.trend_completed') }}</th>
                    </tr>
                </thead>
                <tbody id="analytics-trend-body"></tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
            <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#F4F4F2] dark:bg-[#161615]">
                        <tr>
                            <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.source') }}</th>
                            <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.trend_views') }}</th>
                            <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.unique') }}</th>
                        </tr>
                    </thead>
                    <tbody id="analytics-sources-body"></tbody>
                </table>
            </div>
            <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#F4F4F2] dark:bg-[#161615]">
                        <tr>
                            <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.device') }}</th>
                            <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.trend_views') }}</th>
                            <th class="text-left px-4 py-3">{{ __('messages.blog_analytics.unique') }}</th>
                        </tr>
                    </thead>
                    <tbody id="analytics-devices-body"></tbody>
                </table>
            </div>
        </div>
    </x-admin.card>
@endsection

@push('scripts')
    <script>
        window.blogAnalyticsI18n = @json([
            'seconds_abbr' => __('messages.blog_analytics.seconds_abbr'),
            'other_bucket' => __('messages.blog_analytics.other_bucket'),
            'load_error' => __('messages.blog_analytics.load_error'),
            'number_locale' => app()->getLocale() === 'tr' ? 'tr-TR' : 'en-US',
        ]);
    </script>
    @vite('resources/js/pages/admin/blog-analytics-overview.js')
@endpush
