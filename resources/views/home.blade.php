@extends('layouts.app')

@section('title', 'Portfolio - ' . config('app.name'))

@section('seo')
    <x-seo
        title="Vedat Egündüz - Full-Stack Developer | Laravel & PHP Backend Specialist"
        description="Backend ağırlıklı çalışan bir full-stack geliştiriciyim. Laravel, PHP, RESTful API, JWT authentication ve modern web teknolojileri konusunda deneyimliyim. Ölçeklenebilir ve sürdürülebilir yazılım geliştiriyorum."
        keywords="vedat egündüz, full-stack developer, backend developer, laravel developer, php developer, rest api, jwt authentication, mysql, database design, web developer, software engineer, next.js, tailwind css, türkiye, turkey"
        :canonical="true"
    />
@endsection

@section('content')
<!-- Background Wrapper with Gradient Blobs -->
<div class="relative overflow-hidden">
    <!-- Decorative Background Blobs -->
    <div class="absolute inset-0 z-0" aria-hidden="true">
        <!-- Blob 1: Top Left (Purple to Cyan) -->
        <div class="absolute -top-32 -left-32 w-96 h-96 md:w-150 md:h-150 lg:w-175 lg:h-175 rounded-full bg-linear-to-br from-purple-500/35 to-cyan-400/25 blur-3xl pointer-events-none animate-float-blob-1"></div>

        <!-- Blob 2: Top Right (Pink to Amber) -->
        <div class="absolute -top-20 -right-40 w-80 h-80 md:w-150 md:h-125 lg:w-150 lg:h-150 rounded-full bg-linear-to-br from-pink-500/30 to-amber-400/20 blur-[120px] pointer-events-none animate-float-blob-2"></div>

        <!-- Blob 3: Mid Left (Cyan to Purple) -->
        <div class="absolute top-1/3 -left-48 w-72 h-72 md:w-112.5 md:h-112.5 lg:w-137.5 lg:h-137.5 rounded-full bg-linear-to-br from-cyan-400/25 to-purple-500/30 blur-3xl pointer-events-none animate-float-blob-3"></div>

        <!-- Blob 4: Mid Right (Amber to Pink) -->
        <div class="absolute top-1/2 -right-32 w-96 h-96 md:w-137.5 md:h-137.5 lg:w-162.5 lg:h-162.5 rounded-full bg-linear-to-br from-amber-400/20 to-pink-500/25 blur-[120px] pointer-events-none animate-float-blob-4"></div>

        <!-- Blob 5: Bottom Left (Purple to Cyan) -->
        <div class="absolute -bottom-40 -left-20 w-80 h-80 md:w-150 md:h-125 lg:w-150 lg:h-150 rounded-full bg-linear-to-br from-purple-600/30 to-cyan-500/20 blur-3xl pointer-events-none animate-float-blob-5"></div>

        <!-- Optional: Subtle Noise Overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMDAiIGhlaWdodD0iMzAwIj48ZmlsdGVyIGlkPSJhIj48ZmVUdXJidWxlbmNlIGJhc2VGcmVxdWVuY3k9Ii43NSIgc3RpdGNoVGlsZXM9InN0aXRjaCIgdHlwZT0iZnJhY3RhbE5vaXNlIi8+PGZlQ29sb3JNYXRyaXggdHlwZT0ic2F0dXJhdGUiIHZhbHVlcz0iMCIvPjwvZmlsdGVyPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbHRlcj0idXJsKCNhKSIgb3BhY2l0eT0iLjA1Ii8+PC9zdmc+')] opacity-[0.06] mix-blend-overlay pointer-events-none"></div>
    </div>

    <!-- Content Wrapper (Above Background) -->
    <div class="relative z-10">
        <!-- Hero Section -->
        <section id="home" class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto text-center" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
        <h1 class="text-5xl md:text-7xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6 transition-all duration-1000"
            :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            Merhaba, Ben
            <span class="text-[#FF2D20]">Vedat</span>
        </h1>
        <p class="text-xl md:text-2xl text-[#706f6c] dark:text-[#A1A09A] max-w-3xl mx-auto mb-8 transition-all duration-1000 delay-200"
           :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            Backend ağırlıklı çalışan bir full-stack geliştiriciyim. Ölçeklenebilir, temiz ve sürdürülebilir yazılım geliştirmeye odaklanıyorum.
        </p>
        <div class="flex gap-4 justify-center flex-wrap transition-all duration-1000 delay-400"
             :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'">
            <x-button href="#projects" variant="primary">
                Projelerimi Görüntüle
            </x-button>
            <x-button href="#contact" variant="secondary">
                İletişime Geç
            </x-button>
        </div>
    </div>
