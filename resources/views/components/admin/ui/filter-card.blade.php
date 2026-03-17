@props([
    'title' => 'Filtreler',
    'action' => null,
    'method' => 'get',
])

<x-admin.card class="p-6 mb-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">{{ $title }}</h3>
        @if(isset($actions))
            <div class="flex items-center gap-2">{{ $actions }}</div>
        @endif
    </div>
    <form method="{{ $method }}" action="{{ $action }}" {{ $attributes->merge(['class' => 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4']) }}>
        {{ $slot }}
    </form>
</x-admin.card>
