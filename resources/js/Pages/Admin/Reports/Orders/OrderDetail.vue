<template>
  <OrderIndex>
    <template #detail>
      <Head :title="$t('view_order') + ' #' + order.data.order_no" />
      <Teleport to="body">
        <modal
          size="md"
          :show="show_modal"
          :show-footer="false"
          :show-confirm-button="true"
          button-confirm-label="save"
          @close="closeModal"
        >
          <template #title>
            <span class="!text-purple-600">#{{ order.data.order_no }}</span>
          </template>
          <template #body>
            <div class="p-6 max-w-2xl mx-auto">
              <!-- Order Info Table -->
              <div class="border-b pb-4 mb-6">
                <h3 class="text-lg font-medium text-gray-600 dark:text-gray-200 mb-4">
                  {{ $t("order_information") }}
                </h3>
                <table class="table w-full text-sm text-left rtl:text-right">
                  <tbody
                    class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark"
                  >
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("date") }}:</td>
                      <td class="py-2 px-4">
                        {{ formatDate(order.data.created_at, true) }}
                      </td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("order_no") }}:</td>
                      <td class="py-2 px-4 !text-purple-600">
                        #{{ order.data.order_no }}
                      </td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("full_name") }}:</td>
                      <td class="py-2 px-4">{{ order.data.full_name }}</td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("email") }}:</td>
                      <td class="py-2 px-4">{{ order.data.email }}</td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("phone") }}:</td>
                      <td class="py-2 px-4">{{ order.data.phone }}</td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("telegram_or_phone") }}:</td>
                      <td class="py-2 px-4">{{ order.data.telegram_or_phone }}</td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("price") }}:</td>
                      <td class="py-2 px-4">{{ formatMoney(order.data.price) }}</td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("location") }}:</td>
                      <td class="py-2 px-4 !text-purple-600">
                        {{ order.data.location || "" }}
                      </td>
                    </tr>
                    <tr>
                      <td class="py-2 px-4 font-sm">{{ $t("detail") }}:</td>
                      <td class="py-2 px-4">{{ order.data.detail }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Order Details Table -->
              <div>
                <h3 class="text-lg font-medium text-gray-600 dark:text-gray-200 mb-4">
                  {{ $t("order_items") }}
                </h3>
                <table class="table w-full text-sm text-left rtl:text-right">
                  <thead class="text-center bg-white dark:bg-boxdark">
                    <tr>
                      <th class="py-2 px-4 text-left">{{ $t("item_code") }}</th>
                      <th class="py-2 px-4">{{ $t("local_link") }}</th>
                      <th class="py-2 px-4">{{ $t("sourced_link") }}</th>
                    </tr>
                  </thead>
                  <tbody
                    class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark"
                  >
                    <tr class="border-b border-gray-300">
                      <td class="py-2 px-4">{{ order.data.item_code }}</td>
                      <td class="py-2 px-4 text-center">
                        <a
                          :href="order.data.link"
                          class="hover:underline text-blue-700"
                          target="_blank"
                        >
                          <i class="fi fi-rr-link text-[12px]"></i> {{ $t("open") }}
                        </a>
                      </td>
                      <td class="py-2 px-4 text-center">
                        <a
                          :href="order.data.link_korea"
                          class="hover:underline text-blue-700"
                          target="_blank"
                          v-if="order.data.link_korea"
                        >
                          <i class="fi fi-rr-link text-[12px]"></i> {{ $t("open") }}
                        </a>
                        <span v-else> No Link </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </template>
        </modal>
      </Teleport>
    </template>
  </OrderIndex>
</template>

<script setup>
import { Head, router } from "@inertiajs/vue3";
import OrderIndex from "./Index.vue";
import useHelper from "@/composables/useHelper";
const { formatDate, formatMoney } = useHelper();
const props = defineProps({
  show_modal: {
    type: Boolean,
    default: false,
  },
  order: {
    type: Object,
    require: true,
  },
});

const closeModal = () => {
  router.get(route("orders.index"));
};
</script>
