<script setup>
import { Head, Link, router } from "@inertiajs/vue3"; // Import router for search/filters
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"; // Keep if used for other parts of the app, though DefaultLayout is primary here
import Pagination from "@/Components/Pagination.vue";
import {
  onMounted,
  onBeforeUnmount,
  reactive,
  h,
  createApp,
  ref,
  watch,
  computed,
} from "vue"; // Add 'watch'
// Assuming these are globally available or imported in your app setup
// import { i18n } from "@/i18n";
// import { events } from "@/plugins/events"; // Assuming you have an event bus for confirm dialogs
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useMainStore } from "@/stores/main";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import Checkbox from "@/Components/Checkbox.vue"; // Assuming you have a reusable Checkbox component
import TextInput from "@/Components/TextInput.vue"; // Assuming you have a reusable TextInput component
import SelectInput from "@/Components/SelectInput.vue"; // Assuming you have a reusable SelectInput component

const props = defineProps({
  stocks: Object, // Changed to Object as it will contain data, links, etc. from pagination
  filters: Object, // To receive current filter values from the backend
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

// Search & Filter State
const search = ref(props.filters.search || "");
const client_id = ref(props.filters.client_id || "");
const payment_status = ref(props.filters.payment_status || "");
const stock_type = ref(props.filters.stock_type || "");

// Debounce search input
let searchTimeout = null;
watch(search, (newSearch) => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 300); // Debounce by 300ms
});

watch([client_id, payment_status, stock_type], () => {
  applyFilters();
});

const applyFilters = () => {
  router.get(
    route("stocks.index"),
    {
      search: search.value,
      client_id: client_id.value,
      payment_status: payment_status.value,
      stock_type: stock_type.value,
    },
    {
      preserveState: true, // Keep the current scroll position and history state
      replace: true, // Replace the current history entry
    }
  );
};

const resetFilters = () => {
  search.value = "";
  client_id.value = "";
  payment_status.value = "";
  stock_type.value = "";
  router.get(route("stocks.index"), {}, { preserveState: true, replace: true });
};

const btnDeleteSelected = () => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : [];
  // Assuming 'events' is globally available or imported
  // Make sure your event bus and confirmation dialog logic is in place
  if (typeof events !== "undefined" && events.emit) {
    events.emit("confirm:open", items);
  } else {
    // Fallback if event bus is not configured/available
    if (confirm("Are you sure you want to delete the selected items?")) {
      router.delete(route("stocks.destroy.batch"), { data: { ids: items } });
    }
  }
};

const toggleAllCheckboxes = (event) => {
  if (event.target.checked) {
    mainStore.selectedRows = props.stocks.data.map((stock) => stock.id);
  } else {
    mainStore.clearSelectedRows();
  }
};

