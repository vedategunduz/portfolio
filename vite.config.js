import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
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
                manualChunks: {
                    'vendor-alpine': ['alpinejs'],
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
