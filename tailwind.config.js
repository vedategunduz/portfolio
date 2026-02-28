/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.{blade.php,js,vue,jsx,tsx,ts}',
        './app/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                // Özel renkler eklenebilir
            },
            // Diğer tema özelleştirmeleri
        },
    },
    plugins: [
        // Plugin'ler buraya eklenebilir
    ],
    // Optimize etme seçenekleri
    safelist: [
        // Dinamik olarak eklenen sınıflar buraya yazılmalıdır
        // Örn: 'bg-red-500', 'text-lg' gibi
    ],
    corePlugins: {
        // İhtiyaç olmayan özellikleri devre dışı bırakabilirsiniz
        // preflight: true, // devre dışı bırakmak isterseniz false yapın
    },
};
