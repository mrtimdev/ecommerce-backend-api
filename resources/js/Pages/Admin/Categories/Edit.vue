<template>
  <DefaultLayout>
    <Head :title="$t('category.edit')" />
    <div class="container">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <Bc :crumbs="breadcrumbs" />
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <form
            @submit.prevent="store.updateCategory(category)"
            enctype="multipart/form-data"
          >
            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div>
                <label
                  for="code"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("code") }}</label
                >
                <input
                  autofocus
                  type="text"
                  ref="code"
                  id="code"
                  v-model="store.form.code"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('category.code')"
                />
                <InputError :message="store.form.errors.code" class="mt-2" />
              </div>

              <div>
                <label
                  for="name"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("name") }}</label
                >
                <input
                  type="text"
                  ref="name"
                  id="name"
                  v-model="store.form.name"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('category.name')"
                />
                <InputError :message="store.form.errors.name" class="mt-2" />
              </div>
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div>
                <div>
                  <label
                    for="slug"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                    >{{ $t("slug") }}</label
                  >
                  <input
                    type="text"
                    ref="slug"
                    id="slug"
                    v-model="store.form.slug"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder="$t('category.slug')"
                  />
                  <InputError :message="store.form.errors.slug" class="mt-2" />
                </div>
              </div>

              <div>
                <label
                  for="status"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("select_status") }}</label
                >
                <select
                  v-model="store.form.is_active"
                  id="status"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                >
                  <option selected>{{ $t("select_status") }}</option>
                  <option :value="true">{{ $t("active") }}</option>
                  <option :value="false">{{ $t("inactive") }}</option>
                </select>
                <InputError :message="store.form.errors.is_active" class="mt-2" />
              </div>
              <div>
                <FileUpload
                  :multiple="false"
                  v-model="store.form.image_path"
                  :selectedFile="props.category.image_path"
                />
                <InputError :message="store.form.errors.image_path" class="mt-2" />
              </div>
            </div>
            <div class="modal-footer">
              <div class="flex justify-start gap-5 items-center">
                <Link
                  :href="route('categories.index')"
                  class="focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900"
                  >{{ $t("cancel") }}</Link
                >
                <input
                  type="submit"
                  :value="$t('update')"
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
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import { Head, Link } from "@inertiajs/vue3";
import { reactive, watch, onBeforeUnmount, onMounted } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import useHelper from "@/composables/useHelper";
const { generateSlug } = useHelper();

import { useCategory } from "@/stores/category";
const props = defineProps({
  category: { type: Object, required: true },
});
const store = useCategory();
onMounted(() => store.setItemValue(props.category));
onBeforeUnmount(store.resetForm);

const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("categories"), url: "/admin/categories" },
  { label: t("update"), url: null, is_active: true },
]);

watch([() => store.form.name, () => store.form.code], ([newName, newCode]) => {
  store.form.slug = generateSlug(newCode, newName);
});
</script>
