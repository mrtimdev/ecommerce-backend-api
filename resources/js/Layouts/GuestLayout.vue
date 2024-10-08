<script setup>
    import { Link } from '@inertiajs/vue3';
    defineProps({
        login_logo: {
            type: String,
            required: false,
            default: 'https://example.com/logo.png'  // Example logo URL. Replace with your actual logo URL.
        }
    });
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <Link href="/">
                <img :src="`/storage/${login_logo}`" class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>

