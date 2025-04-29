<template>
  <LayoutTab>
    <Head :title="$t('car_galleries')" />
    <div class="">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 px-0 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <div class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
          <h2
            class="text-md font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white"
          >
            {{ $t("car_galleries") }}
          </h2>
        </div>
        <div class="flex items-center gap-2 justify-center">
          <button
            v-if="mainStore.selectedRows.length"
            @click="btnDeleteSelected"
            class="text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
          >
            <i class="fi fi-rr-trash w-4 h-4 me-2"></i>
            {{ $t("delete") }}
          </button>
        </div>
      </div>
      <div class="relative">
        <div class="mt-4">
          <div class="grid gap-6 mb-6 md:grid-cols-2">
            <fieldset
              class="border border-purple-300 dark:border-purple-400 p-4 rounded-lg"
            >
              <legend class="block mb-2 text-sm font-medium !text-purple-600">
                {{ $t("gallery_info") }}
              </legend>
              <div>
                <div class="">
                  <form
                    @submit.prevent="menuCarGalleryFormSubmit"
                    enctype="multipart/form-data"
                  >
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                      <div>
                        <Label
                          for_id="label"
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                        >
                          {{ $t("label") }}</Label
                        >
                        <input
                          type="text"
                          id="label"
                          ref="label"
                          v-model="menuCarGalleryForm.label"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                          :placeholder="$t('label')"
                        />
                        <InputError
                          :message="menuCarGalleryForm.errors.label"
                          class="mt-2"
                        />
                      </div>
                      <div>
                        <Label
                          for_id="title"
                          class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                        >
                          {{ $t("title") }}</Label
                        >
                        <input
                          type="text"
                          id="title"
                          ref="title"
                          v-model="menuCarGalleryForm.title"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                          :placeholder="$t('title')"
                        />
                        <InputError
                          :message="menuCarGalleryForm.errors.title"
                          class="mt-2"
                        />
                      </div>
                    </div>

                    <button
                      class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
                    >
                      {{ $t("save") }}
                    </button>
                  </form>
                </div>
              </div>
            </fieldset>

            <fieldset
              class="border border-purple-300 dark:border-purple-400 p-4 rounded-lg"
            >
              <legend class="block mb-2 text-sm font-medium !text-purple-600">
                {{ $t("items") }}
              </legend>
              <div>
                <form
                  @submit.prevent="menuCarGalleryItemsFormSubmit"
                  enctype="multipart/form-data"
                >
                  <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                      <Label
                        for_id="item_title"
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >
                        {{ $t("title") }}</Label
                      >
                      <input
                        type="text"
                        id="item_title"
                        ref="item_title"
                        v-model="menuCarGalleryItemForm.title"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                        :placeholder="$t('title')"
                      />
                      <InputError
                        :message="menuCarGalleryItemForm.errors.title"
                        class="mt-2"
                      />
                    </div>
                    <div>
                      <Label
                        for_id="pdf"
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >
                        {{ $t("pdf_file") }}</Label
                      >
                      <input
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                        id="pdf"
                        type="file"
                        ref="fileInput"
                        @change="handleFileUpload"
                        accept="application/pdf"
                      />
                      <span class="mt-2 text-sm text-red-600" v-if="errorMessage">{{
                        errorMessage
                      }}</span>
                      <InputError
                        :message="menuCarGalleryItemForm.errors.pdf"
                        class="mt-2"
                      />
                    </div>
                  </div>

                  <div class="flex justify-center gap-5 items-center">
                    <button
                      @click="cancelUpdateItem"
                      type="button"
                      class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-2 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900"
                    >
                      {{ $t("cancel") }}
                    </button>
                    <button
                      class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
                    >
                      {{ $t(buttonSubmitItem) }}
                    </button>
                  </div>
                </form>
              </div>
            </fieldset>
          </div>

          <div class="relative">
            <table
              id="menuCarGalleryItems"
              class="table w-full text-sm text-left rtl:text-right"
            >
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
                  <th class="w-[25%]">{{ $t("title") }}</th>
                  <th class="w-[25%]">{{ $t("pdf_file") }}</th>
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
    </div>
  </LayoutTab>
</template>

<script setup>
import Label from "@/Components/Others/Label.vue";
import LayoutTab from "../../Layout/Index.vue";
import CheckBox from "@/Components/Others/CheckBox.vue";
import ActionButtons from "@/Components/Others/ActionButtons.vue";
import { onMounted, nextTick, onBeforeUnmount, h, createApp, ref, reactive } from "vue";
import { i18n } from "@/i18n";
import { useI18n } from "vue-i18n";
import { getCurrentInstance } from "vue";
import useHelper from "@/composables/useHelper";
const { statusFormat } = useHelper();

import { useMainStore } from "@/stores/main";
import { useForm, Head } from "@inertiajs/vue3";

const { proxy } = getCurrentInstance();

import { events } from "@/events";

const mainStore = useMainStore();

const { t } = useI18n();
const tableData = ref(false);
const item_title = ref(null);
const menuCarGalleryItem = ref(false);
const buttonSubmitItem = ref("save");

const props = defineProps({
  menuCarGallery: {
    type: Object,
    required: true,
  },
});
const menuCarGalleryStatus = useForm({
  id: null,
  is_active: undefined,
});
const menuCarGalleryForm = useForm({
  id: props.menuCarGallery ? props.menuCarGallery.id : null,
  label: props.menuCarGallery ? props.menuCarGallery.label : "",
  title: props.menuCarGallery ? props.menuCarGallery.title : "",
});

const menuCarGalleryItemForm = useForm({
  id: null,
  title: "",
  pdf: null,
  menu_car_gallery_id: props.menuCarGallery.id ? props.menuCarGallery.id : null,
});

