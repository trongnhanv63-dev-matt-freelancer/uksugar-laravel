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
            host: env.VITE_HMR_HOST, // 'sugar.local'
            port: 5173,
            origin: `http://${env.VITE_HMR_HOST}:5173`, // ensure this matches what the browser expects
            strictPort: true,
            cors: {
                origin: `http://${env.VITE_HMR_HOST}`, // allow requests from your Laravel domain
                credentials: true,
            },
            hmr: {
                host: env.VITE_HMR_HOST,
                protocol: 'ws',
            },
        },
    };
});
