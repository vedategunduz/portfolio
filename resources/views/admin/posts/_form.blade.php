@php
    $post = $post ?? null;
    $locales = $locales ?? config('app.supported_locales', ['tr', 'en']);
    $firstLocale = $locales[0] ?? 'tr';

    $erroredLocale = collect($locales)->first(function ($locale) use ($errors) {
        return $errors->has("translations.$locale.title")
            || $errors->has("translations.$locale.slug")
            || $errors->has("translations.$locale.excerpt")
            || $errors->has("translations.$locale.content")
            || $errors->has("translations.$locale.meta_title")
            || $errors->has("translations.$locale.meta_description");
    });

    $activeLocale = old('active_locale', $erroredLocale ?? $firstLocale);

    $existingCover = old('cover_image_existing', $post?->cover_image);
    $existingCoverUrl = $existingCover
        ? (\Illuminate\Support\Str::startsWith($existingCover, ['http://', 'https://']) ? $existingCover : asset('storage/' . ltrim($existingCover, '/')))
        : null;

    $existingGalleryImages = old('gallery_images_existing', $post?->galleryImages->pluck('image_path')->all() ?? []);
    if (is_string($existingGalleryImages)) {
        $existingGalleryImages = [$existingGalleryImages];
    }
    $existingGalleryImages = collect($existingGalleryImages)->filter()->values()->all();

    $initialStep = 1;
    if ($errors->any()) {
        if ($errors->has('cover_image') || $errors->has('gallery_images') || $errors->has('published_at')) {
            $initialStep = 1;
        } elseif ($erroredLocale) {
            $initialStep = 2;
        } else {
            $initialStep = 1;
        }
    }
@endphp

<div
    x-data="{
        currentStep: {{ $initialStep }},
        activeLocale: '{{ $activeLocale }}',
        previewLocale: '{{ $activeLocale }}',
        coverPreview: null,
        removeCover: {{ old('remove_cover_image') ? 'true' : 'false' }},
        galleryPreviews: [],
        isStepComplete(step) {
            if (step === 1) return true;
            if (step === 2) return true;
            if (step === 3) return true;
            return true;
        },
        nextStep() {
            if (this.currentStep < 4) this.currentStep++;
        },
        prevStep() {
            if (this.currentStep > 1) this.currentStep--;
        }
    }"
    x-init="typeof window.createIcons !== 'undefined' && window.createIcons(); setTimeout(() => { typeof window.initAllEditors !== 'undefined' && window.initAllEditors(); }, 100)"
>

<input type="hidden" name="active_locale" x-model="activeLocale">
<input type="hidden" id="autosave-post-id" name="_autosave_post_id" value="{{ old('_autosave_post_id', $post?->id) }}">

