<template>
  <LayoutTab>
    <Head :title="$t('frontend.guarantees')" />
    <div class="">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 px-0 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <div class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
          <h2
            class="text-md font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white"
          >
            {{ $t("guarantees") }}
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
                {{ $t("guarantee_info") }}
              </legend>
              <div>
                <div class="">
                  <form
                    @submit.prevent="guaranteeFormSubmit"
                    enctype="multipart/form-data"
                  >
                    <div class="mb-6">
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
                        v-model="guaranteeForm.title"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                        :placeholder="$t('title')"
                      />
                      <InputError :message="guaranteeForm.errors.title" class="mt-2" />
                    </div>
                    <div>
                      <Label
                        for_id="image_path"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >{{ $t("image") }}</Label
                      >
                      <FileUpload
                        v-model="guaranteeForm.image_path"
                        target_input="image_path"
                        :selectedFile="selectedImage"
                      />
                      <InputError
                        :message="guaranteeForm.errors.image_path"
                        class="mt-2"
                      />
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
                {{ $t("guarantee_items") }}
              </legend>
              <div>
                <form
                  @submit.prevent="guaranteeItemsFormSubmit"
                  enctype="multipart/form-data"
                >
                  <div>
                    <div class="mb-6">
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
                        v-model="guaranteeItemForm.title"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                        :placeholder="$t('title')"
                      />
                      <InputError
                        :message="guaranteeItemForm.errors.title"
                        class="mt-2"
                      />
                    </div>
                    <div class="mb-[19px]">
                      <Label
                        for_id="description"
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                        >{{ $t("description") }}</Label
                      >
                      <Textarea
                        rows="1"
                        id="description"
                        v-model="guaranteeItemForm.description"
                      ></Textarea>
                      <InputError
                        :message="guaranteeItemForm.errors.description"
                        class="mt-2"
                      />
                    </div>

                    <div>
                      <Label
                        for_id="item_image_path"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >{{ $t("image") }}</Label
                      >
                      <FileUpload
                        v-model="guaranteeItemForm.image_path"
                        target_input="item_image_path"
                        :selectedFile="gt_item_selected_image"
                      />
                      <InputError
                        :message="guaranteeItemForm.errors.image_path"
                        class="mt-2"
                      />
                    </div>

                    <div class="mt-4 mb-6">
                      <h3 class="text-lg font-semibold">
                        Items List:
                        <button
                          type="button"
                          @click="addItem"
                          class="focus:outline-none text-white bg-gray-700 hover:bg-gray-800 focus:ring-2 focus:ring-gray-300 font-medium rounded-lg text-sm p-2 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-900"
                        >
                          ➕
                        </button>
                      </h3>

                      <div
                        v-for="(item, index) in guaranteeItemForm.items"
                        :key="index"
                        class="flex gap-2 mt-2"
                      >
                        <input
                          v-model="item.name"
                          placeholder="Name"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                        />

                        <button
                          v-if="guaranteeItemForm.items.length > 1"
                          type="button"
                          @click="removeItem(index)"
                          class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 transition"
                          :disabled="guaranteeItemForm.items.length === 1"
                        >
                          ✖
                        </button>
                      </div>
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
              id="guaranteeItems"
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
                  <th class="w-[5%]">{{ $t("image") }}</th>
                  <th class="w-[25%]">{{ $t("title") }}</th>
                  <th class="w-[30%]">{{ $t("description") }}</th>
                  <th class="w-[20%]">{{ $t("items") }}</th>
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
import FileUpload from "@/Components/Others/FileUpload.vue";
import Textarea from "@/Components/Others/Textarea.vue";
import LayoutTab from "../../Layout/Index.vue";
import CheckBox from "@/Components/Others/CheckBox.vue";
import ActionButtons from "@/Components/Others/ActionButtons.vue";
import { onMounted, nextTick, onBeforeUnmount, h, createApp, ref, reactive } from "vue";
import { i18n } from "@/i18n";
import { useI18n } from "vue-i18n";
import useHelper from "@/composables/useHelper";
const {
  statusFormat,
  imageFormat,
  countryFlagFormat,
  extractYouTubeVideoId,
} = useHelper();

import { useMainStore } from "@/stores/main";
import { useForm, Head } from "@inertiajs/vue3";

import { events } from "@/events";

const mainStore = useMainStore();

