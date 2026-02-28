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
                color="#D62113"
                initial="E"
                :tags="['Laravel', 'Next.js', 'RESTful API', 'MySQL']"
            >
                <div class="text-sm text-[#706f6c] dark:text-[#D4D3D0]">
                    <p class="mb-2"><strong>Özellikler:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>RESTful API mimarisi</li>
                        <li>JWT Authentication</li>
                        <li>Ödeme altyapısı entegrasyonu</li>
                        <li>Gerçek zamanlı stok yönetimi</li>
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
                <div class="text-sm text-[#706f6c] dark:text-[#D4D3D0]">
                    <p class="mb-2"><strong>Özellikler:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>3D Secure 2.0 protokolü</li>
                        <li>İşlem (transaction) loglama</li>
                        <li>Callback yönetimi</li>
                        <li>Hata kurtarma mekanizması</li>
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
                <div class="text-sm text-[#706f6c] dark:text-[#D4D3D0]">
                    <p class="mb-2"><strong>Özellikler:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Gelişmiş arama ve filtreleme</li>
                        <li>Rol bazlı erişim kontrolü</li>
                        <li>Görsel optimizasyonu</li>
                        <li>Üçüncü parti harita entegrasyonu</li>
                    </ul>
                </div>
            </x-project-card>
        </div>
    </div>
</section>
