import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import basicSsl from '@vitejs/plugin-basic-ssl'; // <-- THÊM DÒNG NÀY

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '');
  return {
    plugins: [
      laravel({
        input: ['resources/css/app.css', 'resources/js/app.js'],
        refresh: true,
      }),
      tailwindcss(),
      basicSsl(), // <-- THÊM DÒNG NÀY
    ],
    server: {
      host: '0.0.0.0',
      port: 5173,
      strictPort: true,
      https: true, // <-- ĐỔI THÀNH TRUE
    },
  };
});
