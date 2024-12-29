import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            vue: 'vue/dist/vue.esm-bundler.js',
            'ziggy-js': path.resolve('vendor/tightenco/ziggy/dist/vue.es.js')
        },
    },
    optimizeDeps: {
        include: ['vue', '@inertiajs/vue3'],
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue', '@inertiajs/vue3','element-plus'],
                },
                assetFileNames: (assetInfo) => {
                    return assetInfo.name == 'app.css' ? 'assets/app.css' : 'assets/'+assetInfo.name;
                }
            }
        },
        minify: process.env.APP_ENV !== 'local' ? true : false,
        cssCodeSplit: process.env.APP_ENV === 'local' ? false : undefined
    },
    server: {
        hmr: {
            overlay: false
        }
    }
});
