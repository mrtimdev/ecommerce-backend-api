<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { onMounted, onBeforeUnmount, reactive, h, createApp, ref } from "vue";
import { i18n } from "@/i18n";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useMainStore } from "@/stores/main";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

defineProps({
  stocks: Array,
});
const mainStore = useMainStore();
const breadcrumbs = reactive([
  {
    label: "Home",
    url: route("dashboard"),
  },
  {
    label: t("stocks"),
    url: null,
  },
  {
    label: t("list"),
    url: null,
    is_active: true,
  },
]);
const btnDeleteSelected = () => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : [];
  events.emit("confirm:open", items);
};
onBeforeUnmount(() => mainStore.clearSelectedRows());
</script>
<template>
  <DefaultLayout>
    <Head :title="$t('stocks')" />
    <div class="container">
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
            :href="route('stocks.create')"
            class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
          >
            <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
            {{ $t("add") }}
          </Link>
        </div>
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Client
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Product
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Quantity
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Price
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="stock in stocks" :key="stock.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ stock.client.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ stock.product.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ stock.quantity }} {{ stock.unit }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${{ stock.price }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <Link
                    :href="`/stocks/${stock.id}/edit`"
                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                    >Edit</Link
                  >
                  <button class="text-red-600 hover:text-red-900">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>
