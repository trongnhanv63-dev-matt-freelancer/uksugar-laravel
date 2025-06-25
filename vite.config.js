import { defineConfig, loadEnv } from 'vite'; // Import loadEnv
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    // Load env variables based on the current mode (development, production)
    const env = loadEnv(mode, process.cwd(), '');

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            // Listen on all network interfaces inside the container
            host: '0.0.0.0',
            port: 5173,
            hmr: {
                // Use the VITE_HMR_HOST from .env file for the HMR client
                host: env.VITE_HMR_HOST,
            },
        },
    };
});
