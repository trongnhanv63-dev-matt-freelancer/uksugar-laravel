import { defineConfig, loadEnv } from 'vite'; // Import loadEnv
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    // Load env variables based on the current mode (development, production)
    const env = loadEnv(mode, process.cwd(), ''); //console.log({ env })
    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            host: 'localhost',
            port: 5173,
            strictPort: true,
            https: false, // <- Force HTTP
            hmr: {
                protocol: 'ws', // not wss
                host: 'localhost',
                port: 5173,
            },
        },
    };
});
