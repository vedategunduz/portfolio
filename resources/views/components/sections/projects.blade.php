<section id="projects" data-scroll-section class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-7xl mx-auto w-full">
        <x-section-title
            class="scroll-item"
            :title="__('messages.home.projects.title')"
            :subtitle="__('messages.home.projects.subtitle')"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <x-project-card
                data-scroll-card
                :title="__('messages.home.projects.project1.title')"
                :description="__('messages.home.projects.project1.description')"
                color="#D62113"
                initial="E"
                :tags="['Laravel', 'Next.js', 'RESTful API', 'MySQL']"
            >
                <div class="text-sm text-[#706f6c] dark:text-[#D4D3D0]">
                    <p class="mb-2"><strong>{{ __('messages.home.projects.features') }}</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>{{ __('messages.home.projects.project1.feature1') }}</li>
                        <li>{{ __('messages.home.projects.project1.feature2') }}</li>
                        <li>{{ __('messages.home.projects.project1.feature3') }}</li>
                        <li>{{ __('messages.home.projects.project1.feature4') }}</li>
                    </ul>
                </div>
            </x-project-card>

            <x-project-card
                data-scroll-card
                :title="__('messages.home.projects.project2.title')"
                :description="__('messages.home.projects.project2.description')"
                color="#3B82F6"
                initial="3D"
                :tags="['PHP', 'Payment Gateway', 'API', 'Security']"
            >
                <div class="text-sm text-[#706f6c] dark:text-[#D4D3D0]">
                    <p class="mb-2"><strong>{{ __('messages.home.projects.features') }}</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>{{ __('messages.home.projects.project2.feature1') }}</li>
                        <li>{{ __('messages.home.projects.project2.feature2') }}</li>
                        <li>{{ __('messages.home.projects.project2.feature3') }}</li>
                        <li>{{ __('messages.home.projects.project2.feature4') }}</li>
                    </ul>
                </div>
            </x-project-card>

            <x-project-card
                data-scroll-card
                :title="__('messages.home.projects.project3.title')"
                :description="__('messages.home.projects.project3.description')"
                color="#10B981"
                initial="E"
                :tags="['Laravel', 'MySQL', 'RESTful API', 'JWT']"
            >
                <div class="text-sm text-[#706f6c] dark:text-[#D4D3D0]">
                    <p class="mb-2"><strong>{{ __('messages.home.projects.features') }}</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>{{ __('messages.home.projects.project3.feature1') }}</li>
                        <li>{{ __('messages.home.projects.project3.feature2') }}</li>
                        <li>{{ __('messages.home.projects.project3.feature3') }}</li>
                        <li>{{ __('messages.home.projects.project3.feature4') }}</li>
                    </ul>
                </div>
            </x-project-card>
        </div>
    </div>
</section>
