<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { ref, reactive, computed, watch } from "vue"; // Import 'watch'
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();
const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("stocks"), url: route("stocks.index") },
  { label: t("create"), url: null, is_active: true },
]);

const props = defineProps({
  clients: Array,
  // products: Array, // No longer directly passed, will be fetched via AJAX
});

const form = useForm({
  client_id: "",
  items: [],
  payment_status: "unpaid",
  date: new Date().toISOString().slice(0, 10),
  note: "",
});

// Search functionality
const searchQuery = ref("");
const selectedProduct = ref(null);
const quantity = ref(1);
const searchResults = ref([]); // To store products fetched from AJAX
const showSearchResults = ref(false); // Control visibility of search results

// Watch for changes in searchQuery and fetch products
let searchTimeout = null;
watch(searchQuery, (newQuery) => {
  if (newQuery.length > 2) {
    // Start searching after 2 characters
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(fetchProducts, 300); // Debounce search
  } else {
    searchResults.value = [];
    showSearchResults.value = false;
  }
});

const fetchProducts = async () => {
  try {
    const response = await axios.get(route("admin.products.ajax-products"), {
      params: { query: searchQuery.value },
    });
    searchResults.value = response.data;
    showSearchResults.value = true;
  } catch (error) {
    console.error("Error fetching products:", error);
    searchResults.value = [];
    showSearchResults.value = false;
  }
};

const selectProduct = (product) => {
  selectedProduct.value = product;
  searchQuery.value = product.name; // Set search query to selected product name for display
  showSearchResults.value = false; // Close search results
  addProduct();
};

const addProduct = () => {
  if (!selectedProduct.value || quantity.value < 1) return;

  const existingIndex = form.items.findIndex(
    (item) => item.product_id === selectedProduct.value.id
  );

  if (existingIndex >= 0) {
    form.items[existingIndex].quantity =
      parseFloat(form.items[existingIndex].quantity) + parseFloat(quantity.value);
  } else {
    form.items.push({
      product_id: selectedProduct.value.id,
      name: selectedProduct.value.name,
      code: selectedProduct.value.code,
      unit: selectedProduct.value.unit?.name,
      price: parseFloat(selectedProduct.value.price) || 0, // Ensure price is a number
      quantity: parseFloat(quantity.value), // Ensure quantity is a number
    });
  }

  // Reset selection and close search
  searchQuery.value = "";
  selectedProduct.value = null;
  quantity.value = 1;
  searchResults.value = []; // Clear search results after adding
  showSearchResults.value = false;
};

const removeItem = (index) => {
  form.items.splice(index, 1);
};