<div class="mb-8">
    <div class="flex items-center justify-between gap-1.5">
        @php $steps = ['Genel Bilgiler', 'İçerik', 'SEO', 'Önizleme']; @endphp
        @foreach($steps as $index => $stepLabel)
            @php $stepNum = $index + 1; @endphp
            <div class="flex-1 flex items-center gap-1.5">
                <button
                    type="button"
                    @click="currentStep = {{ $stepNum }}"
                    class="flex-1 px-2.5 py-2 rounded-sm text-xs font-semibold transition-all duration-200"
                    :class="currentStep === {{ $stepNum }} ? 'bg-[#D62113] text-white shadow-md' : 'bg-[#f8f8f7] dark:bg-[#111110] text-[#706f6c] dark:text-[#8F8F8B] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/30'"
                >
                    <span class="flex items-center justify-center gap-1.5">
                        <span class="inline-flex w-5 h-5 items-center justify-center rounded-full text-[10px] font-bold" :class="currentStep === {{ $stepNum }} ? 'bg-white/30' : 'border border-current'">{{ $stepNum }}</span>
                        <span class="hidden sm:inline">{{ $stepLabel }}</span>
                    </span>
                </button>
                @if($index < count($steps) - 1)
                    <div class="flex-shrink-0 w-px h-4 bg-[#e3e3e0] dark:bg-[#3E3E3A]"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div x-show="currentStep === 1" x-cloak class="space-y-6">
    <x-admin.card class="p-6 space-y-6">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_admin.general_information') }}</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-3">
                <label for="cover_image" class="block text-xs font-medium">{{ __('messages.blog_admin.cover_image') }}</label>
                <input type="hidden" name="cover_image_existing" value="{{ $existingCover }}">
                <label for="cover_image" class="flex items-center justify-center rounded-sm border border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-4 py-8 text-xs text-[#706f6c] dark:text-[#8F8F8B] cursor-pointer hover:border-[#D62113]/50 transition-colors">
                    <span x-text="document.getElementById('cover_image')?.files?.[0]?.name || '{{ __('messages.blog_admin.upload_cover_hint') }}'"></span>
                </label>
                <input
                    id="cover_image"
                    name="cover_image"
                    type="file"
                    accept="image/*"
                    class="sr-only"
                    x-on:change="
                        const file = $event.target.files[0];
                        if (file) {
                            coverPreview = URL.createObjectURL(file);
                            removeCover = false;
                        }
                    "
                >

                @if($existingCoverUrl)
                    <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden" x-show="!removeCover">
                        <img src="{{ $existingCoverUrl }}" alt="cover" class="w-full h-44 object-cover" x-show="!coverPreview">
                    </div>
                @endif

                <template x-if="coverPreview">
                    <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                        <img :src="coverPreview" alt="cover preview" class="w-full h-44 object-cover">
                    </div>
                </template>

                @if($existingCoverUrl)
                    <label class="inline-flex items-center gap-2 text-xs text-[#706f6c] dark:text-[#8F8F8B]">
                        <input type="checkbox" name="remove_cover_image" value="1" x-model="removeCover">
                        {{ __('messages.blog_admin.remove_cover_image') }}
                    </label>
                @endif

                @error('cover_image')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('cover_image_file')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-3">
                <label for="gallery_images" class="block text-xs font-medium">{{ __('messages.blog_admin.gallery_images') }}</label>
                <label for="gallery_images" class="flex items-center justify-center rounded-sm border border-dashed border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-4 py-8 text-xs text-[#706f6c] dark:text-[#8F8F8B] cursor-pointer hover:border-[#D62113]/50 transition-colors">
                    <span x-text="galleryPreviews.length > 0 ? (galleryPreviews.length + ' dosya seçildi') : '{{ __('messages.blog_admin.upload_gallery_hint') }}'"></span>
                </label>
                <input
                    id="gallery_images"
                    name="gallery_images[]"
                    type="file"
                    accept="image/*"
                    multiple
                    class="sr-only"
                    x-on:change="
                        galleryPreviews = Array.from($event.target.files).map(file => URL.createObjectURL(file));
                    "
                >

                @if(count($existingGalleryImages) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" x-show="galleryPreviews.length === 0">
                        @foreach($existingGalleryImages as $existingImage)
                            @php
                                $existingUrl = \Illuminate\Support\Str::startsWith($existingImage, ['http://', 'https://'])
                                    ? $existingImage
                                    : asset('storage/' . ltrim($existingImage, '/'));
                            @endphp
                            <div class="space-y-2">
                                <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                                    <img src="{{ $existingUrl }}" alt="gallery" class="w-full h-24 object-cover">
                                </div>
                                <label class="inline-flex items-center gap-2 text-[11px] text-[#706f6c] dark:text-[#8F8F8B]">
                                    <input type="checkbox" name="remove_gallery_images[]" value="{{ $existingImage }}">
                                    {{ __('messages.blog_admin.remove') }}
                                </label>
                                <input type="hidden" name="gallery_images_existing[]" value="{{ $existingImage }}">
                            </div>
                        @endforeach
                    </div>
                @endif

                <template x-if="galleryPreviews.length > 0">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <template x-for="(preview, index) in galleryPreviews" :key="index">
                            <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden">
                                <img :src="preview" alt="gallery preview" class="w-full h-24 object-cover">
                            </div>
                        </template>
                    </div>
                </template>

                @error('gallery_images')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('gallery_images.*')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('gallery_images_files')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('gallery_images_files.*')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="space-y-3">
                <label for="published_at" class="block text-xs font-medium uppercase tracking-wider">{{ __('messages.blog_admin.published_at') }}</label>
                <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $post?->published_at?->format('Y-m-d\\TH:i')) }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm">
                @error('published_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-3">
                <label class="block text-xs font-medium uppercase tracking-wider text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_admin.status') }}</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="hidden" name="published" value="0">
                        <input type="checkbox" name="published" value="1" @checked(old('published', $post?->published))>
                        {{ __('messages.blog_admin.status_published') }}
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post?->is_featured))>
                        {{ __('messages.blog_admin.featured') }}
                    </label>
                </div>
            </div>
        </div>
    </x-admin.card>
