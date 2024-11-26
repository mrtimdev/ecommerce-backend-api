<template>
    <LayoutTab>
        <div class="">
            <div class="content-body mt-6">
                <div class="relative">
                    <div class="grid gap-6 mb-6 md:grid-cols-1">
                        <div>
                            <fieldset class="border border-gray-300 dark:border-gray-400 p-6 rounded-lg">
                                <legend class="block text-sm font-medium text-gray-900 dark:text-white">{{ $t('shipping_config') }}</legend>
                                <form @submit.prevent=" saveShipping ">
                                    <div class="">
                                        <div>
                                            <Label for_id="shipping"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                                $t("shipping") }}</Label>
                                                <input
                                                type="text"
                                                ref="shipping"
                                                id="shipping"
                                                v-model="shippingForm.shipping"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                                                :placeholder="$t('shipping_amount')"
                                                />
                                            <InputError :message=" form.errors.shipping " class="mt-2" />
                                        </div>
                                    </div>
        
                                    <div class="mt-6">
                                        <div class="flex justify-start items-center">
                                            <button
                                                class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                                                {{ $t("save") }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                        <div>
                            <fieldset class="border border-gray-300 dark:border-gray-400 p-6 rounded-lg">
                                <legend class="block text-sm font-medium text-gray-900 dark:text-white">{{ $t('login_config') }}</legend>
                                <form @submit.prevent=" saveLoginLogo " enctype="multipart/form-data">
                                    <div class="">
                                        <div>
                                            <Label for_id="login_logo"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                                $t("login_logo") }}</Label>
                                            <FileUpload v-model=" form.login_logo " target_input="login_logo"
                                                :selectedFile=" setting.login_logo " />
                                            <InputError :message=" form.errors.login_logo " class="mt-2" />
                                        </div>
                                    </div>
        
                                    <div class="mt-6">
                                        <div class="flex justify-start items-center">
                                            <button
                                                class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                                                {{ $t("save") }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                        
                        <div>
                            <fieldset class="border border-gray-300 dark:border-gray-400 p-6 rounded-lg">
                                <legend class="block text-sm font-medium text-gray-900 dark:text-white">{{ $t('site_config') }}</legend>
                                <form @submit.prevent=" saveLoginSiteLogo " enctype="multipart/form-data">
                                    <div class="">
                                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                                            <div>
                                                <Label :isRequired="true"
                                                for_id="site_name"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                                                >{{ $t("site_name") }}</Label
                                                >
                                                <input
                                                type="text"
                                                ref="site_name"
                                                id="site_name"
                                                v-model="siteForm.site_name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                                                :placeholder="$t('site_name')"
                                                />
                                                <InputError :message="siteForm.errors.site_name" class="mt-2" />
                                            </div>
                                            <div>
                                                <Label :isRequired="true"
                                                for_id="site_link"
                                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                                                >{{ $t("site_link") }}</Label
                                                >
                                                <input
                                                type="text"
                                                ref="site_link"
                                                id="site_link"
                                                v-model="siteForm.site_link"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                                                :placeholder="$t('site_link')"
                                                />
                                                <InputError :message="siteForm.errors.site_link" class="mt-2" />
                                            </div>
                                        </div>
                                        <div>
                                            <Label for_id="site_logo"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                                $t("site_logo") }}</Label>
                                            <FileUpload v-model=" siteForm.site_logo " target_input="site_logo"
                                                :selectedFile=" props.setting.site_logo " />
                                            <InputError :message=" siteForm.errors.site_logo " class="mt-2" />
                                        </div>
                                    </div>
        
                                    <div class="mt-6">
                                        <div class="flex justify-start items-center">
                                            <button
                                                class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                                                {{ $t("save") }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
    </LayoutTab>
</template>

<script setup>
import InputError from "@/Components/InputError.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import Label from "@/Components/Others/Label.vue";
import LayoutTab from "./Layout/Index.vue";
import { ref, reactive } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { events } from "@/events";
import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();

const props = defineProps({
    setting: {
        type: Object,
        required: true,
    },
});

const shippingForm = useForm({
    shipping: props.setting.shipping,
});

const form = useForm({
    login_logo: null,
});
const siteForm = useForm({
    site_logo: null,
    site_name: props.setting.site_name ?? "",
    site_link: props.setting.site_link ?? ""
})
const saveShipping = () => {
    shippingForm.post(route("settings.shipping"), {
        onFinish: () => {
            shippingForm.reset("shipping");
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
}
const saveLoginLogo = () => {
    form.post(route("settings.login_logo"), {
        onFinish: () => {
            console.log("Logo uploaded successfully");
            form.reset("login_logo");
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
}

const saveLoginSiteLogo = () => {
    siteForm.post(route("settings.site_configs"), {
        onSuccess: () => {
            mainStore.loadSetting()
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};

</script>
