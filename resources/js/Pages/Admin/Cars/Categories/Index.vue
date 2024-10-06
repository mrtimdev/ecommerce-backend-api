<template>
  <DefaultLayout>
    <div class="container">
      <div class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between">
        <div class="breacrumb">
          <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
              <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white">
                  <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                  </svg>
                  {{ $t('home') }}
                </a>
              </li>
              <li>
                <div class="flex items-center">
                  <i class="fi fi-rr-angle-right rtl:rotate-180 w-3 h-3 text-gray-400 mx-1 mb-1"></i>
                  <a href="#" class="ms-1 text-sm font-medium text-gray-700 hover:text-purple-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Projects</a>
                </div>
              </li>
              <li aria-current="page">
                <div class="flex items-center">
                  <i class="fi fi-rr-angle-right rtl:rotate-180 w-3 h-3 text-gray-400 mx-1 mb-1"></i>
                  <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Flowbite</span>
                </div>
              </li>
            </ol>
          </nav>

        </div>
        <div class="flex items-center gap-2 justify-center">
          <button v-if="selectedRows.length" @click="btnDeleteSelected" class="text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300">
            <i class="fi fi-rr-trash w-4 h-4 me-2"></i>
            {{ $t('delete') }}
          </button>
          <button @click=" openModalFormAdd('add', false) " class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300">
            <i class="fi fi-rr-plus w-4 h-4 me-2"></i>
            {{ $t('add') }}
          </button>
        </div>
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <table id="car-category"
            class="table w-full text-sm text-left rtl:text-right">
            <thead class="text-center bg-white dark:bg-boxdark">
              <tr>
                <th class="w-[5%]">
                  <input type="checkbox"  
                    :checked="checkAll" @change="onHandleCheckAll"
                    class="check-all-row w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                </th>
                <th class="w-[70%]">{{ $t('name') }}</th>
                <th class="w-[10%]">{{ $t('status') }}</th>
                <th class="w-[10%] action-col">{{ $t('actions') }}</th>
              </tr>
            </thead>
            <tbody class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark"></tbody>
          </table>
        </div>
      </div>  
    </div>
    <AddOrEditForm @close="closeModal" @success="onModalSuccess"/>
  </DefaultLayout>
</template>

<script setup>

import CheckBox from '@/Components/Others/CheckBox.vue';
import ActionButtons from '@/Components/Others/ActionButtons.vue';
import AddOrEditForm from './AddOrEdit.vue';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { onMounted, onBeforeUnmount, h, createApp, ref, nextTick, watch, computed } from 'vue';
import useCarCategories from '@/composables/useCarCategories';
import { i18n } from '@/i18n';
import { useEventBus } from '@vueuse/core';
import { useI18n } from 'vue-i18n';
import useHelper from '@/composables/useHelper'
const { statusFormat } = useHelper()

const { t } = useI18n();
const tableData = ref(false);
const selectedRows = ref([]);
const checkAll = ref(false);
const showDeleteButton = ref(false);

const { on: actionConfirmed } = useEventBus('action:confirmed');
const { emit: openConfirmPopUp } = useEventBus('confirm:open');
const { emit: closePopup } = useEventBus('popup:close');

const { emit: emitOpenModal } = useEventBus('open:car:category:modal');
const { on: onCloseModal } = useEventBus('close:car:category:modal');

const openModalFormAdd = (action = 'add', item = false) => {
  emitOpenModal(action, item)
}

const btnEdit = (item) => {
  emitOpenModal('edit', item)
}

const onModalSuccess = (arg) => {
  tableData.value.ajax.reload();
}
const closeModal = (arg) => {
  console.log({arg})
}

onCloseModal(() => {
  console.log('Closing Modal')
})

onMounted(() => {

  tableData.value = $('#car-category').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[1, 'desc']],
    aLengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 100, "All"]],
    ajax: {
      url: route('cars.categories.list'),
      type: 'GET',
    },
    columns: [
      {
        data: 'id',
        orderable: false, searchable: false,
        className: 'checkbox-col',
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
                  modelValue: false,
                  class: 'check-row',
                  value: parseInt(row.id),
                  'onUpdate:modelValue': (checked) => onCheckboxChange(checked, parseInt(row.id))
                });
              }
            });

            container.__vueApp__ = checkBoxApp;
            checkBoxApp.use(i18n).mount(container);
          }, 0)
          return checkBoxHtml;
        },
      },
      { data: 'name', name: 'name' },
      { data: 'status', 
        className: 'action-col',
        sorderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
          const status = data === 1 ? 'active' : 'inactive';
          // return statusFormat(data === 1 ? 'active' : 'inactive');
          if (status === 'active') {
              return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-success text-white">
                  ${status}
              </div>`
          } else if (status === 'inactive') {
              return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-red-600 text-white">
                  ${status}
              </div>`
          } else {
              return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded-full bg-gray-600 text-white">
                  ${status}
              </div>`
          }
        }
      },
      {
        data: 'action',
        orderable: false,
        searchable: false,
        className: 'action-col',
        render: function (data, type, row, meta) {
          const actionsHtml = `<div id="actions-${row.id}"></div>`;
          setTimeout(() => {
            const container = document.getElementById(`actions-${row.id}`);
            if (container.__vueApp__) {
              container.__vueApp__.unmount();
            }
            const actionsApp = createApp({
              render() {
                return h(ActionButtons, { item: row }, {
                  'action-buttons': () => [
                    h('div', {
                      class: 'cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200',
                      onClick: () => btnEdit(row),
                    }, [
                      h('i', { class: 'fa fa-pencil mr-2' }),
                      t('edit'),
                    ]),
                    h('div', {
                      class: 'cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200',
                      onClick: () => btnDelete(row),
                    }, [
                      h('i', { class: 'fa fa-trash mr-2' }),
                      t('delete'), 
                    ]),
                  ]
                });
              }
            });
            container.__vueApp__ = actionsApp;
            actionsApp.use(i18n).mount(container);
          }, 0);

          return actionsHtml;
        },
      },
    ],
  });
  actionConfirmed((item, action) => {
    deleteCategory(item, action)
    closePopup()
    tableData.value.ajax.reload();
  })
  
})

const {
  deleteCategory,
} = useCarCategories()
const onHandleCheckAll = () => {
  checkAll.value = !checkAll.value;
  const checkboxes = document.querySelectorAll('.check-row');
  checkboxes.forEach(function(checkbox) {
    checkbox.checked = checkAll.value;
    const id = checkbox.value;
    if (checkAll.value) {
      if (!selectedRows.value.includes(id)) {
        selectedRows.value.push(id);
      }
    } else {
      selectedRows.value = [];
    }
  });
};
const onCheckboxChange = (checked, id) => {
  if (checked) {
    if (!selectedRows.value.includes(id)) {
      selectedRows.value.push(id);
    }
  } else {
    selectedRows.value = selectedRows.value.filter(val => parseInt(val) !== parseInt(id));
  }
  const totalCheckboxes = document.querySelectorAll('.check-row').length;
  const checkedCheckboxes = document.querySelectorAll('.check-row:checked').length;
  checkAll.value = checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0;
  console.log('checked row', selectedRows.value)
};
function btnDelete(item) {
  openConfirmPopUp({
    data: item,
    action: 'delete',
  });
}

const btnDeleteSelected = () => {
  openConfirmPopUp({
    data: selectedRows.value.length > 0 ? selectedRows : false,
    action: t('delete-selected'),
  });
}
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