</div>

<div x-show="currentStep === 2" x-cloak class="space-y-6">
    <x-admin.card class="p-6">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-[#1b1b18] dark:text-[#EDEDEC] mb-4">{{ __('messages.blog_admin.translations') }}</h2>

        <div class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] mb-6">
            <div class="flex flex-wrap gap-2">
                @foreach($locales as $locale)
                    @php
                        $hasErrors = $errors->has("translations.$locale.title")
                            || $errors->has("translations.$locale.slug")
                            || $errors->has("translations.$locale.excerpt")
                            || $errors->has("translations.$locale.content");
                    @endphp
                    <button
                        type="button"
                        class="relative px-3 py-2 text-xs font-semibold rounded-t-sm border-b-2 transition-colors"
                        :class="activeLocale === '{{ $locale }}' ? 'border-[#D62113] text-[#D62113]' : 'border-transparent text-[#706f6c] dark:text-[#8F8F8B] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]'"
                        x-on:click="activeLocale = '{{ $locale }}'"
                    >
                        {{ strtoupper($locale) }}
                        @if($hasErrors)
                            <span class="ml-1 inline-block w-1.5 h-1.5 rounded-full bg-red-500 align-middle"></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            @foreach($locales as $locale)
                @php
                    $translation = $post?->translations->firstWhere('locale', $locale);
                @endphp
                <div x-show="activeLocale === '{{ $locale }}'" x-cloak class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title_{{ $locale }}" class="block text-xs font-medium mb-1">{{ __('messages.blog_admin.title') }}</label>
                            <input id="title_{{ $locale }}" name="translations[{{ $locale }}][title]" type="text" value="{{ old("translations.$locale.title", $translation?->title) }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm">
                            @error("translations.$locale.title")<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="slug_{{ $locale }}" class="block text-xs font-medium mb-1">{{ __('messages.blog_admin.slug_optional') }}</label>
                            <input id="slug_{{ $locale }}" name="translations[{{ $locale }}][slug]" type="text" value="{{ old("translations.$locale.slug", $translation?->slug) }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm">
                            @error("translations.$locale.slug")<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="excerpt_{{ $locale }}" class="block text-xs font-medium mb-1">{{ __('messages.blog_admin.excerpt') }}</label>
                        <textarea id="excerpt_{{ $locale }}" name="translations[{{ $locale }}][excerpt]" rows="2" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm">{{ old("translations.$locale.excerpt", $translation?->excerpt) }}</textarea>
                        @error("translations.$locale.excerpt")<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="content_{{ $locale }}" class="block text-xs font-medium mb-1">{{ __('messages.blog_admin.content') }}</label>
                        <div id="editor_{{ $locale }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] min-h-64" data-editor-content="{{ $locale }}" data-editor-locale="{{ $locale }}"></div>
                        <textarea id="content_{{ $locale }}" name="translations[{{ $locale }}][content]" class="sr-only" data-quill-target>{{ old("translations.$locale.content", $translation?->content) }}</textarea>
                        @error("translations.$locale.content")<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            @endforeach
        </div>
    </x-admin.card>
</div>

