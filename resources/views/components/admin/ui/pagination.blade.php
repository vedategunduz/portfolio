@props([
    'paginator',
    'wire' => false,
])

@if ($paginator && $paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $onEachSide = 1;
    $window = $onEachSide * 2;
    $slots = [];
    if ($last <= $window + 3) {
        $slots = range(1, $last);
    } else {
        $slots[] = 1;
        if ($current > 2 + $onEachSide) {
            $slots[] = '...';
        }
        $start = max(2, $current - $onEachSide);
        $end = min($last - 1, $current + $onEachSide);
        for ($i = $start; $i <= $end; $i++) {
            if (!in_array($i, $slots)) {
                $slots[] = $i;
            }
        }
        if ($current < $last - 1 - $onEachSide) {
            $slots[] = '...';
        }
        if ($last > 1) {
            $slots[] = $last;
        }
    }
@endphp

<nav
    role="navigation"
    aria-label="{{ __('messages.pagination.aria_label') }}"
    {{ $attributes->merge(['class' => 'px-4 sm:px-6 py-3 bg-[#fafafa] dark:bg-[#1e1e1e] border-t border-[#e5e5e5] dark:border-[#333333]']) }}
>
    <div class="hidden sm:flex items-center justify-center gap-1.5">
            <div>
                @if ($paginator->onFirstPage())
                    <span
                        class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#a3a3a0] dark:text-[#525250] cursor-not-allowed"
                    >
                        <i data-lucide="chevron-left" class="w-3.5 h-3.5 shrink-0"></i>
                        {{ __('messages.pagination.previous') }}
                    </span>
                @else
                    @if ($wire)
                        <button
                            type="button"
                            wire:click="previousPage"
                            class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                        >
                            <i data-lucide="chevron-left" class="w-3.5 h-3.5 shrink-0"></i>
                            {{ __('messages.pagination.previous') }}
                        </button>
                    @else
                        <a
                            href="{{ url($paginator->previousPageUrl()) }}"
                            class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                        >
                            <i data-lucide="chevron-left" class="w-3.5 h-3.5 shrink-0"></i>
                            {{ __('messages.pagination.previous') }}
                        </a>
                    @endif
                @endif
            </div>

            <div class="flex items-center gap-0.5">
                @foreach ($slots as $page)
                    @if ($page === '...')
                        <span class="inline-flex h-7 min-w-7 items-center justify-center text-xs text-[#9ca3af] dark:text-[#6b7280]">…</span>
                    @else
                        @if ($page === $current)
                            <span
                                aria-current="page"
                                class="inline-flex h-7 min-w-7 items-center justify-center rounded-sm bg-[#D62113] text-xs font-medium text-white"
                            >
                                {{ $page }}
                            </span>
                        @else
                            @if ($wire)
                                <button
                                    type="button"
                                    wire:click="gotoPage({{ $page }})"
                                    class="inline-flex h-7 min-w-7 items-center justify-center rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                                >
                                    {{ $page }}
                                </button>
                            @else
                                <a
                                    href="{{ url($paginator->url($page)) }}"
                                    class="inline-flex h-7 min-w-7 items-center justify-center rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                                >
                                    {{ $page }}
                                </a>
                            @endif
                        @endif
                    @endif
                @endforeach
            </div>

            <div>
                @if ($paginator->hasMorePages())
                    @if ($wire)
                        <button
                            type="button"
                            wire:click="nextPage"
                            class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                        >
                            {{ __('messages.pagination.next') }}
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0"></i>
                        </button>
                    @else
                        <a
                            href="{{ url($paginator->nextPageUrl()) }}"
                            class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                        >
                            {{ __('messages.pagination.next') }}
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0"></i>
                        </a>
                    @endif
                @else
                    <span
                        class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#a3a3a0] dark:text-[#525250] cursor-not-allowed"
                    >
                        {{ __('messages.pagination.next') }}
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0"></i>
                    </span>
                @endif
            </div>
    </div>

    <div class="mt-2 flex items-center justify-center gap-2 sm:hidden">
        <div>
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-8 items-center justify-center rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 text-xs font-medium text-[#a3a3a0] dark:text-[#525250] cursor-not-allowed">
                    {{ __('messages.pagination.previous') }}
                </span>
            @else
                @if ($wire)
                    <button type="button" wire:click="previousPage" class="inline-flex h-8 items-center justify-center rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A]">
                        {{ __('messages.pagination.previous') }}
                    </button>
                @else
                    <a href="{{ url($paginator->previousPageUrl()) }}" class="inline-flex h-8 items-center justify-center rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A]">
                        {{ __('messages.pagination.previous') }}
                    </a>
                @endif
            @endif
        </div>

        <span class="text-xs font-medium text-[#6b7280] dark:text-[#9ca3af]">
            {{ str_replace(['{current}', '{last}'], [$current, $last], __('messages.pagination.page_of')) }}
        </span>

        <div>
            @if ($paginator->hasMorePages())
                @if ($wire)
                    <button type="button" wire:click="nextPage" class="inline-flex h-8 items-center justify-center rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A]">
                        {{ __('messages.pagination.next') }}
                    </button>
                @else
                    <a href="{{ url($paginator->nextPageUrl()) }}" class="inline-flex h-8 items-center justify-center rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A]">
                        {{ __('messages.pagination.next') }}
                    </a>
                @endif
            @else
                <span class="inline-flex h-8 items-center justify-center rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 text-xs font-medium text-[#a3a3a0] dark:text-[#525250] cursor-not-allowed">
                    {{ __('messages.pagination.next') }}
                </span>
            @endif
        </div>
    </div>

    @if ($paginator->total() > 0)
        <div class="mt-2 text-center text-xs text-[#6b7280] dark:text-[#9ca3af]">
            {{ str_replace(['{from}', '{to}', '{total}'], [$paginator->firstItem(), $paginator->lastItem(), number_format($paginator->total())], __('messages.pagination.total_records')) }}
        </div>
    @endif
</nav>
@endif
