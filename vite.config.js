import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

export default defineConfig({
    build: {
        sourcemap: true,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: 'dierenasiel.test',
        hmr: {
            host: 'dierenasiel.test',
        },
        https: {
            key: fs.readFileSync('/var/www/Certificates/dierenasiel.test.key'),
            cert: fs.readFileSync('/var/www/Certificates/dierenasiel.test.crt'),
        },
    },
});
