<section id="contact" data-scroll-section class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-3xl mx-auto w-full">
        <x-section-title
            class="scroll-item"
            :title="__('messages.home.contact.title')"
            :subtitle="__('messages.home.contact.subtitle')"
        />

        <div class="scroll-item">
            @include('partials.contact-form')
        </div>
    </div>
</section>
