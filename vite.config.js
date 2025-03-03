import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',     // コンテナ内から見えるように
        port: 5173,          // Viteデフォルトポート
        strictPort: true,
        hmr: {
            host: 'localhost', // ホストからアクセスできるように
            port: 5173,
        },
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
});
