@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'error' => null,
])

@php
    $id = $id ?? $name ?? Str::random(8);
    $inputClass = 'w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#706f6c]/60 dark:placeholder:text-[#8F8F8B]/60 focus:outline-none focus:border-[#D62113] focus:ring-1 focus:ring-[#D62113] transition-colors';
    if ($error) $inputClass .= ' border-red-500 dark:border-red-500';
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $id }}" class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        id="{{ $id }}"
        @if($name) name="{{ $name }}" @endif
        @if(!$attributes->has('wire:model')) value="{{ $value ?? old($name) }}" @endif
        placeholder="{{ $placeholder }}"
        {{ $attributes->except('class')->merge(['class' => $inputClass]) }}
    />
    @if($error)
        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
