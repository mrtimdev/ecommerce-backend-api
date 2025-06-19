<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { ref, reactive, computed, watch } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();
const breadcrumbs = reactive([
  {
    label: "Home",
    url: route("dashboard"),
  },
  {
    label: t("products"),
    url: null,
  },
  {
    label: t("list"),
    url: null,
    is_active: true,
  },
]);
defineProps({
  categories: Array,
  units: Array,
});

const form = useForm({
  code: "",
  name: "",
  category_id: "",
  unit_id: "",
  price: "",
  stock_alert: "",
  is_active: true,
});

const submit = () => {
  form.post(route("products.store"));
};
</script>
<template>
  <DefaultLayout>
    <Head title="Create Product" />

    <div
      class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
    >
      <Bc :crumbs="breadcrumbs" />
      <div class="flex items-center gap-2 justify-center">
        <button
          v-if="mainStore.selectedRows.length"
          @click="btnDeleteSelected"
          class="text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
        >
          <i class="fi fi-rr-trash w-4 h-4 me-2"></i>
          {{ $t("delete") }}
        </button>
        <Link
          :href="route('products.create')"
          class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
        >
          <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
          {{ $t("add") }}
        </Link>
      </div>
    </div>

    <div class="content-body p-5">
      <div class="relative">
        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label
              for="code"
              class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
              >Product Code</label
            >
            <input
              id="code"
              v-model="form.code"
              type="text"
              class="form-input w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200 ease-in-out"
              placeholder="e.g., PROD001"
            />
          </div>
          <div>
            <label
              for="name"
              class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
              >Product Name</label
            >
            <input
              id="name"
              v-model="form.name"
              type="text"
              class="form-input w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200 ease-in-out"
              placeholder="e.g., Wireless Mouse"
            />
          </div>
          <div>
            <label
              for="category"
              class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
              >Category</label
            >
            <select
              id="category"
              v-model="form.category_id"
              class="form-select w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent dark:bg-gray-700 dark:text-white appearance-none pr-8 transition duration-200 ease-in-out"
            >
              <option disabled value="">-- Select Category --</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">
                {{ c.name }}
              </option>
            </select>
          </div>
          <div>
            <label
              for="unit"
              class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
              >Unit</label
            >
            <select
              id="unit"
              v-model="form.unit_id"
              class="form-select w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent dark:bg-gray-700 dark:text-white appearance-none pr-8 transition duration-200 ease-in-out"
            >
              <option disabled value="">-- Select Unit --</option>
              <option v-for="u in units" :key="u.id" :value="u.id">{{ u.name }}</option>
            </select>
          </div>
          <div>
            <label
              for="price"
              class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
              >Price</label
            >
            <input
              id="price"
              v-model="form.price"
              type="number"
              step="0.01"
              class="form-input w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200 ease-in-out"
              placeholder="0.00"
            />
          </div>
          <div>
            <label
              for="stock_alert"
              class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
              >Stock Alert Threshold</label
            >
            <input
              id="stock_alert"
              v-model="form.stock_alert"
              type="number"
              class="form-input w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200 ease-in-out"
              placeholder="e.g., 10"
            />
          </div>
          <div class="col-span-full flex items-center gap-3 mt-2">
            <input
              id="is_active"
              v-model="form.is_active"
              type="checkbox"
              class="h-5 w-5 text-purple-600 dark:text-purple-500 rounded border-gray-300 dark:border-gray-600 focus:ring-purple-500 transition duration-200 ease-in-out"
            />
            <label
              for="is_active"
              class="text-base font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
              >Product is Active</label
            >
          </div>
          <div class="col-span-full flex justify-end mt-4">
            <button
              type="submit"
              class="bg-purple-600 text-white px-8 py-3 rounded-xl hover:bg-purple-700 dark:hover:bg-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-700 font-bold text-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5"
              :disabled="form.processing"
            >
              {{ form.processing ? "Saving..." : "Save Product" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DefaultLayout>
</template>
