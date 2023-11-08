import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/guest.js',
                'resources/js/admin.js',
                'resources/js/authenticated.js'
            ],
            refresh: true,
        }),
    ],
});