const menuCarGalleryFormSubmit = () => {
  menuCarGalleryForm.post(route("frontend.page.menuCarGallery.store"), {
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
        text: `Car Gallery Info updated.`,
      });
    },
    onError: () => {},
  });
};

onBeforeUnmount(() => {
  menuCarGalleryItem.value = false;
});
onMounted(() => {
  tableData.value = $("#menuCarGalleryItems").DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[1, "desc"]],
    aLengthMenu: [
      [5, 10, 25, 50, -1],
      [5, 10, 25, 50, 100, "All"],
    ],
    ajax: {
      url: route("frontend.page.menuCarGalleryItems.list"),
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

      { data: "title", name: "title" },
      {
        data: "pdf_path",
        name: "pdf_path",
        render: function (data, type, row, meta) {
          if (!data) return "";
          return `<a href="${route("frontend.page.menuCarGalleryItems.pdf-preview", {
            menuCarGalleryItem: row.id,
          })}" class="text-purple-500 hover:underline">
                    <i class="fi fi-rs-file-pdf text-red-500"></i> View
                  </a>`;
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
                            buttonSubmitItem.value = "update";
                            menuCarGalleryItemForm.id = row.id;
                            menuCarGalleryItemForm.title = row.title;
                            menuCarGalleryItemForm.pdf = row.pdf;
                            menuCarGalleryItemForm.clearErrors();
                            item_title.value.focus();
                            scrollToTitle();
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
                            events.emit("confirm:menuCarGallery-active", row);
                          },
                        },
                        [
                          h("i", { class: "fi fi-rr-dot-circle mr-2 mt-[7px]" }),
                          t("is_active"),
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
                        [h("i", { class: "fa fa-trash mr-2" }), t("delete")]
                      ),
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
  });
});

const scrollToTitle = () => {
  if (item_title.value) {
    item_title.value.scrollIntoView({
      behavior: "smooth",
      block: "center",
    });
  }
};

const cancelUpdateItem = () => {
  buttonSubmitItem.value = "save";
  menuCarGalleryItemForm.id = null;
  menuCarGalleryItemForm.title = "";
  menuCarGalleryItemForm.pdf = null;
  menuCarGalleryItemForm.clearErrors();
  item_title.value.focus();

  events.emit("clear-selected-file");
};
const fileInput = ref(null);
const errorMessage = ref("");
const handleFileUpload = (event) => {
  const selectedFile = event.target.files[0];

  if (selectedFile) {
    if (selectedFile.type === "application/pdf") {
      menuCarGalleryItemForm.pdf = selectedFile;
      errorMessage.value = "";
      console.log("Selected file:", selectedFile);
    } else {
      errorMessage.value = "Only PDF files are allowed!";
      event.target.value = "";
    }
  }
};

const menuCarGalleryItemsFormSubmit = () => {
  errorMessage.value = "";
  if (menuCarGalleryItemForm.id) {
    menuCarGalleryItemForm.post(
      route("frontend.page.menuCarGalleryItems.update", menuCarGalleryItemForm),
      {
        onSuccess: () => {
          menuCarGalleryItemForm.reset();
          item_title.value.focus();
          tableData.value.ajax.reload();
          buttonSubmitItem.value = "save";
          menuCarGalleryItemForm.pdf = null;
          nextTick(() => {
            events.emit("clear-selected-file");
            fileInput.value.value = "";
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
              html: `<b>Success:</b> Car Gallery Info Item updated.`,
            });
          });
        },
        onError: () => {},
      }
    );
  } else {
    menuCarGalleryItemForm.post(route("frontend.page.menuCarGalleryItems.store"), {
      onSuccess: () => {
        menuCarGalleryItemForm.reset();
        item_title.value.focus();
        tableData.value.ajax.reload();
        menuCarGalleryItemForm.pdf = null;
        nextTick(() => {
          events.emit("clear-selected-file");
          fileInput.value.value = "";
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
            html: `<b>Success:</b> Car Gallery Info Item created.`,
          });
        });
      },
      onError: () => {},
    });
  }
};

events.on("modal:success", () => {
  tableData.value.ajax.reload();
});

const btnDeleteSelected = () => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : [];
  events.emit("confirm:open", items);
};

events.on("confirm:confirmed", (data) => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : data;
  var routeName = route("frontend.page.menuCarGalleryItems.destroy.selected", {
    ids: items,
  });
  menuCarGalleryItemForm.post(routeName, {
    preserveScroll: true,
    onSuccess: () => {
      events.emit("confirm:cancel");
      events.emit("confirm:success");
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
        html: `<b>Success:</b> Car Gallery Info Item deleted.`,
      });
      if (menuCarGalleryItemForm.id) {
        cancelUpdateItem();
      }
    },
  });
});
events.on("confirm:success", () => {
  tableData.value.ajax.reload();
  mainStore.clearSelectedRows();
});

events.on("confirm:menuCarGallery-active", (item) => {
  proxy.$swal
    .fire({
      title: "Do you want to save the changes?",
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: "Yes",
    })
    .then((result) => {
      if (result.isConfirmed) {
        menuCarGalleryStatus.id = item.id;
        menuCarGalleryStatus.is_active = item.is_active ? false : true;
        menuCarGalleryStatus.post(
          route("frontend.page.menuCarGalleryItems.change-status", menuCarGalleryStatus),
          {
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
                text: `Status updated.`,
              });
              events.emit("confirm:cancel");
              events.emit("confirm:success");
            },
            onError: () => {},
          }
        );
      }
    });
});

onBeforeUnmount(() => mainStore.clearSelectedRows());
</script>

<style lang="scss">
html,
body {
  scroll-behavior: smooth;
}
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
