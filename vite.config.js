import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/pages/admin/dashboard.js',
                'resources/js/pages/admin/page-history.js',
                'resources/js/pages/admin/contact-messages.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    build: {
        minify: 'terser',
        target: 'esnext',
        chunkSizeWarningLimit: 500,
        reportCompressedSize: false,
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    // Vendor çünklerini ayır
                    if (id.includes('node_modules/alpinejs')) {
                        return 'alpine';
                    }
                    if (id.includes('node_modules/lucide')) {
                        return 'lucide';
                    }
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                    // Yardımcı dosyaları ayır
                    if (id.includes('resources/js/')) {
                        const fileName = id.split('/').pop().split('.')[0];
                        if (fileName !== 'app') {
                            return fileName;
                        }
                    }
                },
            },
        },
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
                passes: 2,
                unused: true,
            },
            mangle: true,
            format: {
                comments: false,
            },
        },
    },
});
