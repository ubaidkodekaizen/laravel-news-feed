import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/App.jsx'],
            refresh: true,
        }),
        react({
            jsxRuntime: 'automatic',  // Ensure this setting is included for React 18
        }),
    ],
    build: {
        outDir: 'build', // Ensure this matches your Laravel public directory structure
    },
});