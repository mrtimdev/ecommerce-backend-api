<template>
  <DefaultLayout>
    <Head :title="$t('cars.featured')" />
    <div class="container">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <Bc :crumbs="breadcrumbs" />
        <div class="flex items-center gap-2 justify-center">
          <button
            v-if="featured_count < 20"
            @click.prevent="openModalForm"
            class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
          >
            <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
            {{ $t("new") }}
          </button>
        </div>
      </div>

      <div class="content-body p-5">
        <div
          class="flex items-center p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
          role="alert"
        >
          <svg
            class="flex-shrink-0 inline w-4 h-4 me-3"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path
              d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"
            />
          </svg>
          <span class="sr-only">Info</span>
          <div>
            <span class="font-medium">Note!</span> Cars Featured can create only 20 cars
            limited.
          </div>
        </div>
        <div class="relative">
          <table id="cars" class="table w-full text-sm text-left rtl:text-right">
            <thead class="text-center bg-white dark:bg-boxdark">
              <tr>
                <th class="w-[3%]">
                  {{ $t("#") }}
                </th>
                <th class="w-[5%]">{{ $t("image") }}</th>
                <th class="w-[5%]">{{ $t("code") }}</th>
                <th class="w-[25%]">{{ $t("name") }}</th>
                <th class="w-[5%]">{{ $t("listing_date") }}</th>
                <th class="w-[5%]">{{ $t("total_price") }}</th>
                <th class="w-[5%]">{{ $t("year") }}</th>
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
    <AddFeatured :show-modal="false" :cars="cars" />
  </DefaultLayout>
</template>

<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import ChangeFeaturedImage from "./ChangeFeaturedImage.vue";
import ChangeGalleryImages from "./ChangeGalleryImages.vue";
import ModalView from "./ModalView.vue";
import AddFeatured from "./AddFeatured.vue";
import ActionButtons from "@/Components/Others/ActionButtons.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { onMounted, reactive, h, createApp, ref, getCurrentInstance } from "vue";
import { useCar } from "@/stores/car";
import { i18n } from "@/i18n";
import { useI18n } from "vue-i18n";
import { nextTick } from "vue";
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
  id: [],
});

const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("cars"), url: route("cars.index") },
  { label: t("featured"), url: null, is_active: true },
]);

defineProps({
  cars: Array,
  featured_count: Number,
});
const openModalForm = () => {
  events.emit("modal:carFeatured:open", {
    modal_title: "featured.add",
    event_type: "add",
  });
};

onMounted(() => {
  tableData.value = $("#cars").DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[4, "asc"]],
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
      url: route("cars.featured.list"),
      type: "GET",
    },
    columns: [
      {
        data: "id",
        orderable: false,
        searchable: false,
        className: "checkbox-col",
        render: function (data, type, row, meta) {
          return row.DT_RowIndex;
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
                                openConfirmPopup(row);
                              },
                            },
                            [h("i", { class: "fa fa-trash mr-2" }), t("remove")]
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
const openConfirmPopup = async (row) => {
  const result = await proxy.$swal.fire({
    title: "Are you sure?",
    html: `remove <span class="text-rose-600">[${t(row.code)}] - ${
      row.name
    }</span> from featured list!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: t("yes"),
    cancelButtonText: t("no"),
  });

  if (result.isConfirmed) {
    form.id = row.id;
    form.post(route("cars.featured.remove"), {
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
          title: `<span class="text-rose-600">[${t(row.code)}] - ${
            row.name
          }</span> was removed from featured list!`,
        });
        form.reset();
        tableData.value.ajax.reload();
      },
      onError: () => {},
    });
  } else {
    console.log("Item not deleted.");
  }
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
      tableData.value.ajax.reload();
      router.get(route("cars.featured"));
    },
    onError: () => {},
  });
});
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
</style>
