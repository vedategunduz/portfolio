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
    $overviewHasCover = (bool) $existingCover && ! old('remove_cover_image');
    $removedGalleryImages = old('remove_gallery_images', []);
    if (is_string($removedGalleryImages)) {
        $removedGalleryImages = [$removedGalleryImages];
    }
    $removedGalleryImages = collect($removedGalleryImages)->filter()->values()->all();

    $adminTranslationWarnings = $adminTranslationWarnings ?? [];
    $slugSuffixLocales = $slugSuffixLocales ?? [];
    $publishChecklistNotes = $publishChecklistNotes ?? [];

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
        hasExistingCover: {{ $existingCoverUrl ? 'true' : 'false' }},
        removeCover: {{ old('remove_cover_image') ? 'true' : 'false' }},
        removedGalleryImages: {{ json_encode($removedGalleryImages) }},
        galleryPreviews: [],
        seoCounterVersion: 0,
        previewVersion: 0,
        toggleRemoveCover() {
            this.removeCover = !this.removeCover;
            if (!this.removeCover) {
                window.dispatchEvent(new CustomEvent('autosave:trigger', { detail: { media: true } }));
                return;
            }

            const input = document.getElementById('cover_image');
            if (input) {
                input.value = '';
            }
            this.coverPreview = null;
            window.dispatchEvent(new CustomEvent('autosave:trigger', { detail: { media: true } }));
        },
        isGalleryRemoved(path) {
            return this.removedGalleryImages.includes(path);
        },
        toggleGalleryRemove(path) {
            if (this.isGalleryRemoved(path)) {
                this.removedGalleryImages = this.removedGalleryImages.filter(item => item !== path);
                window.dispatchEvent(new CustomEvent('autosave:trigger', { detail: { media: true } }));
                return;
            }

            this.removedGalleryImages.push(path);
            window.dispatchEvent(new CustomEvent('autosave:trigger', { detail: { media: true } }));
        },
        getInputLength(id) {
            return document.getElementById(id)?.value?.length || 0;
        },
        getSeoCounterClass(id, warningThreshold, maxLength) {
            const length = this.getInputLength(id);
            if (length > maxLength) return 'text-red-600';
            if (length >= warningThreshold) return 'text-emerald-600';
            return 'text-amber-600';
        },
        getContentStats(locale) {
            const textarea = document.getElementById('content_' + locale);
            const html = textarea?.value || '';
            const plainText = html.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();

            const wordCount = plainText ? plainText.split(' ').filter(w => w.length > 0).length : 0;
            const readingMinutes = Math.max(1, Math.ceil(wordCount / 200));

            const container = document.createElement('div');
            container.innerHTML = html;

            const blockCount = container.querySelectorAll('p, li, blockquote, pre, h1, h2, h3, h4, h5, h6').length;
            const lineBreakBasedCount = plainText
                ? plainText.split(/\n{2,}/).map(p => p.trim()).filter(Boolean).length
                : 0;
            const paragraphCount = Math.max(blockCount, lineBreakBasedCount, plainText ? 1 : 0);

            return {
                wordCount,
                readingMinutes,
                paragraphCount,
            };
        },
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
    x-on:seo-update.window="seoCounterVersion++"
    x-on:input.window="previewVersion++"
    x-on:change.window="previewVersion++"
    x-init="typeof window.createIcons !== 'undefined' && window.createIcons(); setTimeout(() => { typeof window.initAllEditors !== 'undefined' && window.initAllEditors(); }, 100)"
>

<input type="hidden" name="active_locale" x-model="activeLocale">
<input type="hidden" id="autosave-post-id" name="_autosave_post_id" value="{{ old('_autosave_post_id', $post?->id) }}">

