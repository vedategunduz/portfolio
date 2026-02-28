<section id="about" class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-20">
    <div class="max-w-7xl mx-auto">
        <x-section-title title="Hakkımda" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h3 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Ben Kimim?
                </h3>
                <p class="text-lg text-[#706f6c] dark:text-[#D4D3D0] mb-4">
                    PHP/Laravel ile backend geliştirme odaklı çalışıyorum. RESTful API, JWT kimlik doğrulama ve üçüncü parti entegrasyonlar (özellikle .NET servisleriyle) konusunda deneyimliyim. Ödeme akışları ve veritabanı tasarımı alanlarında özellikle titiz çalışırım.
                </p>
                <p class="text-lg text-[#706f6c] dark:text-[#D4D3D0] mb-6">
                    Sağlam backend mimarileri kurma, RESTful API geliştirme ve modern frontend entegrasyonu alanlarında uzmanlaşıyorum. Temiz ve okunabilir kod yazarım, en iyi uygulamalara sadık kalırım ve problemi geçici çözümlerle yamamak yerine doğru mimariyi kurarım.
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
