<template>
  <LayoutTab>
    <Head :title="$t('frontend.contactusnow')" />
    <div class="">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 px-0 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <div class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
          <h2
            class="text-md font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white"
          >
            {{ $t("contact-us") }}
          </h2>
        </div>
      </div>
      <div class="relative">
        <form
          @submit.prevent="contactUsNowStore"
          enctype="multipart/form-data"
          class="mt-4"
        >
          <div
            class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3"
          >
            <div>
              <Label
                for_id="title"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >{{ $t("title") }}</Label
              >
              <input
                type="text"
                id="title"
                ref="title"
                v-model="form.title"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder="$t('title')"
              />
              <InputError :message="form.errors.title" class="mt-2" />
            </div>
            <div>
              <Label
                for_id="name"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >{{ $t("name") }}</Label
              >
              <input
                type="text"
                id="name"
                ref="name"
                v-model="form.name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder="$t('name')"
              />
              <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div>
              <Label
                for_id="phone"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >{{ $t("phone") }}</Label
              >
              <input
                type="text"
                id="phone"
                ref="phone"
                v-model="form.phone"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder="$t('phone')"
              />
              <InputError :message="form.errors.phone" class="mt-2" />
            </div>
            <div>
              <Label
                for_id="telegram_link"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >{{ $t("telegram_link") }}</Label
              >
              <input
                type="text"
                id="telegram_link"
                ref="telegram_link"
                v-model="form.telegram_link"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder="$t('telegram_link')"
              />
              <InputError :message="form.errors.telegram_link" class="mt-2" />
            </div>
            <div>
              <Label
                for_id="facebook_link"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >{{ $t("facebook_link") }}</Label
              >
              <input
                type="text"
                id="facebook_link"
                ref="facebook_link"
                v-model="form.facebook_link"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder="$t('facebook_link')"
              />
              <InputError :message="form.errors.facebook_link" class="mt-2" />
            </div>
            <div>
              <Label
                for_id="whatapp_link"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >{{ $t("whatapp_link") }}</Label
              >
              <input
                type="text"
                id="whatapp_link"
                ref="whatapp_link"
                v-model="form.whatapp_link"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder="$t('whatapp_link')"
              />
              <InputError :message="form.errors.whatapp_link" class="mt-2" />
            </div>
          </div>

          <div class="mt-4 mb-4">
            <Label
              for_id="avatar"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >{{ $t("avatar") }}</Label
            >
            <FileUpload
              v-model="form.avatar"
              target_input="avatar"
              :selectedFile="contactusnow.avatar"
            />
            <InputError :message="form.errors.avatar" class="mt-2" />
          </div>

          <div class="button-group mt-4">
            <div class="flex justify-start gap-5 items-center">
              <input
                type="submit"
                :value="$t('save')"
                class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
              />
            </div>
          </div>
        </form>
      </div>
    </div>
    <AddOrEdit :show-modal="false" />
  </LayoutTab>
</template>

<script setup>
import FileUpload from "@/Components/Others/FileUpload.vue";
import LayoutTab from "../../Layout/Index.vue";
import Label from "@/Components/Others/Label.vue";
import AddOrEdit from "../Videos/AddOrEdit.vue";

import { useForm, Head } from "@inertiajs/vue3";

const props = defineProps({
  contactusnow: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  title: props.contactusnow.title,
  phone: props.contactusnow.phone,
  name: props.contactusnow.name,
  telegram_link: props.contactusnow.telegram_link,
  facebook_link: props.contactusnow.facebook_link,
  whatapp_link: props.contactusnow.whatapp_link,
  contact_label: props.contactusnow.contact_label,
  avatar: null,
  type: "korea",
});

const contactUsNowStore = () => {
  form.post(route("frontend.page.agencycontact.store"), {
    onSuccess: () => {},
    onError: () => {},
  });
};
</script>
