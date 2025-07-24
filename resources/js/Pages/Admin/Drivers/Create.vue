<template>
  <DefaultLayout>
    <Head :title="$t('drivers.add')" />
    <div class="container">
      <div
        class="content-header p-5 rounded-tl-md rounded-tr-md py-4 bg-white dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <div
          class="flex justify-start items-center gap-1 text-sm font-medium text-gray-700 dark:text-white"
        >
          <i class="fi fi-rr-plus w-4 h-4"></i> {{ $t("drivers.add") }}
        </div>
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
            <div class="grid grid-cols-12 gap-4">
              <!-- Column -->
              <div
                class="col-span-12 md:col-span-8 p-4 border dark:border-gray-700 rounded-lg"
              >
                <div
                  class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 mb-4"
                >
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="first_name"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("first_name") }}</Label
                    >
                    <input
                      type="text"
                      ref="first_name"
                      id="first_name"
                      v-model="form.first_name"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('first_name')"
                    />
                    <InputError :message="form.errors.first_name" class="mt-2" />
                  </div>
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="last_name"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("last_name") }}</Label
                    >
                    <input
                      type="text"
                      ref="last_name"
                      id="last_name"
                      v-model="form.last_name"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('last_name')"
                    />
                    <InputError :message="form.errors.last_name" class="mt-2" />
                  </div>
                </div>

                <div class="mb-4">
                  <Label
                    :isRequired="true"
                    for_id="username"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                    >{{ $t("username") }}</Label
                  >
                  <input
                    type="text"
                    ref="username"
                    id="username"
                    v-model="form.username"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder="$t('username')"
                  />
                  <InputError :message="form.errors.username" class="mt-2" />
                </div>

                <div
                  class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 mb-4"
                >
                  <div>
                    <Label
                      :isRequired="true"
                      for_id="email"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("email") }}</Label
                    >
                    <input
                      type="text"
                      ref="email"
                      id="email"
                      v-model="form.email"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('email')"
                    />
                    <InputError :message="form.errors.email" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      for_id="phone"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("phone") }}</Label
                    >
                    <input
                      type="text"
                      ref="phone"
                      id="phone"
                      v-model="form.phone"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('phone')"
                    />
                    <InputError :message="form.errors.phone" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="password"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("password") }}</Label
                    >
                    <input
                      type="text"
                      ref="password"
                      id="password"
                      v-model="form.password"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('password')"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                  </div>

                  <div>
                    <Label
                      :isRequired="true"
                      for_id="password_confirmation"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("confirm_password") }}</Label
                    >
                    <input
                      type="text"
                      ref="password_confirmation"
                      id="password_confirmation"
                      v-model="form.password_confirmation"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('confirm_password')"
                    />
                    <InputError
                      :message="form.errors.password_confirmation"
                      class="mt-2"
                    />
                  </div>
                </div>
              </div>

              <!-- Column -->
              <div
                class="col-span-12 md:col-span-4 p-4 border dark:border-gray-700 rounded-lg"
              >
                <div>
                  <Label
                    for_id="avatar"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >{{ $t("avatar") }}</Label
                  >
                  <FileUpload v-model="form.avatar" target_input="avatar" />
                  <InputError :message="form.errors.avatar" class="mt-2" />
                </div>

                <div class="">
                  <Label
                    :isRequired="true"
                    for_id="is_active"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >
                    {{ $t("is_active") }}</Label
                  >
                  <select
                    v-model="form.is_active"
                    id="is_active"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                  >
                    <option :value="true">{{ $t("active") }}</option>
                    <option :value="false">{{ $t("inactive") }}</option>
                  </select>
                  <InputError :message="form.errors.is_active" class="mt-2" />
                </div>
              </div>
            </div>

            <div class="button-group mt-4">
              <div class="flex justify-start gap-5 items-center">
                <Link
                  :href="route('drivers.index')"
                  class="focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900"
                >
                  {{ $t("cancel") }}
                </Link>
                <input
                  type="submit"
                  :value="$t('save')"
                  class="focus:outline-none text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-1 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-900"
                />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import "summernote/dist/summernote-lite.css";
import "summernote/dist/summernote-lite.js";
import Label from "@/Components/Others/Label.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { reactive, onMounted, nextTick } from "vue";
import { useI18n } from "vue-i18n";
import axios from "axios";
const { t } = useI18n();

import { events } from "@/events";
import { useMainStore } from "@/stores/main";
const mainStore = useMainStore();

const form = useForm({
  first_name: "",
  last_name: "",
  username: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
  avatar: null,
  is_active: true,
});

const handleSubmit = () => {
  form.post(route("drivers.store"), {
    preserveScroll: true,
    onSuccess: (res) => {
      events.emit("toaster", {
        type: "success",
        action: "create",
        message: `${t("driver")} [${form.first_name} ${form.last_name}] ${t(
          "successfully_added"
        )}`,
      });
    },
    onError: () => {},
  });
};
onMounted(() => {
  nextTick(() => {
    Array.from(document.querySelectorAll(".multiselect__tags")).forEach((element) => {
      element.classList.add(...mainStore.inputClasses);
    });
    Array.from(document.querySelectorAll(".multiselect__input")).forEach((element) => {
      element.classList.add(...mainStore.inputClasses);
    });
  });
});
</script>
