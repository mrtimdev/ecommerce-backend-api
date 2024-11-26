<template>
    <Teleport to="body">
        <modal size="md" :show=" isVisible " :show-footer=" false " :show-confirm-button=" true " button-confirm-label="save" @close="closeModal">
          <template #title>
            {{ t(modal_title) }}
          </template>
          <template #body>
            <form @submit.prevent=" optionFormSubmit " enctype="multipart/form-data">
              <div class="p-4 md:p-5">
                <div class="mb-6">
                  <Label :isRequired="true" for_id="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">
                    {{ $t('name') }}
                  </Label>
                  <input
                    type="text"
                    id="name"
                    ref="name" 
                    v-model="form.name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder="$t('name')"
                  />
                  <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div>
                  <Label for_id="optionGroup">
                    {{ $t('option_group') }}</Label>
                  <MultiSelect
                    id="optionGroup"
                    v-model="form.option_group" 
                    :options="optionGroups"
                    :multiple="false"
                    track-by="id"
                    :custom-label="(option) => `${option.name}`"
                  >
                  </MultiSelect>
                  <InputError :message=" form.errors.option_group " class="mt-2" />
                </div>
              </div>
                <div class="modal-footer p-4 md:p-5 border-t border-gray-300 dark:border-gray-400">
                  <div class="flex justify-center gap-5 items-center">
                    <button @click=" closeModal " type="button"
                      class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-2 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900">{{
                      $t('close') }}</button>
                    <button
                      class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">{{
                      $t('save') }}</button>
                    
                  </div>
                </div>
            </form>

          </template>
        </modal>
      </Teleport>
</template>

<script setup>
  import Label from '@/Components/Others/Label.vue'
  import InputError from '@/Components/InputError.vue';
  import { reactive, ref, nextTick } from 'vue'
  import { useForm } from '@inertiajs/vue3'
  import { useI18n } from 'vue-i18n';
  const { t } = useI18n();
  import { events } from "@/events"

  import { useMainStore } from "@/stores/main";

const mainStore = useMainStore();

  const isVisible = ref(false)
  const modal_title = ref('slider.add')
  const event_type = ref('add')  
  const name = ref(null)

  const props = defineProps({
      showModal: {
        type: Boolean,
        default: false,
      },
      optionGroups: Object
  })
  const form = useForm({
    id: null,
    name: "",
    option_group: null
  });  
  const closeModal = () => {
    isVisible.value = false
    form.name = ''
    events.emit('modal:close')
    isVisible.value = false
  }
  events.on('modal:open', (data) => {
    modal_title.value = data.modal_title || 'slider.add'
    event_type.value = data.event_type
    isVisible.value = true 
    nextTick(() => {
      name.value.focus();
    })
    form.errors = {}
    if(event_type.value === "edit" && data.item) {
      nextTick(() => {
        form.id = data.item.id
        form.name = data.item.name
        form.option_group = data.item.option_group
      })
    } else {
      form.id = null
      form.name = ''
      form.option_group = null
    }

    nextTick(() => {
      Array.from(document.querySelectorAll('.multiselect__tags')).forEach((element) => {
        element.classList.add(...mainStore.inputClasses);
      });
      Array.from(document.querySelectorAll('.multiselect__input')).forEach((element) => {
        element.classList.add(...mainStore.inputClasses);
      });
    })
    
  })

 

  const optionFormSubmit = () => {
    if(event_type.value === "edit") {
      form.post(route('options.update', form), {
        onSuccess: () => {
          nextTick(() => {
            isVisible.value = false
            events.emit('modal:success')
            events.emit('toaster', {
              type: 'success',
              action: 'update',
              message: `${t('option')} [${form.name}] ${t('successfully_updated')}`,
            })
            form.id = null
            form.name = ''
            form.option_group = null
          })
        },
        onError: () => {
          isVisible.value = true
        }
      })
    } else {
      form.post(route('options.store'), {
        onSuccess: () => {
          nextTick(() => {
            
            events.emit('modal:success')
            events.emit('toaster', {
              type: 'success',
              action: 'create',
              message: `${t('option')} [${form.name}] ${t('successfully_added')}`,
            })
            form.id = null
            form.name = ''
            form.option_group = null
            isVisible.value = true
            name.value.focus();
          })
        },
        onError: () => {
          isVisible.value = true
        }
      })
    }
    
  }

  events.on('delete-items', (ids) => {
    var routeName = route('options.destroy.selected', {
                    ids: ids
                  })
    form.post(routeName, {
      preserveScroll: true,
        onSuccess: () => {
          events.emit('confirm:cancel')
          events.emit('confirm:success')
          events.emit('toaster', {
            type: 'success',
            action: 'delete',
            message: `${t('item.count', ids.length)} ${t('successfully_deleted')}`,
          })
        },
      });
      
  })

    
</script>

<style scoped>
  [for="upload_file"]
  {
    border: 1px dashed #ddd;
  }
  .selected-image
  {
    height: 250px !important;
    border-radius: 0.25rem !important;
    border: 1px solid #ddd !important;
  }
</style>
