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
    aria-label="Sayfalama"
    {{ $attributes->merge(['class' => 'px-4 sm:px-6 py-2 bg-[#fafafa] dark:bg-[#1e1e1e] border-t border-[#e5e5e5] dark:border-[#333333]']) }}
>
    <div class="flex flex-row flex-nowrap items-center justify-between gap-1 sm:flex-wrap sm:justify-center sm:gap-1.5">
        {{-- Önceki --}}
        <div class="order-2 sm:order-1">
            @if ($paginator->onFirstPage())
                <span
                    class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#a3a3a0] dark:text-[#525250] cursor-not-allowed"
                >
                    <i data-lucide="chevron-left" class="w-3.5 h-3.5 shrink-0"></i>
                    Önceki
                </span>
            @else
                @if ($wire)
                    <button
                        type="button"
                        wire:click="previousPage"
                        class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                    >
                        <i data-lucide="chevron-left" class="w-3.5 h-3.5 shrink-0"></i>
                        Önceki
                    </button>
                @else
                    <a
                        href="{{ url($paginator->previousPageUrl()) }}"
                        class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                    >
                        <i data-lucide="chevron-left" class="w-3.5 h-3.5 shrink-0"></i>
                        Önceki
                    </a>
                @endif
            @endif
        </div>

        {{-- Sayfa numaraları --}}
        <div class="order-1 flex min-w-0 shrink flex-wrap justify-center gap-0.5 sm:order-2">
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

        {{-- Sonraki --}}
        <div class="order-3">
            @if ($paginator->hasMorePages())
                @if ($wire)
                    <button
                        type="button"
                        wire:click="nextPage"
                        class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                    >
                        Sonraki
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0"></i>
                    </button>
                @else
                    <a
                        href="{{ url($paginator->nextPageUrl()) }}"
                        class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] transition-colors hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
                    >
                        Sonraki
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0"></i>
                    </a>
                @endif
            @else
                <span
                    class="inline-flex min-w-7 h-7 items-center justify-center gap-1 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-2 py-1 text-xs font-medium text-[#a3a3a0] dark:text-[#525250] cursor-not-allowed"
                >
                    Sonraki
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0"></i>
                </span>
            @endif
        </div>
    </div>

    @if ($paginator->total() > 0)
        <div class="mt-1 flex flex-col gap-0.5 text-[11px] text-[#9ca3af] dark:text-[#6b7280] sm:flex-row sm:justify-between sm:items-center">
            <span>{{ $paginator->firstItem() }} – {{ $paginator->lastItem() }} arası</span>
            <span>toplam {{ number_format($paginator->total()) }} kayıt</span>
        </div>
    @endif
</nav>
@endif
