<template>
    <DefaultLayout>
        <div class="container">
            <div class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between">
                <Bc :crumbs="breadcrumbs" />
                <div class="flex items-center gap-2 justify-center">
     
                    <button @click=" openModalFormAdd('add', false) " class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300">
                        <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
                        {{ $t('add') }}
                    </button>
                </div>
            </div>
    
            <div class="content-body p-5">
                <div class="relative">
                    <div v-if="login_logo">
                        <h2>Current Logo</h2>
                        <img :src="`/storage/${login_logo}`" alt="Login Logo" width="200">
                    </div>
                    <form @submit.prevent="uploadLogo" enctype="multipart/form-data">
                        <input type="file" @input="form.login_logo = $event.target.files[0]" />
                        <button type="submit">Submit</button>
                    </form>
                </div>
            </div>  
        </div>
    </DefaultLayout>
</template>

<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { ref,reactive } from "vue";
import { useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const breadcrumbs = reactive([
    {   label: 'Home', url: route('dashboard') },
    {   label: t('admin'), url: null },
    {   label: t('Frontend'), url: null } ,
    {   label: t('Home Page'), url: null } ,
])

defineProps({
    login_logo: {
        type: String,
        required: true,
        default: '/assets/images/login.png',
    }
})

const form = useForm({
  login_logo: null,
})


const uploadLogo = () => {
    form.post(route("settings.upload"), {
        onFinish: () => {
            console.log("Logo uploaded successfully");
            form.reset('login_logo');
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};


</script>
