@props([])

<div {{ $attributes->merge(['class' => 'px-4 sm:px-6 py-4 bg-[#fafafa] dark:bg-[#1e1e1e] border-t border-[#e5e5e5] dark:border-[#333333]']) }}>
    {{ $slot }}
</div>
