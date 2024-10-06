


<template>
  <DefaultLayout>
  <div>
    <DataTable
      :columns="columns"
      :data="tableData"
      :serverSide="true"
      :processing="true"
      :ajax="fetchData"
      @draw="onTableDraw"
    />
  </div>
</DefaultLayout>
</template>

<script setup>

import DefaultLayout from '@/Layouts/DefaultLayout.vue'
import { ref, h, nextTick , createApp   } from 'vue'
// import 'datatables.net-dt/css/jquery.dataTables.css'
import { DataTable } from 'datatables.net-vue3'
import ActionButtons from '@/Components/Others/ActionButtons.vue'
import axios from 'axios'
import { i18n } from '@/i18n';

import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Define the table columns
const columns = ref([
  { title: 'ID', data: 'id' },
  { title: 'Name', data: 'name' },
  {
    title: 'Status',
    data: null,
    orderable: false,
    searchable: false,
    render(data, type, row) {
      return `<div id="action-buttons-${row.id}"></div>`  // Placeholder div
    }
  }
])

// Data container for server response
const tableData = ref([])

// Fetch data from API using server-side processing
const fetchData = (data, callback) => {
  const searchValue = data.search ? data.search.value : ''
  // You can send additional params such as page, search, etc. based on DataTables' requests
  axios.get(route('cars.categories.list'), {
    draw: data.draw,
    start: data.start,
    length: data.length,
    search: searchValue
  })
  .then((response) => {
    // Pass data to DataTables
    callback({
      draw: response.data.draw,
      recordsTotal: response.data.recordsTotal,
      recordsFiltered: response.data.recordsFiltered,
      data: response.data.data
    })
    tableData.value = response.data.data
  })
  .catch((error) => {
    console.error('Error fetching data:', error)
  })
}

const onTableDraw = () => {
  nextTick(() => {
    tableData.value.forEach(row => {
      const container = document.getElementById(`action-buttons-${row.id}`);
      if (container) {
        // Check if there is already an app mounted and unmount it
        if (container.__vueApp__) {
          container.__vueApp__.unmount();
        }

        // Dynamically create and mount a new app instance
        const app = createApp({
          components: { ActionButtons }, // Register ActionButtons component
          setup() {
            const btnEdit = (item) => {
              console.log('Edit clicked for:', item);
              // Your edit logic here
            };

            const btnDelete = (item) => {
              console.log('Delete clicked for:', item);
              // Your delete logic here
            };

            return () => h('div', [
              h(ActionButtons, { item: row }, {
                'action-buttons': () => [
                  h('div', {
                    class: 'cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200',
                    onClick: () => btnEdit(row),
                  }, [
                    h('i', { class: 'fa fa-pencil mr-2' }),
                    t('edit') // Assuming `t` is a translation function
                  ]),
                  h('div', {
                    class: 'cursor-pointer border-t border-stroke dark:border-gray-200 inline-flex justify-start items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-100 w-full text-left --hover:bg-gray-200',
                    onClick: () => btnDelete(row),
                  }, [
                    h('i', { class: 'fa fa-trash mr-2' }),
                    t('delete') // Assuming `t` is a translation function
                  ]),
                ]
              })
            ]);
          }
        });

        // Store the app instance in the container for later unmounting
        container.__vueApp__ = app;

        app.use(i18n).mount(container); // Use i18n and mount the app
      }
    });
  });
}


const btnDelete = () => {
  
}

const btnEdit = () => {

}
</script>

<style scoped>
/* Add any custom styling you want */
</style>
