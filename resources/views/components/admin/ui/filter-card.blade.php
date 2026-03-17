@props([
    'title' => 'Filtreler',
    'action' => null,
    'method' => 'get',
])

@php
    $activeFilterCount = collect(request()->query())
        ->except(['page'])
        ->filter(function ($value) {
            if (is_array($value)) {
                return collect($value)->contains(fn ($item) => $item !== null && $item !== '');
            }

            return $value !== null && $value !== '';
        })
        ->count();
@endphp

<x-admin.card class="mb-6">
    <details class="group" {{ $activeFilterCount > 0 ? 'open' : '' }}>
        <summary class="list-none cursor-pointer px-6 py-4 [&::-webkit-details-marker]:hidden">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">{{ $title }}</h3>
                    @if($activeFilterCount > 0)
                        <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ $activeFilterCount }} aktif</span>
                    @endif
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#706f6c] dark:text-[#8F8F8B] transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </summary>

        <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] p-6 pt-4">
            @if(isset($actions))
                <div class="mb-4 flex items-center justify-end gap-2">{{ $actions }}</div>
            @endif

            <form method="{{ $method }}" action="{{ $action }}" {{ $attributes->merge(['class' => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4']) }}>
                {{ $slot }}
            </form>
        </div>
    </details>
</x-admin.card>
