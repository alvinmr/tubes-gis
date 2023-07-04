import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/map-picker.css', 'resources/js/map-picker.js'],
            refresh: true,
        }),
    ],
});
