@props([
    'post',
    'imageHeight' => 'h-52',
])

@php
    $cover = $post->cover_image;
    $coverUrl = $cover
        ? (\Illuminate\Support\Str::startsWith($cover, ['http://', 'https://']) ? $cover : asset('storage/' . ltrim($cover, '/')))
        : null;
@endphp

<article class="group overflow-hidden rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-black/20 backdrop-blur-sm shadow-xs hover:border-[#D62113] hover:shadow-md hover:shadow-[#D62113]/15 transition-all duration-300">
    <a href="{{ route('blog.show', $post->translated_slug) }}" class="block">
        <div class="relative">
            @if ($coverUrl)
                <img src="{{ $coverUrl }}" alt="{{ $post->translated_title }}" class="{{ $imageHeight }} w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
            @else
                <div class="{{ $imageHeight }} w-full bg-linear-to-br from-[#D62113]/15 to-[#FF6B6B]/20 dark:from-[#D62113]/20 dark:to-[#FF6B6B]/10"></div>
            @endif
        </div>
    </a>

    <div class="p-6">
        <div class="flex items-center gap-4 text-xs md:text-sm text-[#706f6c] dark:text-[#D4D3D0] mb-3">
            <span>{{ $post->user->name ?? 'Admin' }}</span>
            <span>•</span>
            <span>{{ $post->published_at?->format('d.m.Y') ?? '-' }}</span>
        </div>

        <h3 class="text-xl font-bold leading-tight text-[#1b1b18] dark:text-[#EDEDEC]">
            <a href="{{ route('blog.show', $post->translated_slug) }}" class="group-hover:text-[#D62113] transition-colors">
                {{ $post->translated_title }}
            </a>
        </h3>

        <p class="mt-3 text-sm md:text-base text-[#706f6c] dark:text-[#D4D3D0] line-clamp-3">
            {{ $post->translated_excerpt }}
        </p>

        <a href="{{ route('blog.show', $post->translated_slug) }}" class="mt-5 inline-block text-sm font-semibold text-[#D62113] hover:text-[#B51C10] transition">
            {{ __('Devamını Oku') }}
        </a>
    </div>
</article>
