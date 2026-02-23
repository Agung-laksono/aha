import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// default
// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
// });

// tambahan/modifikasi untuk bisa di akses dari device lain dengan jaringan wifi
export default defineConfig({
    server: {
        host: '0.0.0.0',
        hmr: {
            host: '192.168.18.8',
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
