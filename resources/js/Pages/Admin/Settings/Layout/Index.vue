<template>
    <DefaultLayout>
        <div class="container">
            <div class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between">
                <Bc :crumbs="breadcrumbs" />
                
            </div>

            <div class="content-body p-5">
                <div class="relative">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                           
                            <li class="me-2">
                                <Link 
                                    :href="route('settings.index')" 
                                    :class="{ 'text-purple-600 border-purple-600 dark:text-purple-500 dark:border-purple-500': route().current('settings.index') }"
                                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg active group" aria-current="page">
                                    <svg
                                        :class="{ 'text-purple-600 dark:text-purple-500': route().current('settings.index') }" 
                                        class="w-4 h-4 me-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                        <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                                    </svg>
                                    {{ $t('settings') }}
                                </Link>
                            </li>
                            <li class="me-2">
                                <Link 
                                    :href="route('settings.countries.index')" 
                                    :class="{ 'text-purple-600 border-purple-600 dark:text-purple-500 dark:border-purple-500': route().current('settings.countries.index') }"
                                    class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg active group" aria-current="page">
                                    <svg
                                        :class="{ 'text-purple-600 dark:text-purple-500': route().current('settings.countries.index') }" 
                                        class="w-4 h-4 me-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                        <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                                    </svg>
                                    {{ $t('countries') }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <slot></slot>
                    </div>

                </div>
            </div>  
        </div>
    </DefaultLayout>
</template>

<script setup>

import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { ref,reactive } from "vue";
import { usePage, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const breadcrumbs = reactive([
    {   label: 'Home', url: route('dashboard') },
    {   label: t('admin'), url: null },
    {   label: t('settings'), url: null } ,
    {   label: t('configurations'), url: null } ,
])

const url = usePage().props

</script>
