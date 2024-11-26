<template>
  <DefaultLayout>
    <Head :title="$t('role.list')" />
    <div class="container" v-if="route().current('roles.index')">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 brole-b bg-white dark:brole-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
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
            :href="route('roles.create')"
            class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
          >
            <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
            {{ $t("new") }}
          </Link>
        </div>
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <table id="roles" class="table w-full text-sm text-left rtl:text-right">
            <thead class="text-center bg-white dark:bg-boxdark">
              <tr>
                <th class="w-[3%]">
                  <input
                    type="checkbox"
                    :checked="mainStore.checkAll"
                    @change="mainStore.onCheckAll"
                    class="check-all-row w-4 h-4 text-purple-600 bg-gray-100 brole-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-boxdark-1 dark:brole-gray-600"
                  />
                </th>
                <th class="w-[20%]">{{ $t("name") }}</th>
                <th class="w-[20%]">{{ $t("display_name") }}</th>
                <th class="w-[30%]">{{ $t("description") }}</th>
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
import _ from "lodash";
import CheckBox from "@/Components/Others/CheckBox.vue";
import ActionButtons from "@/Components/Others/ActionButtons.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { onMounted, onBeforeUnmount, reactive, h, createApp, ref } from "vue";

import { i18n } from "@/i18n";
import { useI18n } from "vue-i18n";

import { useMainStore } from "@/stores/main";

import { events } from "@/events";

const mainStore = useMainStore();

const { t } = useI18n();
const tableData = ref(false);
const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("admin"), url: null },
  { label: t("roles"), url: null },
  { label: t("list"), url: null, is_active: true },
]);
onMounted(() => {
  if (route().current("roles.index")) {
    tableData.value = $("#roles").DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      sort: [[0, "desc"]],
      aLengthMenu: [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, 100, "All"],
      ],
      ajax: {
        url: route("roles.list"),
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
        { data: "name", name: "name" },
        { data: "display_name", name: "display_name" },
        { data: "description", name: "description" },
        {
          data: "action",
          roleable: false,
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
                              "cursor-pointer brole-t brole-stroke dark:brole-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                            onClick: (e) => {
                              e.preventDefault();
                              router.get(route("roles.create", row.id));
                            },
                          },
                          [
                            h("i", { class: "fi fi-ts-attribution-pencil mr-2" }),
                            t("edit"),
                          ]
                        ),
                        h(
                          "div",
                          {
                            class:
                              "cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200",
                            onClick: (e) => {
                              e.preventDefault();
                              router.get(route("roles.permissions", row.id));
                            },
                          },
                          [
                            h("i", { class: "fi fi-ts-workflow-setting-alt mr-2" }),
                            t("permissions"),
                          ]
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
                          [h("i", { class: "fi fi-tr-trash-xmark mr-2" }), t("delete")]
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
  }
});

const btnDeleteSelected = () => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : [];
  events.emit("confirm:open", items);
};

events.on("delete-items", (ids) => {
  var routeName = route("roles.destroy.selected", {
    ids: ids,
  });
  NProgress.start();
  axios
    .post(routeName)
    .then((response) => {
      events.emit("confirm:cancel");
      events.emit("confirm:success");
      events.emit("toaster", {
        type: "success",
        message: `${t("item.count", ids.length)} ${t("successfully_deleted")}`,
      });
    })
    .catch((error) => {
      events.emit("confirm:cancel");
      const names = _.map(error.response.data.roles, "name");
      events.emit("toaster", {
        type: "danger",
        message: `[${names}] - ${error.response.data.message}`,
      });
    });
});

events.on("confirm:confirmed", (data) => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : data;
  events.emit("delete-items", items);
});
events.on("confirm:success", () => {
  tableData.value.ajax.reload();
  mainStore.clearSelectedRows();
});
events.on("confirm:cancel", () => {
  NProgress.done();
  NProgress.remove();
});

onBeforeUnmount(() => {
  mainStore.clearSelectedRows();
});
</script>
