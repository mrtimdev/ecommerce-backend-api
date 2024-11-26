<template>
    <Teleport to="body">
        <modal size="md" :show=" isVisible " :show-footer=" false " :show-confirm-button=" true " button-confirm-label="save" @close="closeModal">
          <template #title>
            {{ t(modal_title) }}
          </template>
          <template #body>
            <form @submit.prevent="submitForm">
              <div class="p-4 md:p-5">
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                  <div>
                    <label for="code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                      $t('code') }}</label>
                    <input type="text" ref="code" id="code" v-model=" form.code "
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder=" $t('code') " />
                    <InputError :message=" form.errors.code " class="mt-2" />
                  </div>
                  <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                      $t('name') }}</label>
                    <input type="text" ref="name" id="name" v-model=" form.name "
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder=" $t('name') " />
                    <InputError :message=" form.errors.name " class="mt-2" />
                  </div>
                  
                </div>

                <div class="md-6">
                  <label for="description" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                    $t('description') }}</label>
                  <input type="text" ref="description" id="description" v-model=" form.description "
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder=" $t('description') " />
                  <InputError :message=" form.errors.description " class="mt-2" />
                </div>
                
              </div>
              
              <div class="modal-footer p-4 md:p-5 border-t border-gray-300 dark:border-gray-400">
                <div class="flex justify-center gap-5 items-center">
                  <button @click=" closeModal " type="button"
                    class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900">{{
                    $t('close') }}</button>
                  <button
                    class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">{{
                    $t('save') }}</button>
                  
                </div>
              </div>
            </form>

          </template>
        </modal>
      </Teleport>
</template>

<script setup>
  import InputError from '@/Components/InputError.vue';
  import { ref, watch, nextTick } from 'vue'
  import { useI18n } from 'vue-i18n';
  const { t } = useI18n();
  import { events } from "@/events"
  import { useForm } from '@inertiajs/vue3'

  const props = defineProps({
      showModal: {
        type: Boolean,
        default: false,
      }
  })

  const form = useForm({
    id: null,
    code: '',
    name: '',
    description: '',
  })

  const isVisible = ref(false)
  const name = ref(null)
  const modal_title = ref('steering.add')
  const event_type = ref('add')
  events.on('modal:open', (data) => {
    modal_title.value = data.modal_title || 'steering.add'
    isVisible.value = true
    modal_title.value = data.modal_title
    event_type.value = data.event_type
    
    form.reset("id", "name", "is_active")

    if (event_type.value === 'edit') {
        form.id = data.item.id
        form.code = data.item.code
        form.name = data.item.name
        form.description = data.item.description
    }
    nextTick(() => {
      name.value.focus()
    });
  })

  const submitForm = () => {
    const routeName = event_type.value === 'edit' ? 'steerings.update' : 'steerings.store'
    const routeParams = event_type.value === 'edit' ? form.id : null

    form.post(route(routeName, routeParams), {
        preserveScroll: true,
        onSuccess: () => {
            events.emit('modal:success')
            form.clearErrors()
        },
        onError: () => {
        }
    })
  }
  
  const closeModal = () => {
    events.emit('modal:close')
    isVisible.value = false
    form.clearErrors()
  }
  events.on('modal:success', () => {
    isVisible.value = true
    form.reset("id", "code", "name", "description")
    if(event_type.value === 'edit') {
      isVisible.value = false
    }
    form.clearErrors() 
  })

  events.on('delete-items', (ids) => {
    var routeName = route('steerings.destroy.selected', {
                    ids: ids
                  })
    form.post(routeName, {
      preserveScroll: true,
        onSuccess: () => {
          events.emit('confirm:cancel')
          events.emit('confirm:success')
        },
      });
      
  })
</script>
