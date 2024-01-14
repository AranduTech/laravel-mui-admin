import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/guest.jsx',
                'resources/js/admin.jsx',
                'resources/js/authenticated.jsx'
            ],
            refresh: true,
        }),
        react(),
    ],
    define: {
        global: 'window',
    },
});
