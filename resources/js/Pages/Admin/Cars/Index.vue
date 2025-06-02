<template>
  <DefaultLayout>
    <Head :title="$t('cars.listing')" />
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
            :href="route('cars.create')"
            class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
          >
            <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
            {{ $t("add") }}
          </Link>
        </div>
      </div>

      <div class="content-body p-5 overflow-x-scroll">
        <div class="relative">
          <table id="cars" class="table w-full text-sm text-left rtl:text-right">
            <thead class="text-center bg-white dark:bg-boxdark">
              <tr>
                <th class="w-[3%]">
                  <input
                    type="checkbox"
                    :checked="mainStore.checkAll"
                    @change="mainStore.onCheckAll"
                    class="check-all-row w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-boxdark-1 dark:border-gray-600"
                  />
                </th>
                <th class="w-[5%]">{{ $t("image") }}</th>
                <th class="w-[5%]">{{ $t("code") }}</th>
                <th class="w-[25%]">{{ $t("name") }}</th>
                <th class="w-[25%]">{{ $t("sourced_link") }}</th>
                <th class="w-[5%]">{{ $t("listing_date") }}</th>
                <th class="w-[5%]">{{ $t("total_price") }}</th>
                <th class="w-[5%]">{{ $t("year") }}</th>
                <th class="w-[5%]">{{ $t("mileage") }}</th>
                <th class="w-[5%]">{{ $t("condition") }}</th>
                <th class="w-[5%]">{{ $t("brand") }}</th>
                <th class="w-[5%]">{{ $t("model") }}</th>
                <th class="w-[5%]">{{ $t("fuel_type") }}</th>
                <th class="w-[5%]">{{ $t("plate_number") }}</th>
                <th class="w-[10%]">{{ $t("is_featured") }}</th>
                <th class="w-[10%]">{{ $t("is_active") }}</th>
                <th class="w-[10%]">{{ $t("status") }}</th>
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
    <ModalView :show-modal="false" />
    <ChangeGalleryImages :show-modal="false" />
    <ChangeFeaturedImage :show-modal="false" />
    <ChangeSourcedLink :show-modal="false" />
  </DefaultLayout>
</template>

<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import ChangeFeaturedImage from "./ChangeFeaturedImage.vue";
import ChangeSourcedLink from "./ChangeSourcedLink.vue";
import ChangeGalleryImages from "./ChangeGalleryImages.vue";
import ModalView from "./ModalView.vue";
import CheckBox from "@/Components/Others/CheckBox.vue";
import ActionButtons from "@/Components/Others/ActionButtons.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import {
  onMounted,
  onBeforeUnmount,
  reactive,
  h,
  createApp,
  ref,
  getCurrentInstance,
} from "vue";
import { useCar } from "@/stores/car";
import { i18n } from "@/i18n";
import { useEventBus } from "@vueuse/core";
import { useI18n } from "vue-i18n";
import { nextTick, watch } from "vue";
import useHelper from "@/composables/useHelper";
const { statusFormat, imageFormat, formatMoney, formatDate, formatNumber } = useHelper();
const { proxy } = getCurrentInstance();
import { events } from "@/events";

import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();
import { useHelpers } from "@/helpers/useHelpers";
const { isRole, isPermission } = useHelpers();

const { t } = useI18n();
const tableData = ref(false);
const carStore = useCar();
const showModalView = ref(false);
const showGalleryModal = ref(false);
const form = useForm({
  ids: [],
});

const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("cars"), url: null },
  { label: t("cars.listing"), url: null, is_active: true },
]);

