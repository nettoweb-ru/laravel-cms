import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/styles.css',
                'resources/css/error.scss',
                'resources/css/netto/app.scss',
                'resources/css/netto/form.scss',
                'resources/css/netto/guest.scss',
                'resources/css/netto/layout.scss',
                'resources/css/netto/list.scss',
                'resources/css/netto/tabs.scss',
                'resources/css/netto/home.css',
                'resources/js/styles.js',
                'resources/js/netto/ajax.js',
                'resources/js/netto/app.js',
                'resources/js/netto/browser.js',
                'resources/js/netto/editor.js',
                'resources/js/netto/form.js',
                'resources/js/netto/gallery.js',
                'resources/js/netto/list.js',
                'resources/js/netto/list.widget.js',
                'resources/js/netto/overlay.js',
                'resources/js/netto/tabs.js',
                'resources/js/netto/logs.js',
            ],
            refresh: true,
        }),
    ],
});
