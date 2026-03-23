@extends('layouts.admin')

@section('title', __('messages.blog_admin.posts') . __('messages.admin.title_suffix') . config('app.name'))
@section('page-title', __('messages.blog_admin.posts'))

@php
    $postStatusFilterOptions = [
        '' => __('messages.blog_admin.filter_status_all'),
        'published' => __('messages.blog_admin.status_published'),
        'draft' => __('messages.blog_admin.status_draft'),
        'featured' => __('messages.blog_admin.featured'),
    ];
@endphp

@section('content')
    @if(session('success'))
        <div class="mb-6 rounded-sm border border-emerald-200 dark:border-emerald-800 bg-emerald-50/90 dark:bg-emerald-900/20 p-4">
            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between gap-3">
        <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.blog_admin.total_posts', ['count' => $posts->total()]) }}</p>
        <a href="{{ route('admin.posts.create') }}" class="px-4 py-2 rounded-sm text-xs font-medium bg-[#D62113] text-white hover:bg-[#b81a0f] transition-colors">
            {{ __('messages.blog_admin.create_new') }}
        </a>
    </div>

    <x-admin.ui.filter-card title="{{ __('messages.filters') }}" :action="route('admin.posts.index')" method="get">
        <x-admin.form.input
            class="sm:col-span-2 lg:col-span-2"
            label="{{ __('messages.blog_admin.filter_search') }}"
            name="search"
            value="{{ request('search') }}"
            placeholder="{{ __('messages.blog_admin.filter_search_placeholder') }}"
        />
        <x-admin.form.select
            label="{{ __('messages.blog_admin.status') }}"
            name="status"
            :options="$postStatusFilterOptions"
            :selected="request('status')"
        />
        <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-4">
            <x-admin.ui.button variant="primary" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                {{ __('messages.filter') }}
            </x-admin.ui.button>
            <x-admin.ui.button variant="secondary" :href="route('admin.posts.index')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ __('messages.clear') }}
            </x-admin.ui.button>
        </div>
    </x-admin.ui.filter-card>

    <x-admin.card class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#f8f8f7] dark:bg-[#111110]">
                        <th class="text-left px-4 py-3 font-semibold">{{ __('messages.blog_admin.title') }}</th>
                        <th class="text-left px-4 py-3 font-semibold">{{ __('messages.blog_admin.status') }}</th>
                        <th class="text-left px-4 py-3 font-semibold">{{ __('messages.blog_admin.featured') }}</th>
                        <th class="text-left px-4 py-3 font-semibold">{{ __('messages.blog_admin.published_at') }}</th>
                        <th class="text-left px-4 py-3 font-semibold">{{ __('messages.blog_admin.locales') }}</th>
                        <th class="text-right px-4 py-3 font-semibold">{{ __('messages.blog_admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]/70">
                            <td class="px-4 py-3 align-top">
                                <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $post->translated_title ?? '-' }}</p>
                                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">{{ $post->translated_slug ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 align-top">
                                @if($post->published)
                                    <span class="inline-flex px-2 py-1 rounded-sm text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">{{ __('messages.blog_admin.status_published') }}</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-sm text-xs font-medium bg-amber-500/20 text-amber-700 dark:bg-amber-500/25 dark:text-amber-400">{{ __('messages.blog_admin.status_draft') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">{{ $post->is_featured ? __('messages.blog_admin.yes') : __('messages.blog_admin.no') }}</td>
                            <td class="px-4 py-3 align-top">{{ $post->published_at?->format('d.m.Y H:i') ?? '-' }}</td>
                            <td class="px-4 py-3 align-top">{{ $post->translations->pluck('locale')->map(fn ($l) => strtoupper($l))->implode(', ') ?: '-' }}</td>
                            <td class="px-4 py-3 align-top">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="px-3 py-1.5 rounded-sm text-xs border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 hover:text-[#D62113] transition-colors">
                                        {{ __('messages.blog_admin.edit') }}
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('{{ __('messages.blog_admin.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-sm text-xs border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            {{ __('messages.blog_admin.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.blog_admin.no_posts') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <x-admin.ui.pagination :paginator="$posts" class="mt-6" />
@endsection