onMounted(() => {
  tableData.value = $("#cars").DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [
      [4, "desc"],
      // [4, "asc"],
    ],
    aLengthMenu: [
      [5, 10, 25, 50, -1],
      [5, 10, 25, 50, 100, "All"],
    ],
    stateSave: true,
    stateSaveCallback: function (settings, data) {
      localStorage.setItem("DataTables_" + settings.sInstance, JSON.stringify(data));
    },
    stateLoadCallback: function (settings) {
      return JSON.parse(localStorage.getItem("DataTables_" + settings.sInstance));
    },
    ajax: {
      url: route("cars.list"),
      type: "GET",
    },
    columns: [
      {
        data: "id",
        orderable: false,
        searchable: false,
        className: "checkbox-col",
        render: function (data, type, row, meta) {
          const checkBoxHtml = `<div id="checkbox-${row.id}"></div>`;
          nextTick(() => {
            const container = document.getElementById(`checkbox-${row.id}`);
            if (container.__vueApp__) {
              container.__vueApp__.unmount();
            }
            const checkBoxApp = createApp({
              render() {
                return h(CheckBox, {
                  class: "check-row",
                  value: parseInt(row.id),
                  checked: false,
                  "onUpdate:checked": (checked) =>
                    mainStore.onCheckRow(checked, parseInt(row.id)),
                });
              },
            });

            container.__vueApp__ = checkBoxApp;
            checkBoxApp.use(i18n).mount(container);
          });
          return checkBoxHtml;
        },
      },
      {
        data: "featured_image_full_path",
        orderable: false,
        searchable: false,
        className: "action-col text-center",
        render: function (data, type, row, meta) {
          return imageFormat(data);
        },
      },
      { data: "code", name: "code" },
      { data: "name", name: "name" },
      { data: "sourced_link", name: "sourced_link" },
      {
        data: "listing_date",
        name: "listing_date",
        render: (data, type, row, meta) => {
          return formatDate(data);
        },
      },

      {
        data: "total_price",
        name: "total_price",
        render: (data, type, row, meta) => {
          return formatMoney(data);
        },
      },
      { data: "year", name: "year", className: "text-center" },
      {
        data: "mileage",
        name: "mileage",
        className: "text-right",
        render: (data) => formatNumber(data) + " Km",
      },
      { data: "condition_name", name: "condition.name" },
      { data: "brand_name", name: "brand.name" },
      { data: "model_name", name: "model.name" },
      { data: "fuel_type_name", name: "fuelType.name" },
      { data: "plate_number", name: "plate_number" },
      {
        data: "is_featured",
        name: "is_featured",
        className: "text-center",
        render: function (data, type, row, meta) {
          return statusFormat(data ? "yes" : "no");
        },
      },
      {
        data: "is_active",
        name: "is_active",
        className: "text-center",
        render: function (data, type, row, meta) {
          return statusFormat(data ? "active" : "inactive");
        },
      },
      {
        data: "status",
        name: "status",
        className: "text-center",
        render: function (data, type, row, meta) {
          return statusFormat(data);
        },
      },
      {
        data: "action",
        orderable: false,
        searchable: false,
        className: "action-col",
        render: function (data, type, row, meta) {
          const actionsHtml = `<div id="actions-${row.id}"></div>`;
          nextTick(() => {
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
                            events.emit("modal:modalview:open", {
                              modal_title: "car.details",
                              event_type: "view",
                              item: row,
                            });
                          },
                        },
                        [h("i", { class: "fa fa-eye mr-2" }), t("view")]
                      ),
                      h(
                        "div",
                        {
                          class:
                            "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                          onClick: (e) => {
                            e.preventDefault();
                            events.emit("modal:sourcedLink:open", {
                              event_type: "change_sourced_link",
                              item: row,
                            });
                          },
                        },
                        [h("i", { class: "fa fa-link mr-2" }), t("sourced_link")]
                      ),
                      h(
                        "div",
                        {
                          class:
                            "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                          onClick: (e) => {
                            e.preventDefault();
                            events.emit("modal:featuredimage:open", {
                              modal_title: "featured_image",
                              event_type: "change_featured_image",
                              item: row,
                            });
                          },
                        },
                        [h("i", { class: "fa fa-image mr-2" }), t("featured_image")]
                      ),
                      h(
                        "div",
                        {
                          class:
                            "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                          onClick: (e) => {
                            e.preventDefault();
                            events.emit("modal:changgallery:open", {
                              modal_title: "gallery_images",
                              event_type: "change_gallery_images",
                              item: row,
                            });
                          },
                        },
                        [h("i", { class: "fa fa-image mr-2" }), t("change_gallery")]
                      ),
                      (isRole("owner") && isRole("admin")) ||
                        (isPermission(["car-edit"]) &&
                          h(
                            "div",
                            {
                              class:
                                "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                              onClick: (e) => {
                                router.get(route("cars.edit", row.id));
                              },
                            },
                            [h("i", { class: "fa fa-pencil mr-2" }), t("edit")]
                          )),
                      (isRole("owner") && isRole("admin")) ||
                        (isPermission(["car-delete"]) &&
                          h(
                            "div",
                            {
                              class:
                                "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                              onClick: (e) => {
                                e.preventDefault();
                                events.emit("confirm:open", [row.id]);
                              },
                            },
                            [h("i", { class: "fa fa-trash mr-2" }), t("delete")]
                          )),
                    ],
                  }
                );
              },
            });
            container.__vueApp__ = actionsApp;
            actionsApp.use(i18n).mount(container);
          });
          return actionsHtml;
        },
      },
    ],
    rowCallback: function (row, data, index) {
      const _row = $(row);
      $(_row).find("td:not(:first-child):not(:last-child)").addClass("cursor-pointer");
      $(_row)
        .find("td:not(:first-child):not(:last-child)")
        .on("click", function (e) {
          e.preventDefault();
          events.emit("modal:modalview:open", {
            modal_title: "car.details",
            event_type: "view",
            item: data,
          });
        });
    },
    language: {
      // paginate: {
      //   first: "First",
      //   last: "Last",
      //   next: "Next",
      //   previous: "Previous"
      // },
      // search: "Filter records:",
      // // lengthMenu: "Display _MENU_ records per page",
      // info: "Sowing _START_ to _END_ of _TOTAL_ entries",
      // infoEmpthy: "No entries available",
      // infoFiltered: "(filtered from _MAX_ total records)",
      // zeroRecords: "No matching records found",
      // loadingRecords: "Loading...",
      // emptyTable: "No data available in table"
    },
  });
});

