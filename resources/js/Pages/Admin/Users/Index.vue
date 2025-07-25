<template>
  <DefaultLayout>
    <Head :title="$t('users_list')" />
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
            :href="route('users.create')"
            class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
          >
            <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
            {{ $t("new") }}
          </Link>
        </div>
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <table id="users" class="table w-full text-sm text-left rtl:text-right">
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
                <th class="w-[5%]">{{ $t("avatar") }}</th>
                <th class="w-[20%]">{{ $t("name") }}</th>
                <th class="w-[20%]">{{ $t("username") }}</th>
                <th class="w-[20%]">{{ $t("email") }}</th>
                <th class="w-[20%]">{{ $t("phone") }}</th>
                <th class="w-[15%]">{{ $t("role") }}</th>
                <th class="w-[15%]">{{ $t("is_active") }}</th>
                <th class="w-[10%] action-col">{{ $t("actions") }}</th>
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
import CheckBox from "@/Components/Others/CheckBox.vue";
import ActionButtons from "@/Components/Others/ActionButtons.vue";
import { Head, router, Link, useForm } from "@inertiajs/vue3";
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

import { i18n } from "@/i18n";
import { useI18n } from "vue-i18n";
import useHelper from "@/composables/useHelper";
const { statusFormat, imageFormat } = useHelper();

import { useMainStore } from "@/stores/main";

import { events } from "@/events";

import { useHelpers } from "@/helpers/useHelpers";
const { isRole, isPermission } = useHelpers();

const mainStore = useMainStore();
const { proxy } = getCurrentInstance();

const { t } = useI18n();
const tableData = ref(false);
const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("admin"), url: null },
  { label: t("users"), url: null },
  { label: t("list"), url: null, is_active: true },
]);

const form = useForm({
  ids: [],
});

onMounted(() => {
  tableData.value = $("#users").DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [
      [1, "asc"],
      // [4, "asc"],
    ],
    aLengthMenu: [
      [5, 10, 25, 50, -1],
      [5, 10, 25, 50, 100, "All"],
    ],
    ajax: {
      url: route("users.list"),
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
        data: "id",
        orderable: false,
        searchable: false,
        className: "checkbox-col",
        render: function (data, type, row, meta) {
          const checkBoxHtml = `<div id="checkbox-${row.id}"></div>`;
          setTimeout(() => {
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
          }, 0);
          return checkBoxHtml;
        },
      },
      {
        data: "avatar_full_path",
        orderable: false,
        searchable: false,
        className: "action-col text-center",
        render: function (data, type, row, meta) {
          return imageFormat(data);
        },
      },
      { data: "name", name: "name" },
      { data: "username", name: "username" },
      { data: "email", name: "email" },
      { data: "phone", name: "phone" },
      {
        data: "roles",
        name: "roles",
        className: "!text-center",
        render: (data) => {
          return statusFormat(data[0].name);
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
        data: "action",
        orderable: false,
        searchable: false,
        className: "action-col",
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
                            router.get(route("users.edit", row.id));
                          },
                        },
                        [h("i", { class: "fa fa-pencil mr-2" }), t("edit")]
                      ),
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

events.on("modal:success", () => {
  tableData.value.ajax.reload();
});

const btnDeleteSelected = () => {
  if (!isRole("owner") && !isRole("admin") && !isPermission(["user-delete"])) {
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
  form.post(route("users.destroy.selected"), {
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
