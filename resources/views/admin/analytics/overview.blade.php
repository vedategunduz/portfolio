@extends('layouts.admin')

@section('title', __('messages.blog_analytics.page_title') . ' - ' . config('app.name'))
@section('page-title', __('messages.blog_analytics.page_title'))

@section('content')
    <section class="space-y-6" id="blog-analytics-overview" data-endpoint="{{ route('admin.analytics.overview.data') }}">
        <x-admin.card>
            <div class="px-4 py-3 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h2 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">
                    {{ __('messages.blog_analytics.filters_title') }}
                </h2>
            </div>
            <div class="p-4">
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <label for="analytics-date-from" class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">{{ __('messages.blog_analytics.date_from') }}</label>
                        <input id="analytics-date-from" type="date" class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent text-sm px-3 py-2">
                    </div>
                    <div>
                        <label for="analytics-date-to" class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">{{ __('messages.blog_analytics.date_to') }}</label>
                        <input id="analytics-date-to" type="date" class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-transparent text-sm px-3 py-2">
                    </div>
                    <label class="inline-flex items-center gap-2 text-xs text-[#706f6c] dark:text-[#8F8F8B] pb-2">
                        <input id="analytics-include-bots" type="checkbox" class="rounded border-[#e3e3e0] dark:border-[#3E3E3A]">
                        {{ __('messages.blog_analytics.include_bots') }}
                    </label>
                    <button id="analytics-refresh" type="button" class="px-4 py-2 text-xs font-semibold rounded-sm border border-[#D62113] text-[#D62113] hover:bg-[#D62113]/10 transition">
                        {{ __('messages.blog_analytics.refresh') }}
                    </button>
                </div>
            </div>
        </x-admin.card>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <x-admin.stat-card :label="__('messages.blog_analytics.total_views')" iconColor="red" compact>
                <x-slot:icon><i data-lucide="eye" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-total-views">0</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.unique_visitors')" iconColor="emerald" compact>
                <x-slot:icon><i data-lucide="users" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-unique-visitors">0</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.completed_read_pct')" iconColor="violet" compact>
                <x-slot:icon><i data-lucide="check" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-completed-rate">0%</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.engaged_read_pct')" iconColor="amber" compact>
                <x-slot:icon><i data-lucide="activity" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot><span id="analytics-engaged-rate">0%</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.returning_visitor_rate_unique')" iconColor="emerald" compact>
                <x-slot:icon><i data-lucide="refresh-cw" class="w-6 h-6"></i></x-slot:icon>
                <x-slot:valueSlot>
                    <span id="analytics-returning-rate">0%</span>
                    <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] mt-1">
                        <span id="analytics-returning-visitors">0</span>
                        {{ __('messages.blog_analytics.returning_visitors_suffix') }}
                    </p>
                </x-slot:valueSlot>
            </x-admin.stat-card>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <x-admin.stat-card :label="__('messages.blog_analytics.avg_active_read')" compact>
                <x-slot:valueSlot><span id="analytics-avg-active">0 {{ __('messages.blog_analytics.seconds_abbr') }}</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.avg_total_time')" compact>
                <x-slot:valueSlot><span id="analytics-avg-total">0 {{ __('messages.blog_analytics.seconds_abbr') }}</span></x-slot:valueSlot>
            </x-admin.stat-card>
            <x-admin.stat-card :label="__('messages.blog_analytics.avg_scroll')" compact>
                <x-slot:valueSlot><span id="analytics-avg-scroll">0%</span></x-slot:valueSlot>
            </x-admin.stat-card>
        </div>

        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] p-4">
            <div class="flex items-center justify-between gap-3 mb-3">
                <h2 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                    {{ __('messages.blog_analytics.metric_guide_title') }}
                </h2>
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">
                    {{ __('messages.blog_analytics.metric_guide_scope') }}
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="rounded-sm bg-[#F4F4F2] dark:bg-[#161615] p-3">
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_analytics.completed_read_pct') }}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">{{ __('messages.blog_analytics.metric_help_completed') }}</p>
                </div>
                <div class="rounded-sm bg-[#F4F4F2] dark:bg-[#161615] p-3">
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_analytics.engaged_read_pct') }}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">{{ __('messages.blog_analytics.metric_help_engaged') }}</p>
                </div>
                <div class="rounded-sm bg-[#F4F4F2] dark:bg-[#161615] p-3">
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_analytics.returning_visitor_rate_unique') }}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">{{ __('messages.blog_analytics.metric_help_returning') }}</p>
                </div>
                <div class="rounded-sm bg-[#F4F4F2] dark:bg-[#161615] p-3">
                    <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_analytics.avg_active_read') }}</p>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">{{ __('messages.blog_analytics.metric_help_active_time') }}</p>
                </div>
            </div>
        </div>

        <x-admin.card class="overflow-hidden">
            <x-admin.ui.table-wrapper tableClass="min-w-full text-sm" :sticky-header="false" body-id="analytics-trend-body">
                <x-slot:header>
                    <tr>
                        <x-admin.ui.table-th>{{ __('messages.blog_analytics.trend_date') }}</x-admin.ui.table-th>
                        <x-admin.ui.table-th>{{ __('messages.blog_analytics.trend_views') }}</x-admin.ui.table-th>
                        <x-admin.ui.table-th>{{ __('messages.blog_analytics.trend_engaged') }}</x-admin.ui.table-th>
                        <x-admin.ui.table-th>{{ __('messages.blog_analytics.trend_completed') }}</x-admin.ui.table-th>
                    </tr>
                </x-slot:header>
            </x-admin.ui.table-wrapper>
        </x-admin.card>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
            <x-admin.card class="overflow-hidden">
                <x-admin.ui.table-wrapper tableClass="min-w-full text-sm" :sticky-header="false" body-id="analytics-sources-body">
                    <x-slot:header>
                        <tr>
                            <x-admin.ui.table-th>{{ __('messages.blog_analytics.source') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th>{{ __('messages.blog_analytics.trend_views') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th>{{ __('messages.blog_analytics.unique') }}</x-admin.ui.table-th>
                        </tr>
                    </x-slot:header>
                </x-admin.ui.table-wrapper>
            </x-admin.card>
            <x-admin.card class="overflow-hidden">
                <x-admin.ui.table-wrapper tableClass="min-w-full text-sm" :sticky-header="false" body-id="analytics-devices-body">
                    <x-slot:header>
                        <tr>
                            <x-admin.ui.table-th>{{ __('messages.blog_analytics.device') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th>{{ __('messages.blog_analytics.trend_views') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th>{{ __('messages.blog_analytics.unique') }}</x-admin.ui.table-th>
                        </tr>
                    </x-slot:header>
                </x-admin.ui.table-wrapper>
            </x-admin.card>
        </div>
    </section>
@endsection

@push('scripts')
    @php
        $blogAnalyticsI18n = [
            'seconds_abbr' => __('messages.blog_analytics.seconds_abbr'),
            'other_bucket' => __('messages.blog_analytics.other_bucket'),
            'load_error' => __('messages.blog_analytics.load_error'),
            'number_locale' => app()->getLocale() === 'tr' ? 'tr-TR' : 'en-US',
        ];
    @endphp
    <script>
        window.blogAnalyticsI18n = @json($blogAnalyticsI18n);
    </script>
    @vite('resources/js/pages/admin/blog-analytics-overview.js')
@endpush