</section>

        <!-- About Section -->
        <section id="about" class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-7xl mx-auto">
        <x-section-title title="Hakkımda" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h3 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Kim Ben?
                </h3>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-4">
                    PHP/Laravel ile backend geliştirme odaklı çalışıyorum. RESTful API, JWT authentication ve third-party entegrasyonlar (özellikle .NET servisleriyle) konusunda deneyimliyim. Payment flows ve database design alanlarında özellikle titiz çalışırım.
                </p>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] mb-6">
                    Sağlam backend mimarileri kurmak, RESTful API geliştirme ve modern frontend ile entegrasyon alanlarında uzmanlaşıyorum. Temiz ve okunabilir kod yazarım, best practice'lere sadık kalırım ve problemi hacklemek yerine doğru mimari kurarım.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-skill-card
                    title="Backend (Ana Odak)"
                    description="PHP / Laravel, RESTful API, JWT Auth, Payment Flows, .NET Integration"
                    icon="server"
                />

                <x-skill-card
                    title="Frontend"
                    description="JavaScript (ES6+), Next.js, HTML/CSS, Tailwind CSS, Bootstrap"
                    icon="code"
                />

                <x-skill-card
                    title="Database"
                    description="MySQL, SQL & Relational Design, Query Optimization"
                    icon="database"
                />

                <x-skill-card
                    title="Tools & Workflow"
                    description="Git / GitHub, Linux Deployment, Debugging & Logging"
                    icon="wrench"
                />
            </div>
        </div>
    </div>
</section>

        <!-- Projects Section -->
        <section id="projects" class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-7xl mx-auto w-full">
        <x-section-title
            title="Projelerim"
            subtitle="Son zamanlarda üzerinde çalıştığım projelerden bazıları"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <x-project-card
                title="Laravel + Next.js E-Ticaret Altyapısı"
                description="Modern e-ticaret platformu. Laravel backend API ve Next.js frontend ile ölçeklenebilir mimari. Ödeme entegrasyonu, sepet yönetimi ve admin paneli."
                color="#FF2D20"
                initial="E"
                :tags="['Laravel', 'Next.js', 'RESTful API', 'MySQL']"
            >
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    <p class="mb-2"><strong>Özellikler:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>RESTful API mimarisi</li>
                        <li>JWT Authentication</li>
                        <li>Payment gateway entegrasyonu</li>
                        <li>Real-time stok yönetimi</li>
                    </ul>
                </div>
            </x-project-card>

            <x-project-card
                title="3D Secure Ödeme Entegrasyonu"
                description="Bankalar arası güvenli ödeme sistemi entegrasyonu. 3D Secure protokolü ile kredi kartı işlemleri ve transaction yönetimi."
                color="#3B82F6"
                initial="3D"
                :tags="['PHP', 'Payment Gateway', 'API', 'Security']"
            >
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    <p class="mb-2"><strong>Özellikler:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>3D Secure 2.0 protokolü</li>
                        <li>Transaction logging</li>
                        <li>Callback handling</li>
                        <li>Error recovery mekanizması</li>
                    </ul>
                </div>
            </x-project-card>

            <x-project-card
                title="Emlak Platformu Backend Mimarisi"
                description="Ölçeklenebilir emlak yönetim sistemi. İlan yönetimi, kullanıcı rolleri ve gelişmiş filtreleme özellikleri ile RESTful API."
                color="#10B981"
                initial="E"
                :tags="['Laravel', 'MySQL', 'RESTful API', 'JWT']"
            >
                <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    <p class="mb-2"><strong>Özellikler:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Advanced search & filtering</li>
                        <li>Role-based access control</li>
                        <li>Image optimization</li>
                        <li>Third-party map integration</li>
                    </ul>
                </div>
            </x-project-card>
        </div>
    </div>
</section>

        <!-- Contact Section -->
        <section id="contact" class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-3xl mx-auto w-full">
        <x-section-title
            title="İletişim"
            subtitle="Bir projeniz mi var veya benimle çalışmak mı istiyorsunuz? Benimle iletişime geçin!"
        />

        <livewire:contact-form />
    </div>
</section>
    </div>
</div>
<!-- End Background Wrapper -->
@endsection