events.on("modal:success", () => {
  tableData.value.ajax.reload();
});

const btnDeleteSelected = () => {
  if (!isRole("owner") && !isRole("admin") && !isPermission(["car-delete"])) {
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
      icon: "warning",
      html: `<b>Access Denied:</b> You do not have the required permissions to access this feature.`,
    });
    return;
  }
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : [];
  events.emit("confirm:open", items);
};

events.on("confirm:confirmed", (data) => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : data;
  form.ids = items;
  form.post(route("cars.destroy.selected"), {
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
        title: `${t("item.count", items.length)} ${t("successfully_deleted")}`,
      });
      form.reset();
      events.emit("confirm:cancel");
      events.emit("confirm:success");
    },
    onError: () => {},
  });
});
events.on("confirm:success", () => {
  tableData.value.ajax.reload();
  mainStore.clearSelectedRows();
});

onBeforeUnmount(() => mainStore.clearSelectedRows());
</script>

<style lang="scss">
.table tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.05);
}

.dark {
  .table tr th,
  .table tr td {
    border-color: rgba(209, 199, 199, 0.05) !important;
    border: 1px solid;
  }
}
.table {
  tr {
    td,
    th {
      border-color: #dedada !important;
      border: 1px solid;
    }
  }
}
.checkbox-col,
.action-col {
  text-align: center !important;
}

.dt-length {
  > select {
    width: 50px !important;
  }
}
.table {
  .text-center {
    text-align: center !important;
  }
}

/*#cars.table {
  tbody,
  thead {
    tr {
      td,
      th {
        &:nth-child(2),
        &:nth-child(3) {
          position: sticky;
          @media (prefers-color-scheme: dark) {
            background-color: #1e293b;
          }
          @media (prefers-color-scheme: light) {
            background-color: unset;
          }
        }
        &:nth-child(2) {
          left: -20px;
          border-right: 1px solid rgb(89, 91, 94) !important;
        }
        &:nth-child(3) {
          left: 40px;
        }
        &:nth-last-child(1) {
          position: sticky;
        }
        &:nth-last-child(1) {
          right: -20px;
        }
      }
    }
  }
}*/
</style>
