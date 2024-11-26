<template>
  <LayoutTab>
    <Head :title="$t('frontend.hotmarks')"/>
    <div class="">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 px-0 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <div class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
          <h2 class="text-md font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white">{{ $t('hotmarks') }}</h2>
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
          <button
            @click.prevent="openModalForm"
            class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
          >
            <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
            {{ $t("new") }}
          </button>
        </div>
      </div>
      <div class="relative">
        <table id="hotmark"
          class="table w-full text-sm text-left rtl:text-right">
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
              <th class="w-[87%]">{{ $t('title') }}</th>
              <th class="w-[10%] action-col">{{ $t('actions') }}</th>
            </tr>
          </thead>
          <tbody class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark"></tbody>
        </table>
      </div>
    </div>
    <AddOrEdit :show-modal="false"/>
  </LayoutTab>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import LayoutTab from '../../Layout/Index.vue';
import CheckBox from '@/Components/Others/CheckBox.vue';
import ActionButtons from '@/Components/Others/ActionButtons.vue';
import AddOrEdit from './AddOrEdit.vue';
import { onMounted, nextTick, onBeforeUnmount, h, createApp, ref, reactive } from 'vue';
import { i18n } from '@/i18n';
import { useI18n } from 'vue-i18n';
import useHelper from '@/composables/useHelper'
const { statusFormat, imageFormat } = useHelper()

import { useMainStore } from "@/stores/main";

import { events } from "@/events"

const mainStore = useMainStore();

const { t } = useI18n();
const tableData = ref(false);



const openModalForm = () => {
  events.emit('modal:open', {
    modal_title: "hotmark.add",
    event_type: "add",
  });
}

onMounted(() => {

  tableData.value = $('#hotmark').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[1, 'desc']],
    aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 100, "All"]],
    ajax: {
      url: route('hotmarks.list'),
      type: 'GET',
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
      { data: 'name', name: 'name' },
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
                            events.emit('modal:open', {
                              modal_title: "hotmark.edit",
                              event_type: "edit",
                              item: row
                            });
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
                            events.emit('confirm:open', [row.id]);
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

  
})


events.on('modal:success', () => {
  tableData.value.ajax.reload();
});

const btnDeleteSelected = () => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : []
  events.emit('confirm:open', items);
};

events.on('confirm:confirmed', (data) => {
  const items = mainStore.selectedRows.length > 0 ? mainStore.selectedRows : data;
  events.emit('delete-items', items)
})
events.on('confirm:success', () => {
  tableData.value.ajax.reload();
  mainStore.clearSelectedRows();
})

onBeforeUnmount(() => mainStore.clearSelectedRows());


</script>

<style lang="scss">
  .table tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, .05);
  }
  
  .dark  {
    .table tr th,
    .table tr td{
      border-color:  rgba(209, 199, 199, 0.05) !important;
      border: 1px solid;
    }
  }
  .table {
    tr {
      td, th
      {
        border-color: #dedada !important;
        border: 1px solid;
      }
    }
  }
  .checkbox-col,
  .action-col {
    text-align: center !important;
  }

  .dt-length
  {
    >select {
      width: 50px !important;
    }
  }
  .table {
    .text-center {
      text-align: center !important;
    }
  }
</style>
