import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: [
            {
                find: /^~(.+)$/,
                replacement: path.resolve(__dirname, 'node_modules/$1')
            },
            {
                find: /^bootstrap/,
                replacement: path.resolve(__dirname, 'node_modules/bootstrap')
            }
        ]
    },
    optimizeDeps: {
        include: [
            '@popperjs/core',
            'bootstrap',
            'feather-icons',
            'flatpickr',
            'jsvectormap',
            'simplebar'
        ]
    },
});
