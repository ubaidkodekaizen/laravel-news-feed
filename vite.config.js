import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/chat.css',
                'resources/css/inbox.css',
                'resources/js/App.jsx',
                'resources/js/inbox.jsx',
                ],
            refresh: true,
        }),
        react({
            jsxRuntime: 'automatic',
        }),
    ],
    build: {
        outDir: 'public/build',
    }
});