const submit = () => {
  form.post(route("stocks.store"), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};

// Computed totals
const subtotal = computed(() => {
  return form.items.reduce((sum, item) => sum + item.price * item.quantity, 0);
});

const grandTotal = computed(() => {
  return subtotal.value;
});
</script>

<template>
  <DefaultLayout>
    <Head title="Add Stock" />

    <div
      class="content-header rounded-t-lg p-5 border-b bg-white dark:bg-gray-800 dark:border-gray-700 flex justify-between items-center"
    >
      <Bc :crumbs="breadcrumbs" />
      <div class="flex gap-2">
        <Link :href="route('stocks.index')" class="btn-secondary">
          <i class="fi fi-rr-arrow-left mr-2"></i>
          Back
        </Link>
      </div>
    </div>

    <div class="content-body p-5">
      <div
        class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700"
      >
        <div class="p-6">
          <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-6">
            Add New Stock
          </h2>

          <form @submit.prevent="submit" class="space-y-6">
            <div>
              <label class="form-label">Client <span class="text-red-500">*</span></label>
              <select v-model="form.client_id" class="form-select" required>
                <option disabled value="">Select Client</option>
                <option
                  v-for="client in clients"
                  :key="client.id"
                  :value="client.id"
                  class="dark:bg-gray-700"
                >
                  {{ client.name }}
                </option>
              </select>
              <InputError class="mt-1" :message="form.errors.client_id" />
            </div>

            <div class="space-y-4">
              <label class="form-label">Add Products</label>

              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  class="form-input pr-10"
                  placeholder="Search or scan product..."
                  @focus="showSearchResults = true"
                />
                <span class="absolute right-3 top-3 text-gray-400">
                  <i class="fi fi-rr-search"></i>
                </span>

                <div
                  v-if="showSearchResults && searchResults.length"
                  class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-200 dark:border-gray-600 max-h-60 overflow-auto"
                >
                  <div
                    v-for="product in searchResults"
                    :key="product.id"
                    class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer"
                    @mousedown.prevent="selectProduct(product)"
                  >
                    <div class="font-medium">{{ product.name }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ product.code }} • {{ product.price }} ({{ product.unit?.name }})
                    </div>
                  </div>
                </div>
              </div>

              <div
                v-if="selectedProduct"
                class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg flex items-center justify-between"
              >
                <div>
                  <div class="font-medium">{{ selectedProduct.name }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    Price: {{ selectedProduct.price }} • Unit:
                    {{ selectedProduct.unit?.name }}
                  </div>
                </div>
                <div class="flex items-center space-x-2">
                  <input
                    v-model.number="quantity"
                    type="number"
                    min="1"
                    class="w-20 form-input text-center"
                  />
                  <button
                    type="button"
                    @click="addProduct"
                    class="btn-primary py-1 px-3 text-sm"
                  >
                    Add
                  </button>
                </div>
              </div>
            </div>

            <div v-if="form.items.length" class="overflow-x-auto">
              <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead
                  class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                  <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3 text-right">Price</th>
                    <th class="px-4 py-3 text-right">Qty</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(item, index) in form.items"
                    :key="index"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                  >
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                      {{ item.name }} ({{ item.code }})
                    </td>
                    <td class="px-4 py-3 text-right">
                      <input
                        v-model.number="item.price"
                        type="number"
                        step="0.01"
                        min="0"
                        class="w-24 form-input text-right text-sm py-1"
                      />
                    </td>
                    <td class="px-4 py-3 text-right">
                      <input
                        v-model.number="item.quantity"
                        type="number"
                        min="1"
                        class="w-20 form-input text-right text-sm py-1"
                      />
                    </td>
                    <td class="px-4 py-3 text-right">
                      {{ (item.price * item.quantity).toFixed(2) }}
                    </td>
                    <td class="px-4 py-3 text-right">
                      <button
                        type="button"
                        @click="removeItem(index)"
                        class="text-red-500 hover:text-red-700"
                      >
                        <i class="fi fi-rr-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700 font-medium">
                  <tr>
                    <td colspan="3" class="px-4 py-3 text-right">Subtotal</td>
                    <td class="px-4 py-3 text-right">{{ subtotal.toFixed(2) }}</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="px-4 py-3 text-right">Grand Total</td>
                    <td
                      class="px-4 py-3 text-right text-lg font-bold text-purple-600 dark:text-purple-400"
                    >
                      {{ grandTotal.toFixed(2) }}
                    </td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="form-label">Payment Status</label>
                <select v-model="form.payment_status" class="form-select">
                  <option value="unpaid">Unpaid</option>
                  <option value="paid">Paid</option>
                  <option value="partial">Partial Payment</option>
                </select>
              </div>

              <div>
                <label class="form-label">Date</label>
                <input type="date" v-model="form.date" class="form-input" />
              </div>

              <div class="md:col-span-2">
                <label class="form-label">Notes</label>
                <textarea
                  v-model="form.note"
                  class="form-textarea"
                  rows="3"
                  placeholder="Additional information..."
                ></textarea>
              </div>
            </div>

            <div class="flex justify-end pt-4">
              <button
                type="submit"
                class="btn-primary"
                :disabled="form.processing || form.items.length === 0"
                :class="{
                  'opacity-50 cursor-not-allowed':
                    form.processing || form.items.length === 0,
                }"
              >
                <span v-if="form.processing">
                  <i class="fi fi-rr-spinner animate-spin mr-2"></i>
                  Processing...
                </span>
                <span v-else>
                  <i class="fi fi-rr-save mr-2"></i>
                  Save Stock Entry
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<style scoped>
/* Your existing styles remain here */
.form-label {
  @apply block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300;
}

.form-input {
  @apply w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition duration-200;
}

.form-select {
  @apply w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 appearance-none pr-8 transition duration-200;
}

.form-textarea {
  @apply w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition duration-200;
}

.btn-primary {
  @apply px-5 py-2.5 text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm text-center inline-flex items-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800 transition duration-200;
}

.btn-secondary {
  @apply px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm text-center inline-flex items-center dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:hover:border-gray-700 dark:focus:ring-gray-700 transition duration-200;
}
</style>