<div x-show="currentStep === 3" x-cloak class="space-y-6">
    <x-admin.card class="p-6">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-[#1b1b18] dark:text-[#EDEDEC] mb-4">{{ __('messages.blog_admin.seo_settings') }}</h2>

        <div class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] mb-6">
            <div class="flex flex-wrap gap-2">
                @foreach($locales as $locale)
                    @php
                        $hasErrors = $errors->has("translations.$locale.meta_title")
                            || $errors->has("translations.$locale.meta_description");
                    @endphp
                    <button
                        type="button"
                        class="relative px-3 py-2 text-xs font-semibold rounded-t-sm border-b-2 transition-colors"
                        :class="activeLocale === '{{ $locale }}' ? 'border-[#D62113] text-[#D62113]' : 'border-transparent text-[#706f6c] dark:text-[#8F8F8B] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]'"
                        x-on:click="activeLocale = '{{ $locale }}'"
                    >
                        {{ strtoupper($locale) }}
                        @if($hasErrors)
                            <span class="ml-1 inline-block w-1.5 h-1.5 rounded-full bg-red-500 align-middle"></span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            @foreach($locales as $locale)
                @php
                    $translation = $post?->translations->firstWhere('locale', $locale);
                @endphp
                <div x-show="activeLocale === '{{ $locale }}'" x-cloak class="space-y-4">
                    <div>
                        <label for="meta_title_{{ $locale }}" class="block text-xs font-medium mb-1">{{ __('messages.blog_admin.meta_title') }}</label>
                        <input id="meta_title_{{ $locale }}" name="translations[{{ $locale }}][meta_title]" type="text" value="{{ old("translations.$locale.meta_title", $translation?->meta_title) }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm" x-on:input="$dispatch('seo-update')">
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">En fazla 60 karakter (ideal: 50-60)</p>
                            <span class="text-xs font-medium" :class="document.getElementById('meta_title_{{ $locale }}')?.value?.length > 60 ? 'text-red-600' : document.getElementById('meta_title_{{ $locale }}')?.value?.length >= 50 ? 'text-emerald-600' : 'text-amber-600'" x-text="(document.getElementById('meta_title_{{ $locale }}')?.value?.length || 0) + '/60'"></span>
                        </div>
                        @error("translations.$locale.meta_title")<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="meta_description_{{ $locale }}" class="block text-xs font-medium mb-1">{{ __('messages.blog_admin.meta_description') }}</label>
                        <textarea id="meta_description_{{ $locale }}" name="translations[{{ $locale }}][meta_description]" rows="2" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm" x-on:input="$dispatch('seo-update')">{{ old("translations.$locale.meta_description", $translation?->meta_description) }}</textarea>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">En fazla 160 karakter (ideal: 150-160)</p>
                            <span class="text-xs font-medium" :class="document.getElementById('meta_description_{{ $locale }}')?.value?.length > 160 ? 'text-red-600' : document.getElementById('meta_description_{{ $locale }}')?.value?.length >= 150 ? 'text-emerald-600' : 'text-amber-600'" x-text="(document.getElementById('meta_description_{{ $locale }}')?.value?.length || 0) + '/160'"></span>
                        </div>
                        @error("translations.$locale.meta_description")<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            @endforeach
        </div>
    </x-admin.card>
</div>

