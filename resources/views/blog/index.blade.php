@extends('layouts.app')

@section('content')
<x-sections.background>
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <x-section-title
            :title="__('Blog')"
            :subtitle="__('Teknoloji ve yazılım geliştirme hakkında yazılar')"
            align="left"
        />

        @if ($posts->count())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                @foreach ($posts as $post)
                    <x-blog.post-card :post="$post" imageHeight="h-52" />
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @else
            <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-black/20 backdrop-blur-sm p-10 text-center shadow-xs">
                <p class="text-[#706f6c] dark:text-[#D4D3D0]">{{ __('Henüz blog yazısı yok.') }}</p>
            </div>
        @endif
    </section>
</x-sections.background>
@endsection
