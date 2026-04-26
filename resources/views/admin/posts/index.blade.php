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
    $localeFilterOptions = ['' => __('messages.blog_admin.filter_locale_all')];
    foreach ($supportedLocales ?? [] as $loc) {
        $localeFilterOptions[$loc] = strtoupper($loc);
    }
@endphp

@section('content')

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
        <x-admin.form.select
            label="{{ __('messages.blog_admin.filter_locale_label') }}"
            name="locale"
            :options="$localeFilterOptions"
            :selected="request('locale')"
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
        <x-admin.ui.table-wrapper tableClass="min-w-full text-sm">
            <x-slot:header>
                <tr>
                    <x-admin.ui.table-th>{{ __('messages.blog_admin.title') }}</x-admin.ui.table-th>
                    <x-admin.ui.table-th>{{ __('messages.blog_admin.status') }}</x-admin.ui.table-th>
                    <x-admin.ui.table-th>{{ __('messages.blog_admin.media') }}</x-admin.ui.table-th>
                    <x-admin.ui.table-th>{{ __('messages.blog_admin.featured') }}</x-admin.ui.table-th>
                    <x-admin.ui.table-th>{{ __('messages.blog_admin.published_at') }}</x-admin.ui.table-th>
                    <x-admin.ui.table-th>{{ __('messages.blog_admin.updated_at') }}</x-admin.ui.table-th>
                    <x-admin.ui.table-th>{{ __('messages.blog_admin.locales') }}</x-admin.ui.table-th>
                    <x-admin.ui.table-th class="text-right">{{ __('messages.blog_admin.actions') }}</x-admin.ui.table-th>
                </tr>
            </x-slot:header>
            @forelse($posts as $index => $post)
                <x-admin.ui.table-row :zebra="$index % 2 === 1">
                    <x-admin.ui.table-td class="align-top">
                        @if($post->translated_slug)
                            <a
                                href="{{ route('blog.show', $post->translated_slug) }}"
                                class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#D62113] transition-colors"
                                title="{{ __('messages.blog_admin.view_post') }}"
                            >
                                {{ $post->translated_title ?? '-' }}
                            </a>
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">
                                <a
                                    href="{{ route('blog.show', $post->translated_slug) }}"
                                    class="hover:text-[#D62113] transition-colors"
                                >
                                    {{ $post->translated_slug }}
                                </a>
                            </p>
                        @else
                            <p class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $post->translated_title ?? '-' }}</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">-</p>
                        @endif
                    </x-admin.ui.table-td>
                    <x-admin.ui.table-td class="align-top">
                        @if($post->published && $post->published_at && $post->published_at->isFuture())
                            <x-admin.ui.badge variant="info">{{ __('messages.blog_admin.status_scheduled') }}</x-admin.ui.badge>
                        @elseif($post->published)
                            <x-admin.ui.badge variant="success">{{ __('messages.blog_admin.status_published') }}</x-admin.ui.badge>
                        @else
                            <x-admin.ui.badge variant="warning">{{ __('messages.blog_admin.status_draft') }}</x-admin.ui.badge>
                        @endif
                    </x-admin.ui.table-td>
                    <x-admin.ui.table-td variant="secondary" class="align-top">
                        <span class="block">{{ $post->cover_image ? __('messages.blog_admin.media_cover_yes') : __('messages.blog_admin.media_cover_no') }}</span>
                        <span class="block mt-0.5">
                            @if($post->gallery_images_count === 0)
                                {{ __('messages.blog_admin.media_gallery_none') }}
                            @else
                                {{ __('messages.blog_admin.media_gallery_some', ['count' => $post->gallery_images_count]) }}
                            @endif
                        </span>
                    </x-admin.ui.table-td>
                    <x-admin.ui.table-td class="align-top">
                        @if($post->is_featured)
                            <x-admin.ui.badge variant="success">{{ __('messages.blog_admin.yes') }}</x-admin.ui.badge>
                        @else
                            <span class="text-[#6b7280] dark:text-[#9ca3af]">{{ __('messages.blog_admin.no') }}</span>
                        @endif
                    </x-admin.ui.table-td>
                    <x-admin.ui.table-td class="align-top whitespace-nowrap">{{ $post->published_at?->format('d.m.Y H:i') ?? '-' }}</x-admin.ui.table-td>
                    <x-admin.ui.table-td variant="secondary" class="align-top whitespace-nowrap">{{ $post->updated_at?->timezone(config('app.timezone'))->format('d.m.Y H:i') ?? '-' }}</x-admin.ui.table-td>
                    <x-admin.ui.table-td class="align-top">{{ $post->translations->pluck('locale')->map(fn ($l) => strtoupper($l))->implode(', ') ?: '-' }}</x-admin.ui.table-td>
                    <x-admin.ui.table-td class="align-top">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="px-3 py-1.5 rounded-sm text-xs border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 hover:text-[#D62113] transition-colors">
                                {{ __('messages.blog_admin.edit') }}
                            </a>
                            <form
                                action="{{ route('admin.posts.destroy', $post) }}"
                                method="POST"
                                class="js-post-delete-form"
                                data-confirm-message="{{ __('messages.blog_admin.confirm_delete') }}"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-sm text-xs border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    {{ __('messages.blog_admin.delete') }}
                                </button>
                            </form>
                        </div>
                    </x-admin.ui.table-td>
                </x-admin.ui.table-row>
            @empty
                <x-admin.ui.table-row>
                    <x-admin.ui.table-td colspan="8" variant="secondary" class="py-10 text-center">
                        {{ __('messages.blog_admin.no_posts') }}
                    </x-admin.ui.table-td>
                </x-admin.ui.table-row>
            @endforelse
        </x-admin.ui.table-wrapper>
    </x-admin.card>

    <x-admin.ui.pagination :paginator="$posts" class="mt-6" />
