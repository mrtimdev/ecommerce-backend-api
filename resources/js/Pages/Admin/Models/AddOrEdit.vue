<template>
    <Teleport to="body">
        <modal size="md" :show=" isVisible " :show-footer=" false " :show-confirm-button=" true " button-confirm-label="save" @close="closeModal">
          <template #title>
            {{ t(modal_title) }}
          </template>
          <template #body>
            <form @submit.prevent="modelStore.submit">
              <div class="p-4 md:p-5">
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                  <div>
                    <label for="code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                      $t('code') }}</label>
                    <input type="text" ref="code" id="code" v-model=" modelStore.form.code "
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder=" $t('model.code') " />
                    <InputError :message=" modelStore.form.errors.code " class="mt-2" />
                  </div>
                  <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                      $t('name') }}</label>
                    <input type="text" ref="name" id="name" v-model=" modelStore.form.name "
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder=" $t('model.name') " />
                    <InputError :message=" modelStore.form.errors.name " class="mt-2" />
                  </div>
                </div>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                  <div v-if="modelStore.brands">
                    <label for="brand" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                      $t('select_brand') }}</label>
                      <select v-model=" modelStore.form.brand_id "
                      id="brand"
                      class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                      <option selected value="">{{ $t('select_brand') }}</option>
                        <option v-for="(brand, index) in modelStore.brands" :key="index" :value="brand.id">{{ brand.name }}</option>
                    </select>
                    <InputError :message=" modelStore.form.errors.brand_id " class="mt-2" />
                  </div>
                  <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('select_status') }}</label>
                    <select v-model=" modelStore.form.is_active "
                      id="status"
                      class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                      <option selected value=""> {{ $t('select_status') }}</option>
                      <option value="1">{{ $t('active') }}</option>
                      <option value="0">{{ $t('inactive') }}</option>
                    </select>
                    <InputError :message=" modelStore.form.errors.is_active " class="mt-2" />
                  </div>
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
  import {useModel} from "@/stores/model";
  const modelStore = useModel();
  import { events } from "@/events"

  const props = defineProps({
      showModal: {
        type: Boolean,
        default: false,
      }
  })
  const emit = defineEmits(['open', 'close', 'success'])

  const isVisible = ref(false)
  const code = ref(null)
  const modal_title = ref('model.add')
  events.on('modal:open', (data) => {
    modal_title.value = data.modal_title || 'model.add'
    isVisible.value = true
    modelStore.isSuccess = false
    modelStore.checkEvent(data)
    nextTick(() => {
      code.value.focus()
    });
  })
  
  const closeModal = () => {
    events.emit('modal:close')
    isVisible.value = false
    modelStore.clearForm()
  }
  events.on('modal:success', () => {
    isVisible.value = false
    modelStore.clearForm() 
  })


    
</script>
