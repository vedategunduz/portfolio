@props([
    'posts' => collect(),
])

@if($posts->isNotEmpty())
    <section id="blog" class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-7xl mx-auto w-full">
            <div class="mb-12">
                <x-section-title
                    class="mb-0!"
                    :title="__('Son Yazılar')"
                    :subtitle="__('Blogdan son içerikler')"
                    align="center"
                />
                <div class="mt-2 flex justify-end">
                    <a href="{{ route('blog.index') }}" class="inline-block text-sm font-semibold text-[#D62113] hover:text-[#B51C10] transition">
                        {{ __('Tümünü Gör') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <x-blog.post-card :post="$post" imageHeight="h-44" />
                @endforeach
            </div>
        </div>
    </section>
@endif
