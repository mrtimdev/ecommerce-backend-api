<template>
  <LayoutTab>
    <Head :title="$t('orders_report')" />
    <div class="container">
      <div
        class="content-header rounded-tl-md rounded-tr-md py-4 bg-white dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <div
          v-if="route().current('orders.index') || route().current('orders.user.index')"
          class="flex justify-between items-center w-full pb-4 border-b dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-white"
        >
          <div class="w-[70%] flex justify-items-start gap-5">
            <span>{{ $t("orders.list") }}</span>
            <span v-if="user" class="mt-[3px]"
              ><i class="fi fi-ss-arrow-circle-right"></i
            ></span>
            <span class="text-purple-600 font-bold" v-if="user">
              {{ user.first_name }} {{ user.last_name }}</span
            >
          </div>

          <div class="w-[30%] flex justify-center items-center gap-1">
            <label
              id="user"
              class="block w-[50%] text-sm font-medium text-gray-900 dark:text-white"
              >{{ $t("select_user") }}
            </label>
            <MultiSelect
              id="user"
              v-model="user_form.user"
              :options="users"
              :multiple="false"
              track-by="id"
              @select="handleUserChange"
              @remove="handleUserRemove"
              :custom-label="(option) => `${option.name}`"
            >
            </MultiSelect>
          </div>
        </div>
      </div>

      <div class="content-body">
        <div class="relative">
          <table id="orders" class="table w-full text-sm text-left rtl:text-right">
            <thead class="text-center bg-white dark:bg-boxdark">
              <tr>
                <th class="w-[10%]">{{ $t("date") }}</th>
                <th class="w-[10%]">{{ $t("order_no") }}</th>
                <th class="w-[10%]">{{ $t("item_code") }}</th>
                <th class="w-[10%]">{{ $t("price") }}</th>
                <th class="w-[20%]">{{ $t("customer_name") }}</th>
                <th class="w-[20%]">{{ $t("email") }}</th>
                <th class="w-[20%]">{{ $t("phone") }}</th>
                <th class="w-[5%]">{{ $t("status") }}</th>
                <th class="w-[10%]">{{ $t("actions") }}</th>
              </tr>
            </thead>
            <tbody
              class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark"
            ></tbody>
          </table>
        </div>
      </div>
    </div>
    <slot name="detail"></slot>
  </LayoutTab>
</template>

<script setup>
import LayoutTab from "../Layout/Index.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import ActionButtons from "@/Components/Others/ActionButtons.vue";
import { onMounted, nextTick, h, createApp, ref, getCurrentInstance } from "vue";
import useHelper from "@/composables/useHelper";
const { statusFormat, formatMoney, formatDate } = useHelper();
import { i18n } from "@/i18n";
import { useI18n } from "vue-i18n";
import { events } from "@/events";
import axios from "axios";

const { proxy } = getCurrentInstance();
import { useMainStore } from "@/stores/main";
const mainStore = useMainStore();

const { t } = useI18n();
const tableData = ref(false);

const form = useForm({
  id: null,
  status: "pending",
});

const user_form = useForm({
  user: null,
});

const props = defineProps({
  user: {
    type: [Object, null],
    default: null,
  },
  users: {
    type: Array,
    required: true,
  },
});

const handleUserChange = (user) => {
  router.get(route("orders.user.index", user.id));
};
const handleUserRemove = (user) => {
  router.get(route("orders.index"));
};