@endsection

@push('scripts')
    <script>
        (() => {
            const forms = document.querySelectorAll('.js-post-delete-form');
            if (!forms.length) return;

            const buildConfirmModal = () => {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 z-[70] hidden items-center justify-center bg-black/50 p-4';
                modal.innerHTML = `
                    <div class="w-full max-w-md rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-6 shadow-xl">
                        <h3 class="text-base font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">${window.translations?.['dialog.confirm_title'] || 'Emin misiniz?'}</h3>
                        <p class="mt-2 text-sm text-[#706f6c] dark:text-[#8F8F8B]" data-confirm-message></p>
                        <div class="mt-5 flex items-center justify-end gap-2">
                            <button type="button" data-confirm-cancel class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                                ${window.translations?.['dialog.cancel_button'] || 'Vazgeç'}
                            </button>
                            <button type="button" data-confirm-approve class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-sm bg-[#D62113] text-white hover:bg-[#b81a0f]">
                                ${window.translations?.['dialog.confirm_button'] || 'Evet, Onaylıyorum'}
                            </button>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
                return modal;
            };

            const modal = buildConfirmModal();
            const messageEl = modal.querySelector('[data-confirm-message]');
            const cancelBtn = modal.querySelector('[data-confirm-cancel]');
            const approveBtn = modal.querySelector('[data-confirm-approve]');
            let resolver = null;

            const closeModal = (value) => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (resolver) resolver(value);
                resolver = null;
            };

            cancelBtn?.addEventListener('click', () => closeModal(false));
            approveBtn?.addEventListener('click', () => closeModal(true));
            modal.addEventListener('click', (event) => {
                if (event.target === modal) closeModal(false);
            });

            const confirmDelete = (message) => {
                if (messageEl) messageEl.textContent = message;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                return new Promise((resolve) => {
                    resolver = resolve;
                });
            };

            forms.forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const message = form.dataset.confirmMessage || 'Bu yazıyı silmek istediğinize emin misiniz?';
                    const confirmed = await confirmDelete(message);

                    if (confirmed) {
                        form.submit();
                    }
                });
            });
        })();
    </script>
@endpush
