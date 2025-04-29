<template>
  <LayoutTab>
    <Head :title="$t('cars.featured')" />
    <div class="container">
      <div
        class="content-header rounded-tl-md rounded-tr-md py-4 bg-white dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <div
          class="flex justify-between items-center w-full pb-4 border-b dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-white"
        >
          <div class="w-[70%] flex justify-items-start gap-5">
            <span>{{ $t("cars.list") }}</span>
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
          <table id="cars" class="table w-full text-sm text-left rtl:text-right">
            <thead class="text-center bg-white dark:bg-boxdark">
              <tr>
                <th class="w-[3%]">
                  {{ $t("#") }}
                </th>
                <th class="w-[5%]">{{ $t("image") }}</th>
                <th class="w-[5%]">{{ $t("code") }}</th>
                <th class="w-[25%]">{{ $t("name") }}</th>
                <th class="w-[5%]">{{ $t("total_price") }}</th>
                <th class="w-[5%]">{{ $t("year") }}</th>
                <th class="w-[10%]">{{ $t("status") }}</th>
                <th class="w-[5%]">{{ $t("likes") }}</th>
                <th class="w-[5%]">{{ $t("view") }}</th>
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
  </LayoutTab>
</template>

<script setup>
import LayoutTab from "../Layout/Index.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import ModalView from "@/Pages/Admin/Cars/ModalView.vue";
import { onMounted, ref, nextTick } from "vue";
import useHelper from "@/composables/useHelper";
const { statusFormat, imageFormat, formatMoney } = useHelper();
import { events } from "@/events";
import { useMainStore } from "@/stores/main";
const mainStore = useMainStore();

const tableData = ref(false);

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

const user_form = useForm({
  user: null,
});
const handleUserChange = (user) => {
  router.get(route("clients.items.like", user.id));
};
const handleUserRemove = (user) => {
  router.get(route("clients.items.like"));
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
      url: route("clients.items.like.list", props.user),
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
      { data: "like_count", name: "like_count", className: "text-center" },
      { data: "view_count", name: "view_count", className: "text-center" },
    ],
    rowCallback: function (row, data, index) {
      const _row = $(row);
      $(_row).find("td:not(:first-child)").addClass("cursor-pointer");
      $(_row)
        .find("td:not(:first-child)")
        .on("click", function (e) {
          e.preventDefault();
          events.emit("modal:modalview:open", {
            modal_title: "car.details",
            event_type: "view",
            item: data,
          });
        });
    },
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