onMounted(() => {
  nextTick(() => {
    Array.from(document.querySelectorAll(".multiselect__tags")).forEach((element) => {
      element.classList.add(...mainStore.inputClasses);
    });
    Array.from(document.querySelectorAll(".multiselect__input")).forEach((element) => {
      element.classList.add(...mainStore.inputClasses);
    });
    if (props.user) {
      user_form.user = props.user ? props.user : null;
    }
  });
  tableData.value = $("#orders").DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[1, "desc"]],
    aLengthMenu: [
      [5, 10, 25, 50, -1],
      [5, 10, 25, 50, 100, "All"],
    ],
    ajax: {
      url: props.user?.id
        ? route("orders.user.list", props.user.id)
        : route("orders.list"),
      type: "GET",
    },
    stateSave: true,
    stateSaveCallback: function (settings, data) {
      localStorage.setItem("DataTables_" + settings.sInstance, JSON.stringify(data));
    },
    stateLoadCallback: function (settings) {
      return JSON.parse(localStorage.getItem("DataTables_" + settings.sInstance));
    },
    columns: [
      {
        data: "created_at",
        name: "created_at",
        className: "!text-center",
        render: (data) => formatDate(data),
      },
      { data: "order_no", className: "!text-center", name: "order_no" },
      { data: "item_code", className: "!text-center", name: "item_code" },
      { data: "price", name: "price", render: (data) => formatMoney(data) },
      { data: "user.name", name: "user.name" },
      { data: "user.email", name: "user.email" },
      { data: "user.phone", name: "user.phone", className: "!text-center" },
      { data: "status", name: "status", render: (data) => statusFormat(data) },
      {
        data: "action",
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
          const actionsHtml = `<div id="actions-${row.id}"></div>`;
          setTimeout(() => {
            const container = document.getElementById(`actions-${row.id}`);
            if (container.__vueApp__) {
              container.__vueApp__.unmount();
            }
            const actionsApp = createApp({
              render() {
                return h(
                  ActionButtons,
                  {},
                  {
                    default: () => [
                      h(
                        "div",
                        {
                          class:
                            "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                          onClick: (e) => {
                            e.preventDefault();
                            router.get(route("orders.detail", row.id));
                          },
                        },
                        [h("i", { class: "fi fi-ts-order-history mr-2" }), t("view")]
                      ),
                      row.status === "pending" &&
                        h(
                          "div",
                          {
                            class:
                              "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                            onClick: (e) => {
                              e.preventDefault();
                              openConfirmPopup(row, "approved");
                            },
                          },
                          [
                            h("i", { class: "fi fi-rr-hexagon-check mt-[5px] mr-2" }),
                            t("approve"),
                          ]
                        ),
                      row.status === "approved" &&
                        h(
                          "div",
                          {
                            class:
                              "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                            onClick: (e) => {
                              e.preventDefault();
                              openConfirmPopup(row, "unapproved");
                            },
                          },
                          [
                            h("i", { class: "fi fi-ts-octagon-xmark mt-[5px] mr-2" }),
                            t("unapproved"),
                          ]
                        ),
                      row.status.includes("pending", "approved") &&
                        h(
                          "div",
                          {
                            class:
                              "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                            onClick: (e) => {
                              e.preventDefault();
                              openConfirmPopup(row, "rejected");
                            },
                          },
                          [
                            h("i", { class: "fi fi-ts-octagon-xmark mt-[5px] mr-2" }),
                            t("reject"),
                          ]
                        ),
                      row.status === "rejected" &&
                        h(
                          "div",
                          {
                            class:
                              "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                            onClick: (e) => {
                              e.preventDefault();
                              openConfirmPopup(row, "unrejected");
                            },
                          },
                          [
                            h("i", { class: "fi fi-ts-octagon-xmark mt-[5px] mr-2" }),
                            t("unrejected"),
                          ]
                        ),
                      row.status.includes("pending") &&
                        h(
                          "div",
                          {
                            class:
                              "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                            onClick: (e) => {
                              e.preventDefault();
                              openConfirmPopup(row, "delete");
                            },
                          },
                          [
                            h("i", { class: "fi fi-tr-recycle-bin mt-[5px] mr-2" }),
                            t("delete"),
                          ]
                        ),
                    ],
                  }
                );
              },
            });
            container.__vueApp__ = actionsApp;
            actionsApp.use(i18n).mount(container);
          }, 0);
          return actionsHtml;
        },
      },
    ],
  });
});

const openConfirmPopup = async (row, status) => {
  const result = await proxy.$swal.fire({
    title: "Are you sure?",
    html: `${t(status)} This order! <b>${row.order_no}</b>`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: t("yes"),
    cancelButtonText: t("no"),
  });

  if (result.isConfirmed) {
    form.status = status;
    form.post(route("orders.update-status", row.id), {
      preserveScroll: true,
      onSuccess: () => {
        const Toast = proxy.$swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.onmouseenter = proxy.$swal.stopTimer;
            toast.onmouseleave = proxy.$swal.resumeTimer;
          },
        });
        Toast.fire({
          icon: "success",
          title: `${row.order_no} was ${status} in successfully`,
        });
        form.reset();
        tableData.value.ajax.reload();
      },
      onError: () => {},
    });
    // axios.post(route('ordes.update-status', {order: row.id})).then((res) => {

    // })
  } else {
    // Action canceled
    console.log("Item not deleted.");
  }
};
</script>
