<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";

import Label from "@/Components/Others/Label.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import { getCurrentInstance, nextTick } from "vue";

import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { events } from "@/events";
const { proxy } = getCurrentInstance();
import { useHelpers } from "@/helpers/useHelpers";
const { isRole, isPermission } = useHelpers();
const user = usePage().props.auth.user;
const props = defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  avatar: null,
});

const handleSubmit = () => {
  form.post(route("users.update-avatar", user.id), {
    preserveScroll: true,
    onSuccess: (res) => {
      nextTick(() => {
        router.get(route("profile.edit"));
      });
    },
    onError: () => {},
  });
};
</script>

<template>
  <div>
    <Head title="Profile" />

    <DefaultLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
          <div class="p-4 sm:p-8 shadow sm:rounded-lg">
            <div
              class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 mb-4"
            >
              <div>
                <UpdateProfileInformationForm
                  :must-verify-email="mustVerifyEmail"
                  :status="status"
                  class="max-w-xl"
                  v-if="isRole('owner')"
                />
                <div v-else>
                  <h3 class="text-lg font-medium text-gray-600 dark:text-gray-200 mb-4">
                    {{ $t("profile_information") }}
                  </h3>
                  <table class="no-bg-tr table w-full text-sm text-left rtl:text-right">
                    <tbody
                      class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark"
                    >
                      <tr>
                        <td class="py-2 px-4 font-sm">{{ $t("first_name") }}:</td>
                        <td class="py-2 px-4">
                          {{ user.first_name }}
                        </td>
                      </tr>
                      <tr>
                        <td class="py-2 px-4 font-sm">{{ $t("last_name") }}:</td>
                        <td class="py-2 px-4">
                          {{ user.last_name }}
                        </td>
                      </tr>
                      <tr>
                        <td class="py-2 px-4 font-sm">{{ $t("email") }}:</td>
                        <td class="py-2 px-4">
                          {{ user.email }}
                        </td>
                      </tr>
                      <tr>
                        <td class="py-2 px-4 font-sm">{{ $t("phone") }}:</td>
                        <td class="py-2 px-4">
                          {{ user.phone }}
                        </td>
                      </tr>
                      <tr>
                        <td class="py-2 px-4 font-sm">{{ $t("facebook_link") }}:</td>
                        <td class="py-2 px-4">
                          <a
                            :href="user.facebook_link"
                            class="hover:underline text-blue-700"
                            target="_blank"
                          >
                            <i class="fi fi-rr-link text-[12px]"></i> {{ $t("open") }}
                          </a>
                        </td>
                      </tr>
                      <tr>
                        <td class="py-2 px-4 font-sm">{{ $t("telegram_link") }}:</td>
                        <td class="py-2 px-4">
                          <a
                            :href="user.telegram_link"
                            class="hover:underline text-blue-700"
                            target="_blank"
                          >
                            <i class="fi fi-rr-link text-[12px]"></i> {{ $t("open") }}
                          </a>
                        </td>
                      </tr>
                      <tr>
                        <td class="py-2 px-4 font-sm">{{ $t("whatapp_link") }}:</td>
                        <td class="py-2 px-4">
                          <a
                            :href="user.whatapp_link"
                            class="hover:underline text-blue-700"
                            target="_blank"
                          >
                            <i class="fi fi-rr-link text-[12px]"></i> {{ $t("open") }}
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div>
                <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
                  <div>
                    <Label
                      for_id="avatar"
                      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                      >{{ $t("avatar") }}</Label
                    >
                    <FileUpload
                      v-model="form.avatar"
                      target_input="avatar"
                      :selectedFile="user.avatar"
                    />
                    <InputError :message="form.errors.avatar" class="mt-2" />
                  </div>
                  <div class="button-group mt-4">
                    <div class="flex justify-start gap-5 items-center">
                      <input
                        type="submit"
                        :value="$t('update')"
                        class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
                      />
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="p-4 sm:p-8 shadow sm:rounded-lg" v-if="isRole('owner')">
            <UpdatePasswordForm class="max-w-xl" />
          </div>

          <div class="p-4 sm:p-8 shadow sm:rounded-lg" v-if="isRole('owner')">
            <DeleteUserForm class="max-w-xl" />
          </div>
        </div>
      </div>
    </DefaultLayout>
  </div>
</template>