<div x-show="currentStep === 4" x-cloak class="space-y-6">
    <x-admin.card class="p-6">
        <div class="mb-6 pb-6 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Önizleme</h2>
            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Seçili dildeki içerik aşağıda gösterilmektedir</p>

            <div class="mt-4">
                <label class="block text-xs font-medium mb-2">{{ __('messages.blog_admin.locale') }}</label>
                <select x-model="previewLocale" class="w-full max-w-xs rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm">
                    @foreach($locales as $locale)
                        <option value="{{ $locale }}">{{ strtoupper($locale) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <template x-for="locale in {{ json_encode($locales) }}" :key="locale">
            <div x-show="previewLocale === locale" x-cloak class="space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-4">
                        <div class="overflow-hidden rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] shadow-sm">
                            <template x-if="coverPreview || '{{ $existingCover }}'">
                                <div class="relative">
                                    <img x-show="coverPreview" :src="coverPreview" alt="cover" class="w-full h-64 object-cover">
                                    <img x-show="!coverPreview && !removeCover" src="{{ $existingCoverUrl }}" alt="cover" class="w-full h-64 object-cover">
                                    <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
                                </div>
                            </template>

                            <div class="p-6">
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                                        <span x-text="document.getElementById('title_' + locale)?.value || '...Başlık Girin'"></span>
                                    </h1>
                                    <p class="text-sm text-[#706f6c] dark:text-[#D4D3D0] mb-5">
                                        <span x-text="document.querySelector('[name=\'published_at\']')?.value?.replace('T', ' ') || 'Yayın tarihi belirtilmedi'"></span>
                                    </p>
                                    <div class="prose prose-neutral dark:prose-invert max-w-none mb-4 text-sm">
                                        <p x-text="document.getElementById('excerpt_' + locale)?.value || '...Özet Girin'"></p>
                                    </div>
                                    <div class="p-4 rounded-sm bg-[#f8f8f7] dark:bg-[#111110] border border-[#e3e3e0] dark:border-[#3E3E3A] text-xs text-[#706f6c] dark:text-[#8F8F8B] space-y-1">
                                        <div><strong>Meta Title:</strong> <span x-text="document.getElementById('meta_title_' + locale)?.value || '—'"></span></div>
                                        <div><strong>Meta Description:</strong> <span x-text="document.getElementById('meta_description_' + locale)?.value || '—'"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/50 p-3">
                            <p class="text-xs font-medium text-emerald-800 dark:text-emerald-300">Status</p>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-1">
                                <span x-show="document.querySelector('input[name=\"published\"]:checked')">Yayında</span>
                                <span x-show="!document.querySelector('input[name=\"published\"]:checked')">Taslak</span>
                            </p>
                        </div>

                        <div class="rounded-sm bg-[#f8f8f7] dark:bg-[#111110] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3">
                            <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Öne Çıkan</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">
                                <span x-show="document.querySelector('input[name=\"is_featured\"]:checked')">Evet</span>
                                <span x-show="!document.querySelector('input[name=\"is_featured\"]:checked')">Hayır</span>
                            </p>
                        </div>

                        <div class="rounded-sm bg-[#f8f8f7] dark:bg-[#111110] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3">
                            <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Dil</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1" x-text="locale.toUpperCase()"></p>
                        </div>

                        <div class="rounded-sm bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-900/50 p-3 space-y-2">
                            <p class="text-xs font-medium text-violet-800 dark:text-violet-300">📊 İçerik İstatistikleri</p>
                            <div class="text-xs text-violet-700 dark:text-violet-400 space-y-1">
                                <div><span class="font-medium">Kelime:</span> <span x-text="(() => { const textarea = document.getElementById('content_' + locale); const content = textarea?.value ? textarea.value.replace(/<[^>]*>/g, '') : ''; return content.trim().split(/\s+/).filter(w => w.length > 0).length; })()"></span></div>
                                <div><span class="font-medium">Okuma Süresi:</span> <span x-text="(() => { const textarea = document.getElementById('content_' + locale); const content = textarea?.value ? textarea.value.replace(/<[^>]*>/g, '') : ''; const words = content.trim().split(/\s+/).filter(w => w.length > 0).length; const minutes = Math.ceil(words / 200); return minutes === 1 ? '1 dk' : minutes + '-' + (minutes + 1) + ' dk'; })()"></span></div>
                                <div><span class="font-medium">Paragraf:</span> <span x-text="(() => { const textarea = document.getElementById('content_' + locale); const content = textarea?.value ? textarea.value.replace(/<[^>]*>/g, '') : ''; return content.split(/\s{2,}/).filter(p => p.trim().length > 0).length; })()"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </x-admin.card>
</div>

<div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-[#1a1a19] border-t border-[#e3e3e0] dark:border-[#3E3E3A] backdrop-blur-sm shadow-lg z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-sm text-xs font-medium border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:border-[#D62113]/50 hover:text-[#D62113] transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
                {{ __('messages.blog_admin.cancel') }}
            </a>
            <span id="autosave-status" class="text-[11px] text-[#706f6c] dark:text-[#8F8F8B]">Taslak: değişiklik bekleniyor</span>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                @click="prevStep()"
                x-show="currentStep > 1"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-sm text-xs font-medium border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:border-[#D62113]/50 hover:text-[#D62113] transition-colors"
            >
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Geri
            </button>

            <button
                type="button"
                @click="nextStep()"
                x-show="currentStep < 4"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-sm text-xs font-medium bg-[#D62113] text-white hover:bg-[#b81a0f] transition-colors"
            >
                İleri
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>

            <button
                type="submit"
                x-show="currentStep === 4"
                class="px-4 py-2 rounded-sm text-xs font-medium bg-[#D62113] text-white hover:bg-[#b81a0f] transition-colors"
            >
                {{ __('messages.blog_admin.save') }}
            </button>
        </div>
    </div>
</div>

<div class="pb-24"></div>

</div>

@once
    @push('scripts')
        <script>
            (() => {
                const form = document.querySelector('form[data-autosave-enabled="1"]');
                if (!form) {
                    return;
                }

                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrf) {
                    return;
                }

                const statusEl = document.getElementById('autosave-status');
                const autosavePostIdInput = document.getElementById('autosave-post-id');
                let autosavePostId = form.dataset.autosavePostId || autosavePostIdInput?.value || '';
                let timerId = null;
                let isSubmitting = false;
                let inFlight = false;
                let dirty = false;
                let lastSignature = '';

                const setStatus = (text, tone = 'muted') => {
                    if (!statusEl) {
                        return;
                    }

                    statusEl.textContent = text;
                    statusEl.classList.remove('text-[#706f6c]', 'dark:text-[#8F8F8B]', 'text-emerald-600', 'dark:text-emerald-400', 'text-red-600', 'dark:text-red-400');

                    if (tone === 'ok') {
                        statusEl.classList.add('text-emerald-600', 'dark:text-emerald-400');
                    } else if (tone === 'error') {
                        statusEl.classList.add('text-red-600', 'dark:text-red-400');
                    } else {
                        statusEl.classList.add('text-[#706f6c]', 'dark:text-[#8F8F8B]');
                    }
                };

                const shouldTrack = (target) => {
                    if (!target || !target.name) {
                        return false;
                    }

                    if (target.type === 'file') {
                        return false;
                    }

                    if (target.name === '_token' || target.name === '_method' || target.name === 'active_locale' || target.name === '_autosave_post_id') {
                        return false;
                    }

                    if (target.name.startsWith('remove_gallery_images')) {
                        return false;
                    }

                    return true;
                };

                const signatureFrom = (formData) => {
                    return Array.from(formData.entries())
                        .map(([key, value]) => `${key}:${typeof value === 'string' ? value : ''}`)
                        .join('|');
                };

                const toAutosavePayload = () => {
                    const formData = new FormData(form);

                    formData.delete('cover_image');
                    formData.delete('gallery_images[]');
                    formData.delete('remove_cover_image');
                    formData.delete('remove_gallery_images[]');
                    formData.delete('cover_image_existing');
                    formData.delete('gallery_images_existing[]');

                    return formData;
                };

                const promoteFormToUpdateMode = (postId) => {
                    autosavePostId = String(postId);
                    form.dataset.autosavePostId = autosavePostId;

                    if (autosavePostIdInput) {
                        autosavePostIdInput.value = autosavePostId;
                    }

                    const updateSubmitTemplate = form.dataset.autosaveSubmitUpdateUrlTemplate;
                    if (updateSubmitTemplate) {
                        form.action = updateSubmitTemplate.replace('__POST__', autosavePostId);
                    }

                    let methodField = form.querySelector('input[name="_method"]');
                    if (!methodField) {
                        methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        form.appendChild(methodField);
                    }

                    methodField.value = 'PUT';
                };

                const scheduleAutosave = () => {
                    if (isSubmitting) {
                        return;
                    }

                    dirty = true;
                    if (timerId) {
                        window.clearTimeout(timerId);
                    }

                    setStatus('Taslak: kaydedilecek değişiklik var...');
                    timerId = window.setTimeout(runAutosave, 1200);
                };

                const runAutosave = async () => {
                    if (inFlight || isSubmitting || !dirty) {
                        return;
                    }

                    const formData = toAutosavePayload();
                    const signature = signatureFrom(formData);

                    if (signature === lastSignature) {
                        dirty = false;
                        return;
                    }

                    const updateUrlTemplate = form.dataset.autosaveUpdateUrlTemplate;
                    const storeUrl = form.dataset.autosaveStoreUrl;
                    const endpoint = autosavePostId
                        ? updateUrlTemplate.replace('__POST__', autosavePostId)
                        : storeUrl;

                    formData.append('_autosave', '1');
                    if (autosavePostId) {
                        formData.append('_method', 'PUT');
                    }

                    inFlight = true;
                    setStatus('Taslak: kaydediliyor...');

                    try {
                        const response = await fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            throw new Error(`Autosave request failed: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.post_id) {
                            promoteFormToUpdateMode(data.post_id);
                        }

                        lastSignature = signature;
                        dirty = false;
                        const timeLabel = new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
                        setStatus(`Taslak: kaydedildi (${timeLabel})`, 'ok');
                    } catch (error) {
                        setStatus('Taslak: kaydedilemedi', 'error');
                    } finally {
                        inFlight = false;

                        if (dirty) {
                            scheduleAutosave();
                        }
                    }
                };

                form.addEventListener('input', (event) => {
                    if (shouldTrack(event.target)) {
                        scheduleAutosave();
                    }
                });

                form.addEventListener('change', (event) => {
                    if (shouldTrack(event.target)) {
                        scheduleAutosave();
                    }
                });

                form.addEventListener('submit', () => {
                    isSubmitting = true;
                    if (timerId) {
                        window.clearTimeout(timerId);
                    }
                });
            })();
        </script>
    @endpush
@endonce
