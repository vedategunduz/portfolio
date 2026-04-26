@props([
    'stickyHeader' => true,
    'tableClass' => 'min-w-full text-sm',
    'bodyId' => null,
    'bodyClass' => 'divide-y divide-[#e5e5e5] dark:divide-[#333333]',
])

<div {{ $attributes->merge(['class' => 'overflow-x-auto']) }}>
    <table class="{{ $tableClass }}">
        @if(isset($header))
            <thead class="{{ $stickyHeader ? 'sticky top-0 z-1' : '' }} bg-[#fafafa] dark:bg-[#1a1a18] border-b border-[#e5e5e5] dark:border-[#333333]">
                {{ $header }}
            </thead>
        @endif
        <tbody @if($bodyId) id="{{ $bodyId }}" @endif class="{{ $bodyClass }}">
            {{ $slot }}
        </tbody>
    </table>
</div>