const isAllSelected = computed(() => {
  return (
    props.stocks.data.length > 0 &&
    mainStore.selectedRows.length === props.stocks.data.length
  );
});

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
            class="text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-red-300 transition duration-200"
          >
            <i class="fi fi-rr-trash w-4 h-4 me-2"></i>
            {{ $t("delete") }} ({{ mainStore.selectedRows.length }})
          </button>
          <Link
            :href="route('stocks.create')"
            class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300 transition duration-200"
          >
            <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
            {{ $t("add") }}
          </Link>
        </div>
      </div>

      <div class="content-body p-5 bg-white dark:bg-gray-800 rounded-b-md shadow-md">
        <div class="relative">
          <div class="flex flex-col md:flex-row gap-4 mb-6">
            <TextInput
              v-model="search"
              type="text"
              class="w-full md:w-1/3"
              :placeholder="$t('search_by_client_or_note')"
              aria-label="Search"
            >
              <template #icon>
                <i class="fi fi-rr-search text-gray-400 dark:text-gray-500"></i>
              </template>
            </TextInput>

            <SelectInput
              v-model="client_id"
              class="w-full md:w-1/4"
              :placeholder="$t('filter_by_client')"
            >
              <option value="">{{ $t("all_clients") }}</option>
              <option v-for="client in props.clients" :key="client.id" :value="client.id">
                {{ client.name }}
              </option>
            </SelectInput>

            <SelectInput
              v-model="payment_status"
              class="w-full md:w-1/4"
              :placeholder="$t('filter_by_status')"
            >
              <option value="">{{ $t("all_statuses") }}</option>
              <option value="unpaid">{{ $t("unpaid") }}</option>
              <option value="partial">{{ $t("partial_payment") }}</option>
              <option value="paid">{{ $t("paid") }}</option>
            </SelectInput>

            <SelectInput
              v-model="stock_type"
              class="w-full md:w-1/4"
              :placeholder="$t('filter_by_type')"
            >
              <option value="">{{ $t("all_types") }}</option>
              <option value="purchase">{{ $t("purchase") }}</option>
              <option value="delivery">{{ $t("delivery") }}</option>
            </SelectInput>

            <button @click="resetFilters" class="btn-secondary px-4 py-2.5 text-nowrap">
              <i class="fi fi-rr-refresh w-4 h-4 me-2"></i>
              {{ $t("reset") }}
            </button>
          </div>

          <div
            class="overflow-x-auto shadow-sm rounded-lg border border-gray-200 dark:border-gray-700"
          >
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
              <thead
                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
              >
                <tr>
                  <th scope="col" class="p-4">
                    <Checkbox @change="toggleAllCheckboxes" :checked="isAllSelected" />
                  </th>
                  <th scope="col" class="px-6 py-3">{{ $t("client") }}</th>
                  <th scope="col" class="px-6 py-3">{{ $t("date") }}</th>
                  <th scope="col" class="px-6 py-3">{{ $t("type") }}</th>
                  <th scope="col" class="px-6 py-3 text-right">
                    {{ $t("total_amount") }}
                  </th>
                  <th scope="col" class="px-6 py-3 text-right">
                    {{ $t("paid_amount") }}
                  </th>
                  <th scope="col" class="px-6 py-3 text-right">{{ $t("due_amount") }}</th>
                  <th scope="col" class="px-6 py-3">{{ $t("status") }}</th>
                  <th scope="col" class="px-6 py-3">{{ $t("note") }}</th>
                  <th scope="col" class="px-6 py-3 text-right">{{ $t("actions") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="stock in stocks.data"
                  :key="stock.id"
                  class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                >
                  <td class="w-4 p-4">
                    <Checkbox
                      v-model:checked="mainStore.selectedRows"
                      :value="stock.id"
                    />
                  </td>
                  <th
                    scope="row"
                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                  >
                    {{ stock.client ? stock.client.name : "N/A" }}
                  </th>
                  <td class="px-6 py-4">{{ stock.date }}</td>
                  <td class="px-6 py-4 capitalize">{{ stock.stock_type || "N/A" }}</td>
                  <td class="px-6 py-4 text-right">
                    {{ parseFloat(stock.total_amount).toFixed(2) }}
                  </td>
                  <td class="px-6 py-4 text-right">
                    {{ parseFloat(stock.paid_amount).toFixed(2) }}
                  </td>
                  <td class="px-6 py-4 text-right">
                    {{ parseFloat(stock.due_amount).toFixed(2) }}
                  </td>
                  <td class="px-6 py-4">
                    <span
                      :class="{
                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300':
                          stock.payment_status === 'paid',
                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300':
                          stock.payment_status === 'partial',
                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300':
                          stock.payment_status === 'unpaid',
                      }"
                      class="px-2.5 py-0.5 rounded-full text-xs font-medium"
                    >
                      {{ $t(stock.payment_status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4">{{ stock.note || "N/A" }}</td>
                  <td class="px-6 py-4 text-right">
                    <Link
                      :href="route('stocks.show', stock.id)"
                      class="font-medium text-purple-600 dark:text-purple-500 hover:underline me-3"
                    >
                      <i class="fi fi-rr-eye w-4 h-4"></i>
                    </Link>
                    <Link
                      :href="route('stocks.edit', stock.id)"
                      class="font-medium text-blue-600 dark:text-blue-500 hover:underline me-3"
                    >
                      <i class="fi fi-rr-edit w-4 h-4"></i>
                    </Link>
                    <button
                      @click="events.emit('confirm:open', [stock.id])"
                      class="font-medium text-red-600 dark:text-red-500 hover:underline"
                    >
                      <i class="fi fi-rr-trash w-4 h-4"></i>
                    </button>
                  </td>
                </tr>
                <tr v-if="stocks.data.length === 0">
                  <td
                    colspan="10"
                    class="px-6 py-4 text-center text-gray-500 dark:text-gray-400"
                  >
                    {{ $t("no_stocks_found") }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="stocks.links.length > 3" class="mt-4 flex justify-center">
            <Pagination :links="stocks.links" />
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<style scoped>
/* Your existing form-related styles might be in a separate file or global, but here are general table/layout styles */
/* Add any specific styles for table or filter elements if needed */

.btn-secondary {
  @apply px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm text-center inline-flex items-center dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-700 dark:focus:ring-gray-700 transition duration-200;
}
</style>
