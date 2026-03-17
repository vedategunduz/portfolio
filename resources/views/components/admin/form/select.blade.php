@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'options' => [], // ['' => 'Tümü', 'value' => 'Label']
    'selected' => null,
    'placeholder' => null,
])

@php
    $id = $id ?? $name ?? Str::random(8);
    $selectClass = 'w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:border-[#D62113] focus:ring-1 focus:ring-[#D62113] transition-colors';
@endphp

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $id }}" class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">{{ $label }}</label>
    @endif
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $attributes->except('class')->merge(['class' => $selectClass]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $val => $optLabel)
            <option value="{{ $val }}" {{ (string) $selected === (string) $val ? 'selected' : '' }}>{{ $optLabel }}</option>
        @endforeach
        {{ $slot }}
    </select>
</div>
