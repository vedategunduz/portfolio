@extends('layouts.app')

@section('content')
<x-sections.background>
    @php
        $cover = $post->cover_image;
        $coverUrl = $cover
            ? (\Illuminate\Support\Str::startsWith($cover, ['http://', 'https://']) ? $cover : asset('storage/' . ltrim($cover, '/')))
            : null;

        $gallery = $post->galleryImages->pluck('image_path')->filter()->values();
    @endphp

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="mb-8">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm md:text-base font-semibold text-[#D62113] hover:text-[#B51C10] transition">
                <i data-lucide="arrow-left" class="w-4 h-4 md:w-5 md:h-5"></i>
                {{ __('Bloğa Dön') }}
            </a>
        </div>

        <article class="overflow-hidden rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-black/20 backdrop-blur-sm shadow-xs">
            @if ($coverUrl)
                <div class="relative">
                    <img src="{{ $coverUrl }}" alt="{{ $post->translated_title }}" class="w-full h-64 md:h-96 object-cover" loading="lazy">
                </div>
            @endif

            <div class="p-6 md:p-10">
                <header class="mb-8">
                    <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">{{ $post->translated_title }}</h1>

                    <div class="mt-5 flex flex-wrap items-center gap-4 text-xs md:text-sm text-[#706f6c] dark:text-[#D4D3D0]">
                        <span>{{ $post->user->name ?? 'Admin' }}</span>
                        <span>•</span>
                        <span>{{ $post->published_at?->format('d.m.Y') ?? '-' }}</span>
                        <span>•</span>
                        <span>{{ ceil(str_word_count(strip_tags($post->translated_content ?? '')) / 200) }} {{ __('dk okuma') }}</span>
                    </div>
                </header>

                <div class="prose prose-neutral dark:prose-invert prose-lg max-w-none leading-relaxed text-[#1b1b18] dark:text-[#EDEDEC]">
                    {!! $post->translated_content !!}
                </div>

                @if ($gallery->isNotEmpty())
                    <section class="mt-12 pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-5">{{ __('Galeri') }}</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($gallery as $image)
                                @php
                                    $imageUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                                        ? $image
                                        : asset('storage/' . ltrim($image, '/'));
                                @endphp
                                <a href="{{ $imageUrl }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113] hover:shadow-sm hover:shadow-[#D62113]/15 transition-all duration-300">
                                    <img src="{{ $imageUrl }}" alt="{{ $post->translated_title }}" class="w-full h-52 object-cover hover:scale-105 transition-transform duration-300" loading="lazy">
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </article>
    </section>
</x-sections.background>
@endsection

@push('scripts')
<script>
    window.__BLOG_ANALYTICS_CONTEXT = {
        enabled: true,
        postId: {{ $post->id }},
        postSlug: @json($post->translated_slug),
        endpoints: {
            start: @json(url('/api/analytics/pageview/start')),
            heartbeat: @json(url('/api/analytics/pageview/heartbeat')),
            interaction: @json(url('/api/analytics/pageview/interaction')),
            end: @json(url('/api/analytics/pageview/end')),
        },
    };
</script>
@endpush
