import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/styles.css',
                'resources/css/netto/admin.css',
                'resources/css/netto/browser.css',
                'resources/css/netto/buttons.css',
                'resources/css/netto/form.css',
                'resources/css/netto/gallery.css',
                'resources/css/netto/guest.css',
                'resources/css/netto/layers.css',
                'resources/css/netto/list.css',
                'resources/css/netto/tabs.css',
                'resources/js/app.js',
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
            ],
            refresh: true,
        }),
    ],
});
