import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '127.0.0.1', // or 0.0.0.0 if you want network access
        port: 5173,
        hmr: {
        host: 'app.koverae.test',
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/app.tsx',
            ],
            refresh: true,
        }),
    ],
});
