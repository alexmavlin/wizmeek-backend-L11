import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSCC Files
                'resources/scss/app.scss',
                'resources/scss/admin/dashboard.scss',
                'resources/scss/admin/artists/artists_index.scss',
                'resources/scss/admin/artists/artists_create.scss',
                'resources/scss/admin/artists/artists_edit.scss',
                'resources/scss/popup.scss',
                // JS Files
                'resources/js/app.js',
                'resources/js/admin/popup.js',
                'resources/js/admin/youtubeGetVideoData.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Bind to all network interfaces in the Docker container
        port: 5173,       // Ensure it's on port 5173
        hmr: {
            host: 'localhost', // Host machine access for Hot Module Replacement
            port: 5173,        // Match the Vite dev server port
        },
    }
});
