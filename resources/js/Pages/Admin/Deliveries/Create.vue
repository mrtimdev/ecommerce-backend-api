<template>
  <DefaultLayout>
    <Head :title="$t('receives.add')" />
    <div class="">
      <!-- Header with gradient background -->
      <div
        class="content-header p-5 rounded-tl-lg rounded-tr-lg py-4 bg-gradient-to-r from-blue-500 to-blue-600 dark:from-gray-800 dark:to-gray-900 flex items-center justify-between shadow-md"
      >
        <div
          class="flex items-center gap-2 text-base font-semibold text-white"
        >
          <i class="fi fi-rr-box-open w-5 h-5"></i>
          {{ $t("receives.add") }}
        </div>
        <div class="text-xs text-blue-100 dark:text-gray-300">
          {{ $t('create_new_receive') }}
        </div>
      </div>

      <!-- Form with card styling -->
      <div class="content-body p-5">
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div class="grid grid-cols-12 gap-6">
              
              <div class="col-span-12 md:col-span-6">
                <label
                  for="reference_no"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  {{ $t("reference_no") }}
                  <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    type="text"
                    v-model="form.reference_no"
                    ref="reference_no"
                    placeholder="Enter reference number"
                    class="form-input w-full dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all"
                    required
                  />
                </div>
              </div>

              <!-- Date -->
              <div class="col-span-12 md:col-span-6">
                <label
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  {{ $t("date") }}
                  <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    type="date"
                    v-model="form.date"
                    class="form-input w-full dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all"
                    required
                  />
                </div>
              </div>

              <!-- Client Select -->
              <div class="col-span-12 md:col-span-6">
                <label
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  {{ $t("client") }}
                  <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <!-- <select
                    v-model="form.client_id"
                    @change="loadClientPackages"
                    class="form-select w-full dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all"
                    required
                  >
                    <option value="" disabled selected>{{ $t("select_client") }}</option>
                    <option v-for="client in clients" :key="client.id" :value="client.id">
                      {{ client.name }}
                    </option>
                  </select> -->


                  <div class="relative">
                    <Select fluid v-model="selectedClient" showClear :options="clients" filter optionLabel="name" placeholder="Select a Client" class="w-full md:w-56">
                        <template #value="slotProps">
                            <div v-if="slotProps.value" class="flex items-center">
                                <img :alt="slotProps.value.name" :src="slotProps.value.avatar_full_path" :class="`mr-2`" style="width: 18px" />
                                <div>{{ slotProps.value.name }}</div>
                            </div>
                            <span v-else>
                                {{ slotProps.placeholder }}
                            </span>
                        </template>
                        <template #option="slotProps">
                            <div class="flex items-center">
                                <img :alt="slotProps.option.name" :src="slotProps.option.avatar_full_path"  :class="`mr-2`" style="width: 18px" />
                                <div>{{ slotProps.option.name }}</div>
                            </div>
                        </template>
                    </Select>
                </div>
                </div>
              </div>

              

              

             <!-- package Select -->
              <div class="col-span-12 md:col-span-6">
                <label
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  {{ $t("seelct_package") }}
                  <span class="text-red-500">*</span>
                </label>
                  <AutoComplete
                      v-model="selectedPackage"
                      optionLabel="name"
                      fluid
                      :suggestions="filteredPackages"
                      @complete="searchPackages"
                      :disabled="!form.client_id"
                      inputId="inputPackage"
                      :virtualScrollerOptions="{ itemSize: 38 }" dropdown 
                      inputClass="w-full dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all"
                  />
                  

              </div>

              

              <!-- Package Items -->
              <div class="col-span-12">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="font-semibold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fi fi-rr-list-check"></i>
                    {{ $t("items") }}
                  </h3>
                  <span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full">
                    {{ filteredItems.length }} {{ $t('items') }}
                  </span>
                </div>

                <div
                  v-if="packageItems.length"
                  class="border dark:border-gray-700 rounded-lg overflow-hidden"
                >
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                      <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ $t('product') }}
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ $t('status') }}
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ $t('balance') }}
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ $t('quantity_to_receive') }}
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ $t('actions') }}
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(item, index) in packageItems" :key="item.id">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                              <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <i class="fi fi-rr-box-open text-blue-600 dark:text-blue-300"></i>
                              </div>
                              <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                  {{ item.product.name }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                  {{ item.quantity }} {{ item.unit.name }}
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="statusBadgeClass(item.status)">
                                {{ item.status }}
                        </span>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ item.balance_quantity }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative">
                              <input
                                type="number"
                                min="0"
                                :max="item.balance_quantity"
                                step="0.01"
                                v-model.number="form.items[index].quantity"
                                @input="validateQuantity(index)"
                                class="form-input w-32 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all"
                                :class="{
                                  'border-red-300': quantityErrors[index],
                                  'border-gray-300': !quantityErrors[index]
                                }"
                                placeholder="0"
                              />
                              <input
                                type="hidden"
                                v-model="form.items[index].package_item_id"
                              />
                              <div v-if="quantityErrors[index]" class="absolute -bottom-5 left-0 text-xs text-red-600 dark:text-red-400">
                                {{ quantityErrors[index] }}
                              </div>
                            </div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button
                              type="button"
                              @click="removeItem(index)"
                              class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors"
                              :title="$t('remove_item')"
                            >
                              <i class="fi fi-rr-trash"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div v-else class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
                  <i class="fi fi-rr-box-open text-4xl text-gray-400 mb-3"></i>
                  <p class="text-gray-500 dark:text-gray-400">
                    {{ $t("no_items_available") }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Note -->
              <div class="col-span-12">
                <label
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                  {{ $t("note") }}
                </label>
                <div class="relative">
                  <textarea
                    v-model="form.note"
                    rows="3"
                    class="form-textarea w-full dark:bg-gray-700 dark:text-white dark:border-gray-600 px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 transition-all"
                    :placeholder="$t('add_notes_here')"
                  ></textarea>
                  <div class="absolute top-3 right-3 flex items-start pointer-events-none">
                    <i class="fi fi-rr-comment-alt text-gray-400 mt-1"></i>
                  </div>
                </div>
              </div>

            <!-- Buttons with better styling -->
            <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
              <Link
                :href="route('receives.index')"
                class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:ring-4 focus:ring-red-300 shadow-md transition-all"
              >
                <i class="fi fi-rr-arrow-left mr-2"></i>
                {{ $t("cancel") }}
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:ring-4 focus:ring-emerald-300 shadow-md transition-all disabled:opacity-75 disabled:cursor-not-allowed"
              >
                <i class="fi fi-rr-check mr-2"></i>
                {{ form.processing ? $t('saving')+'...' : $t('save') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { ref, computed, watch } from "vue";
import { useI18n } from "vue-i18n";
import { events } from "@/events";
import axios from 'axios'

import AutoComplete from 'primevue/autocomplete';
import Select from 'primevue/select';


const { t } = useI18n();

const selectedClient = ref(null);
const clientPackages = ref([])
const packageItems = ref([]);
const quantityErrors = ref({});
const removedItems = ref([]);


const selectedPackage = ref(null);
const filteredPackages = ref([]);
const isLoadingPackages = ref(false);

// Computed property to filter out removed items
const filteredItems = computed(() => {
  return packageItems.value.filter((_, index) => !removedItems.value.includes(index));
});

// Props from controller
const props = defineProps({
  clients: Array,
});

const form = useForm({
  reference_no: "",
  client_id: "",
  package_id: "",
  date: new Date().toISOString().slice(0, 10),
  note: "",
  items: [],
});
const removeItem = (index) => {
  const originalIndex = getOriginalIndex(index);
  if (originalIndex === -1) return;

  removedItems.value.push(originalIndex);

  // Show undo notification
  events.emit("toaster", {
    type: "info",
    message: t('item_removed'),
    action: {
      text: t('undo'),
      onClick: () => {
        removedItems.value = removedItems.value.filter(i => i !== originalIndex);
      }
    }
  });
};

// Validate quantity input
const validateQuantity = (index) => {
  const originalIndex = getOriginalIndex(index);
  if (originalIndex === -1) return;

  const item = packageItems.value[originalIndex];
  const inputQuantity = form.items[originalIndex].quantity;

  if (inputQuantity > item.balance_quantity) {
    quantityErrors.value[originalIndex] = t('quantity_exceeds_balance', { balance: item.balance_quantity });
  } else if (inputQuantity < 0) {
    quantityErrors.value[originalIndex] = t('quantity_negative');
  } else {
    quantityErrors.value[originalIndex] = null;
  }
};

// Get original index accounting for removed items
const getOriginalIndex = (filteredIndex) => {
  let count = -1;
  for (let i = 0; i < packageItems.value.length; i++) {
    if (!removedItems.value.includes(i)) {
      count++;
      if (count === filteredIndex) return i;
    }
  }
  return -1;
};
const searchPackages = async (event) => {
  if (!form.client_id) return;

  isLoadingPackages.value = true;
  console.log({event});
  try {
    const response = await axios.get(`/ftd/api/v1/packages/by-client/${form.client_id}`, {
      params: { query: event.query }
    });
    filteredPackages.value = response.data;
  } catch (error) {
    console.error('Error searching packages:', error);
    filteredPackages.value = [];
  } finally {
    isLoadingPackages.value = false;
  }
};

watch([() => selectedPackage.value, () => selectedClient.value], ([selectedPackage_, selectedClient]) => {
  form.client_id = selectedClient ? selectedClient.id : "";
  console.log('Selected package:', selectedPackage_, selectedClient);
})


// watch(selectedPackage, async (newValue) => {
//   if (newValue) {
//     form.package_id = newValue.id;
//     await loadPackageItems();
//   console.log('Selected package:', newValue);
//   } else {
//     form.package_id = "";
//     packageItems.value = [];
//     form.items = [];
//   }
// });

// Load packages when client is selected
const loadClientPackages = async () => {
  selectedPackage.value = null;
  clientPackages.value = [];
  packageItems.value = [];
  form.package_id = "";
  form.items = [];
  if (form.client_id) {
    try {
      const response = await axios.get(route('packages.by-client', {client: form.client_id}))
      clientPackages.value = response.data
    } catch (error) {
      console.error("Error loading packages:", error)
      clientPackages.value = []
    }
  } else {
    clientPackages.value = []
    form.package_id = ""
  }
  resetItems();
}

const loadPackageItems = async () => {
    try {
        const response = await axios.get(route('packages.items.suggession', { package: selectedPackage.value.id }));
        console.log('Package items loaded:', response);
        packageItems.value = response.data.items;
        form.items = packageItems.value.map(item => ({
            package_item_id: item.id,
            quantity: 0,
        }));

        // packageItems.value = response.items.map(item => ({
        //     id: item.id,
        //     product_name: item.product?.name || 'N/A',
        //     quantity: item.quantity,
        //     unit_name: item.unit?.name || 'N/A',
        //     received_quantity: item.received_quantity || 0,
        //     balance_quantity: item.quantity - (item.received_quantity || 0),
        //     status: item.status,
        // }));
        // form.items = packageItems.value.map(item => ({
        //     package_item_id: item.id,
        //     quantity: 0,
        // }));
    } catch (error) {
        console.error("Error loading package items:", error);
        packageItems.value = [];
        form.items = [];

    }
}

const statusBadgeClass = (status) => {
  const base = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full';
  switch(status) {
    case 'pending': return `${base} bg-green-100 text-green-800`;
    case 'partial': return `${base} bg-yellow-100 text-yellow-800`;
    case 'completed': return `${base} bg-blue-100 text-blue-800`;
    default: return `${base} bg-gray-100 text-gray-800`;
  }
};

// Reset removed items and errors
const resetItems = () => {
  removedItems.value = [];
  quantityErrors.value = {};
};

// Prepare the form data for submission
const prepareSubmitData = () => {
  const submitItems = [];

  packageItems.value.forEach((item, index) => {
    if (!removedItems.value.includes(index) && form.items[index].quantity > 0) {
      submitItems.push({
        package_item_id: item.id,
        quantity: form.items[index].quantity
      });
    }
  });

  return {
    client_id: form.client_id,
    package_id: form.package_id,
    date: form.date,
    note: form.note,
    items: submitItems
  };
};

// Handle form submission
const handleSubmit = () => {
  // Validate all quantities before submission
  let hasErrors = false;
  packageItems.value.forEach((_, index) => {
    if (!removedItems.value.includes(index)) {
      validateQuantity(index);
      if (quantityErrors.value[index]) {
        hasErrors = true;
      }
    }
  });

  if (hasErrors) {
    events.emit("toaster", {
      type: "error",
      message: t('please_correct_errors'),
    });
    return;
  }

  const submitData = prepareSubmitData();

  if (submitData.items.length === 0) {
    events.emit("toaster", {
      type: "error",
      message: t('no_items_selected'),
    });
    return;
  }

  form.transform(() => submitData).post(route("receives.store"), {
    preserveScroll: true,
    onSuccess: () => {
      events.emit("toaster", {
        type: "success",
        action: "create",
        message: `${t("receive_items")} ${t("successfully_added")}`,
      });
    },
    onError: (errors) => {
        console.log({errors})
      events.emit("toaster", {
        type: "error",
        message: t('fix_form_errors'),
      });
    }
  });
};
</script>
