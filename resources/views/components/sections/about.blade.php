<section id="about" data-scroll-section class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-7xl mx-auto">
        <x-section-title class="scroll-item" :title="__('messages.home.about.title')" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="scroll-item">
                <h3 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    {{ __('messages.home.about.heading') }}
                </h3>
                <p class="text-lg text-[#706f6c] dark:text-[#D4D3D0] mb-4">
                    {{ __('messages.home.about.paragraph_1') }}
                </p>
                <p class="text-lg text-[#706f6c] dark:text-[#D4D3D0] mb-6">
                    {{ __('messages.home.about.paragraph_2') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-skill-card
                    data-scroll-card
                    :title="__('messages.home.about.skills.backend.title')"
                    :description="__('messages.home.about.skills.backend.description')"
                    icon="server"
                />

                <x-skill-card
                    data-scroll-card
                    :title="__('messages.home.about.skills.frontend.title')"
                    :description="__('messages.home.about.skills.frontend.description')"
                    icon="code"
                />

                <x-skill-card
                    data-scroll-card
                    :title="__('messages.home.about.skills.database.title')"
                    :description="__('messages.home.about.skills.database.description')"
                    icon="database"
                />

                <x-skill-card
                    data-scroll-card
                    :title="__('messages.home.about.skills.tools.title')"
                    :description="__('messages.home.about.skills.tools.description')"
                    icon="wrench"
                />
            </div>
        </div>
    </div>
</section>
