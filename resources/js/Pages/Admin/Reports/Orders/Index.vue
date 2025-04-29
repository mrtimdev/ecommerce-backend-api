<template>
  <DefaultLayout>
    <Head :title="$t('orders_report')" />
    <div class="container">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <Bc :crumbs="breadcrumbs" />
      </div>

      <div class="content-body p-5">
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
  </DefaultLayout>
</template>

<script setup>
import { Head, router, useForm } from "@inertiajs/vue3";
import ActionButtons from "@/Components/Others/ActionButtons.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { onMounted, reactive, h, createApp, ref, getCurrentInstance } from "vue";
import useHelper from "@/composables/useHelper";
const { statusFormat, formatMoney, formatDate } = useHelper();
import { i18n } from "@/i18n";
import { useI18n } from "vue-i18n";
import { events } from "@/events";
import axios from "axios";

const { proxy } = getCurrentInstance();

const { t } = useI18n();
const tableData = ref(false);
const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("reports"), url: null },
  { label: t("orders"), url: null },
  { label: t("list"), url: null, is_active: true },
]);

const form = useForm({
  id: null,
  status: "pending",
});

onMounted(() => {
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
      url: route("orders.list"),
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
