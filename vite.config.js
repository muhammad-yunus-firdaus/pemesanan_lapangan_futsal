import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // SASS/CSS Files
                'resources/sass/app.scss',
                'resources/css/app.css',
                'resources/css/sport-modern-theme.css',
                'resources/css/user/layout.css',
                'resources/css/user/fields.css',
                'resources/css/user/bookings.css',
                'resources/css/user/booking-form.css',
                
                // JavaScript Files
                'resources/js/app.js',
                'resources/js/user/sidebar.js',
                'resources/js/user/booking-form.js',
            ],
            refresh: true,
        }),
    ],
});
