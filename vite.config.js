import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import svgLoader from 'vite-svg-loader';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            ssr: 'resources/js/ssr.js',
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
        svgLoader(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern',
                silenceDeprecations: ["legacy-js-api"],
                sassOptions: {
                    quietDeps: false
                }
            },
            sass: {
                silenceDeprecations: ['slash-div'],
            },
        },
    },
});