@if(count($adminTranslationWarnings) > 0)
    <div class="mb-6 rounded-sm border border-amber-200 dark:border-amber-900/50 bg-amber-50/90 dark:bg-amber-900/20 p-4">
        <p class="text-xs font-semibold text-amber-900 dark:text-amber-200">{{ __('messages.blog_admin.alerts_translation_title') }}</p>
        <ul class="mt-2 list-disc list-inside text-xs text-amber-900/90 dark:text-amber-100/90 space-y-1">
            @foreach($adminTranslationWarnings as $warning)
                <li>{{ $warning }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(count($publishChecklistNotes) > 0)
    <div class="mb-6 rounded-sm border border-sky-200 dark:border-sky-900/50 bg-sky-50/90 dark:bg-sky-900/20 p-4">
        <p class="text-xs font-semibold text-sky-900 dark:text-sky-200">{{ __('messages.blog_admin.alerts_checklist_title') }}</p>
        <ul class="mt-2 list-disc list-inside text-xs text-sky-900/90 dark:text-sky-100/90 space-y-1">
            @foreach($publishChecklistNotes as $note)
                <li>{{ $note }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-8">
    @php $steps = ['Genel Bilgiler', 'İçerik', 'SEO', 'Önizleme']; @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach($steps as $index => $stepLabel)
            @php $stepNum = $index + 1; @endphp
            <button
                type="button"
                @click="currentStep = {{ $stepNum }}"
                class="group rounded-sm border px-3 py-2.5 text-left transition-colors"
                :class="currentStep === {{ $stepNum }}
                    ? 'border-[#D62113] bg-[#D62113]/8 dark:bg-[#D62113]/15'
                    : (currentStep > {{ $stepNum }}
                        ? 'border-emerald-300/70 dark:border-emerald-900 bg-emerald-50/70 dark:bg-emerald-900/15'
                        : 'border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#111110] hover:border-[#D62113]/30')"
            >
                <span class="flex items-start gap-2.5">
                    <span
                        class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full text-[11px] font-bold transition-colors"
                        :class="currentStep === {{ $stepNum }}
                            ? 'bg-[#D62113] text-white'
                            : (currentStep > {{ $stepNum }}
                                ? 'bg-emerald-600 text-white'
                                : 'border border-[#d1d0cc] dark:border-[#4B4B46] text-[#706f6c] dark:text-[#8F8F8B]')"
                        x-text="currentStep > {{ $stepNum }} ? '✓' : '{{ $stepNum }}'"
                    ></span>
                    <span class="min-w-0">
                        <span
                            class="block text-[11px] font-semibold uppercase tracking-wider"
                            :class="currentStep === {{ $stepNum }} ? 'text-[#D62113]' : 'text-[#706f6c] dark:text-[#8F8F8B]'"
                        >
                            Adım {{ $stepNum }}
                        </span>
                        <span class="block text-xs sm:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] truncate">
                            {{ $stepLabel }}
                        </span>
                    </span>
                </span>
            </button>
        @endforeach
    </div>
</div>

<div x-show="currentStep === 1" x-cloak class="space-y-6">
    <x-admin.card class="p-6 space-y-6">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_admin.general_information') }}</h2>

        <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#f8f8f7] dark:bg-[#111110] p-4 space-y-2 text-xs text-[#706f6c] dark:text-[#8F8F8B]">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_admin.overview_title') }}</p>
            @if($post)
                <p>{{ __('messages.blog_admin.overview_last_saved', ['time' => $post->updated_at->timezone(config('app.timezone'))->format('d.m.Y H:i')]) }}</p>
            @else
                <p>{{ __('messages.blog_admin.overview_new_post') }}</p>
            @endif
            <p>{{ __('messages.blog_admin.overview_media', ['cover' => $overviewHasCover ? __('messages.blog_admin.yes') : __('messages.blog_admin.no'), 'gallery' => count($existingGalleryImages)]) }}</p>
            <p class="text-[11px]">{{ __('messages.blog_admin.overview_autosave_hint') }}</p>
        </div>

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
                            window.dispatchEvent(new CustomEvent('autosave:trigger', { detail: { media: true, files: true } }));
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
                    <template x-if="removeCover">
                        <input type="hidden" name="remove_cover_image" value="1">
                    </template>
                    <button
                        type="button"
                        @click="toggleRemoveCover()"
                        class="inline-flex items-center gap-2 rounded-sm border px-3 py-1.5 text-xs font-medium transition-colors"
                        :class="removeCover
                            ? 'border-emerald-500/60 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20'
                            : 'border-red-500/60 text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20'"
                    >
                        <span x-text="removeCover ? 'Kaldırmayı Geri Al' : '{{ __('messages.blog_admin.remove_cover_image') }}'"></span>
                    </button>
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
                        if (galleryPreviews.length > 0) {
                            window.dispatchEvent(new CustomEvent('autosave:trigger', { detail: { media: true, files: true } }));
                        }
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
                                <div
                                    class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden transition-opacity"
                                    :class="isGalleryRemoved(@js($existingImage)) ? 'opacity-40' : ''"
                                >
                                    <img src="{{ $existingUrl }}" alt="gallery" class="w-full h-24 object-cover">
                                </div>
                                <button
                                    type="button"
                                    @click="toggleGalleryRemove(@js($existingImage))"
                                    class="inline-flex items-center gap-2 rounded-sm border px-2.5 py-1 text-[11px] font-medium transition-colors"
                                    :class="isGalleryRemoved(@js($existingImage))
                                        ? 'border-emerald-500/60 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20'
                                        : 'border-red-500/60 text-red-700 dark:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20'"
                                >
                                    <span x-text="isGalleryRemoved(@js($existingImage)) ? 'Silmeyi Geri Al' : '{{ __('messages.blog_admin.remove') }}'"></span>
                                </button>
                                <template x-if="isGalleryRemoved(@js($existingImage))">
                                    <input
                                        type="hidden"
                                        name="remove_gallery_images[]"
                                        value="{{ $existingImage }}"
                                    >
                                </template>
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
                        <input id="published_toggle" type="checkbox" name="published" value="1" @checked(old('published', $post?->published))>
                        {{ __('messages.blog_admin.status_published') }}
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="hidden" name="is_featured" value="0">
                        <input id="featured_toggle" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post?->is_featured))>
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
                        $slugSuffixHint = in_array($locale, $slugSuffixLocales, true);
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
                        @elseif($slugSuffixHint)
                            <span class="ml-1 inline-block w-1.5 h-1.5 rounded-full bg-amber-500 align-middle" title="{{ __('messages.blog_admin.slug_suffix_hint') }}"></span>
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
                            <input id="slug_{{ $locale }}" name="translations[{{ $locale }}][slug]" type="text" value="{{ old("translations.$locale.slug", $translation?->slug) }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm" autocomplete="off">
                            <p class="mt-1 text-[11px] text-[#706f6c] dark:text-[#8F8F8B] break-all">
                                <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.blog_admin.public_url') }}</span>
                                <code class="ml-1 rounded-sm bg-[#f8f8f7] dark:bg-[#1a1a19] px-1 py-0.5" x-text="(previewVersion, '/blog/' + (document.getElementById('slug_{{ $locale }}')?.value?.trim() || @js($translation?->slug ?? '') || '…'))"></code>
                            </p>
                            @if(in_array($locale, $slugSuffixLocales, true))
                                <p class="mt-1 text-[11px] text-amber-700 dark:text-amber-400">{{ __('messages.blog_admin.slug_suffix_hint') }}</p>
                            @endif
                            @if(! $translation?->slug)
                                <p class="mt-1 text-[11px] text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.blog_admin.slug_empty_hint') }}</p>
                            @endif
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
        <div class="mb-6 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#f8f8f7] dark:bg-[#111110] p-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#1b1b18] dark:text-[#EDEDEC] mb-3">{{ __('messages.blog_admin.seo_summary_title') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($locales as $locale)
                    <div class="rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] p-3 text-xs space-y-2">
                        <p class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ strtoupper($locale) }}</p>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.blog_admin.meta_title') }}</span>
                            <span class="font-mono font-medium shrink-0" :class="(seoCounterVersion, getSeoCounterClass('meta_title_{{ $locale }}', 50, 60))" x-text="(seoCounterVersion, getInputLength('meta_title_{{ $locale }}') + '/60')"></span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.blog_admin.meta_description') }}</span>
                            <span class="font-mono font-medium shrink-0" :class="(seoCounterVersion, getSeoCounterClass('meta_description_{{ $locale }}', 150, 160))" x-text="(seoCounterVersion, getInputLength('meta_description_{{ $locale }}') + '/160')"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

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
                            <span class="text-xs font-medium" :class="(seoCounterVersion, getSeoCounterClass('meta_title_{{ $locale }}', 50, 60))" x-text="(seoCounterVersion, getInputLength('meta_title_{{ $locale }}') + '/60')"></span>
                        </div>
                        @error("translations.$locale.meta_title")<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="meta_description_{{ $locale }}" class="block text-xs font-medium mb-1">{{ __('messages.blog_admin.meta_description') }}</label>
                        <textarea id="meta_description_{{ $locale }}" name="translations[{{ $locale }}][meta_description]" rows="2" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] px-3 py-2 text-sm" x-on:input="$dispatch('seo-update')">{{ old("translations.$locale.meta_description", $translation?->meta_description) }}</textarea>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">En fazla 160 karakter (ideal: 150-160)</p>
                            <span class="text-xs font-medium" :class="(seoCounterVersion, getSeoCounterClass('meta_description_{{ $locale }}', 150, 160))" x-text="(seoCounterVersion, getInputLength('meta_description_{{ $locale }}') + '/160')"></span>
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
                        <div class="overflow-hidden rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] shadow-xs">
                            <template x-if="coverPreview || (hasExistingCover && !removeCover)">
                                <div class="relative">
                                    <img x-show="coverPreview" :src="coverPreview" alt="cover" class="w-full h-64 object-cover">
                                    <img x-show="!coverPreview && !removeCover" src="{{ $existingCoverUrl }}" alt="cover" class="w-full h-64 object-cover">
                                </div>
                            </template>

                            <div class="p-6">
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-3">
                                        <span x-text="(previewVersion, document.getElementById('title_' + locale)?.value || '...Başlık Girin')"></span>
                                    </h1>
                                    <p class="text-sm text-[#706f6c] dark:text-[#D4D3D0] mb-5">
                                        <span x-text="(previewVersion, document.querySelector('[name=\'published_at\']')?.value?.replace('T', ' ') || 'Yayın tarihi belirtilmedi')"></span>
                                    </p>
                                    <p class="text-base text-[#706f6c] dark:text-[#D4D3D0] mb-5" x-text="(previewVersion, document.getElementById('excerpt_' + locale)?.value || '...Özet Girin')"></p>
                                    <div
                                        class="prose prose-neutral dark:prose-invert max-w-none text-sm md:text-base"
                                        x-html="(previewVersion, document.getElementById('content_' + locale)?.value || '<p>...İçerik girin</p>')"
                                    ></div>
                                    <div class="mt-6 p-4 rounded-sm bg-[#f8f8f7] dark:bg-[#111110] border border-[#e3e3e0] dark:border-[#3E3E3A] text-xs text-[#706f6c] dark:text-[#8F8F8B] space-y-1">
                                        <div class="font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">SEO Önizleme</div>
                                        <div><strong>Meta Title:</strong> <span x-text="(previewVersion, document.getElementById('meta_title_' + locale)?.value || '—')"></span></div>
                                        <div><strong>Meta Description:</strong> <span x-text="(previewVersion, document.getElementById('meta_description_' + locale)?.value || '—')"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-sm bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/50 p-3">
                            <p class="text-xs font-medium text-emerald-800 dark:text-emerald-300">Status</p>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-1">
                                <span x-text="(previewVersion, document.getElementById('published_toggle')?.checked ? 'Yayında' : 'Taslak')"></span>
                            </p>
                        </div>

                        <div class="rounded-sm bg-[#f8f8f7] dark:bg-[#111110] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3">
                            <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Öne Çıkan</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">
                                <span x-text="(previewVersion, document.getElementById('featured_toggle')?.checked ? 'Evet' : 'Hayır')"></span>
                            </p>
                        </div>

                        <div class="rounded-sm bg-[#f8f8f7] dark:bg-[#111110] border border-[#e3e3e0] dark:border-[#3E3E3A] p-3">
                            <p class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Dil</p>
                            <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1" x-text="locale.toUpperCase()"></p>
                        </div>

                        <div class="rounded-sm bg-violet-50 dark:bg-violet-900/20 border border-violet-200 dark:border-violet-900/50 p-3 space-y-2">
                            <p class="text-xs font-medium text-violet-800 dark:text-violet-300">📊 İçerik İstatistikleri</p>
                            <div class="text-xs text-violet-700 dark:text-violet-400 space-y-1">
                                <div><span class="font-medium">Kelime:</span> <span x-text="(previewVersion, getContentStats(locale).wordCount)"></span></div>
                                <div><span class="font-medium">Okuma Süresi:</span> <span x-text="(previewVersion, (() => { const minutes = getContentStats(locale).readingMinutes; return minutes === 1 ? '1 dk' : minutes + '-' + (minutes + 1) + ' dk'; })())"></span></div>
                                <div><span class="font-medium">Paragraf:</span> <span x-text="(previewVersion, getContentStats(locale).paragraphCount)"></span></div>
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
                let pendingMediaSync = false;
                let pendingFileSync = false;

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
                        .map(([key, value]) => {
                            if (value instanceof File) {
                                return [key, `${value.name}:${value.size}:${value.lastModified}`];
                            }

                            return [key, typeof value === 'string' ? value.trim() : ''];
                        })
                        .sort(([aKey, aValue], [bKey, bValue]) => {
                            if (aKey === bKey) return aValue.localeCompare(bValue);
                            return aKey.localeCompare(bKey);
                        })
                        .map(([key, value]) => `${key}:${value}`)
                        .join('|');
                };

                const toAutosavePayload = () => {
                    const formData = new FormData(form);

                    formData.delete('cover_image_existing');
                    formData.delete('gallery_images_existing[]');

                    if (!pendingFileSync) {
                        formData.delete('cover_image');
                        formData.delete('gallery_images[]');
                    }

                    if (!pendingMediaSync) {
                        formData.delete('remove_cover_image');
                        formData.delete('remove_gallery_images[]');
                    }

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

                    const currentSignature = signatureFrom(toAutosavePayload());
                    if (currentSignature === lastSignature) {
                        dirty = false;
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

                        pendingMediaSync = false;
                        pendingFileSync = false;
                        lastSignature = signatureFrom(toAutosavePayload());
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

                window.addEventListener('autosave:trigger', (event) => {
                    if (event?.detail?.media) {
                        pendingMediaSync = true;
                    }
                    if (event?.detail?.files) {
                        pendingFileSync = true;
                    }
                    scheduleAutosave();
                });

                window.addEventListener('autosave:editors-ready', () => {
                    if (timerId) {
                        window.clearTimeout(timerId);
                        timerId = null;
                    }
                    dirty = false;
                    lastSignature = signatureFrom(toAutosavePayload());
                    setStatus('Taslak: değişiklik bekleniyor');
                });
            })();
        </script>
    @endpush
@endonce