const { t } = useI18n();
const tableData = ref(false);
const selectedImage = ref(null);
const gt_item_selected_image = ref(null);
const item_title = ref(null);
const guaranteeItem = ref(false);
const buttonSubmitItem = ref("save");
const buttonSubmitItemClassname = ref("emerald");

const props = defineProps({
  guarantee: {
    type: Object,
    required: true,
  },
});

const guaranteeForm = useForm({
  id: props.guarantee?.id ?? null,
  title: props.guarantee?.title ?? "",
  image_path: null,
});

const guaranteeItemForm = useForm({
  id: null,
  title: "",
  description: "",
  guarantee_id: props.guarantee?.id,
  image_path: null,
  items: [{ name: "" }],
});

const addItem = () => {
  guaranteeItemForm.items.push({ name: "" });
};

// Function to remove an item input
const removeItem = (index) => {
  if (guaranteeItemForm.items.length > 1) {
    guaranteeItemForm.items.splice(index, 1);
  }
};

selectedImage.value = props.guarantee?.image_path;

const guaranteeFormSubmit = () => {
  guaranteeForm.post(route("frontend.page.guarantees.store"), {
    onSuccess: () => {
      guaranteeForm.image_path = null;
    },
    onError: () => {},
  });
};

onBeforeUnmount(() => {
  guaranteeItem.value = false;
});
onMounted(() => {
  tableData.value = $("#guaranteeItems").DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[1, "desc"]],
    aLengthMenu: [
      [5, 10, 25, 50, -1],
      [5, 10, 25, 50, 100, "All"],
    ],
    ajax: {
      url: route("frontend.page.guaranteeItems.list"),
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
        data: "image_full_path",
        orderable: false,
        searchable: false,
        className: "action-col text-center",
        render: function (data, type, row, meta) {
          return imageFormat(data);
        },
      },

      { data: "title", name: "title" },
      { data: "description", name: "description" },
      { data: "items_list", name: "items_list" },

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
                            guaranteeItemForm.id = row.id;
                            guaranteeItemForm.title = row.title;
                            guaranteeItemForm.description = row.description ?? "";
                            gt_item_selected_image.value = row.image_path ?? null;
                            guaranteeItemForm.items =
                              Array.isArray(row.items) && row.items.length > 0
                                ? row.items.map((item) => ({ name: item.name }))
                                : [{ name: "" }];
                            guaranteeItemForm.clearErrors();
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
  guaranteeItemForm.id = null;
  guaranteeItemForm.title = "";
  guaranteeItemForm.description = "";
  guaranteeItemForm.clearErrors();
  item_title.value.focus();
  guaranteeItemForm.image_path = null;
  gt_item_selected_image.value = null;
  guaranteeItemForm.items = [{ name: "" }];
  events.emit("clear-selected-file");
};

const guaranteeItemsFormSubmit = () => {
  if (guaranteeItemForm.id) {
    guaranteeItemForm.post(
      route("frontend.page.guaranteeItems.update", guaranteeItemForm),
      {
        onSuccess: () => {
          guaranteeItemForm.reset();
          item_title.value.focus();
          tableData.value.ajax.reload();
          buttonSubmitItem.value = "save";
          guaranteeItemForm.image_path = null;
          gt_item_selected_image.value = null;
          guaranteeItemForm.items = [{ name: "" }];
          events.emit("clear-selected-file");
        },
        onError: () => {},
      }
    );
  } else {
    guaranteeItemForm.post(route("frontend.page.guaranteeItems.store"), {
      onSuccess: () => {
        guaranteeItemForm.reset();
        item_title.value.focus();
        tableData.value.ajax.reload();
        guaranteeItemForm.image_path = null;
        gt_item_selected_image.value = null;
        guaranteeItemForm.items = [{ name: "" }];
        events.emit("clear-selected-file");
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
  var routeName = route("frontend.page.guaranteeItems.destroy.selected", {
    ids: items,
  });
  guaranteeItemForm.post(routeName, {
    preserveScroll: true,
    onSuccess: () => {
      events.emit("confirm:cancel");
      events.emit("confirm:success");
      if (guaranteeItemForm.id) {
        cancelUpdateItem();
      }
    },
  });
});
events.on("confirm:success", () => {
  tableData.value.ajax.reload();
  mainStore.clearSelectedRows();
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
