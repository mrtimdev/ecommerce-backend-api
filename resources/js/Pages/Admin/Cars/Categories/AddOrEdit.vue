<template>
    <Teleport to="body">
        <modal size="md" :show=" isVisible " :show-footer=" false " :show-confirm-button=" true " button-confirm-label="save" @close="closeModal">
          <template #title>
            {{ t(action_label) }}
          </template>
          <template #body>
            <form @submit.prevent=" categoryFormSubmit ">
              <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                  <label for="first_name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                    $t('name') }}</label>
                  <input type="text" ref="name" v-model=" form.name "
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder=" $t('category.name') " />
                  <InputError :message=" form.errors.name " class="mt-2" />
                </div>

                <div>
                  <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an
                    option</label>
                  <select v-model=" form.status "
                    class="bg-gray-50 border   border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                    <option selected>Choose a status</option>
                    <option value="1">{{ $t('active') }}</option>
                    <option value="0">{{ $t('inactive') }}</option>
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <div class="flex justify-center gap-5 items-center">
                  <button
                    class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">{{
                    $t('save') }}</button>
                  <button @click=" closeModal " type="button"
                    class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900">{{
                    $t('close') }}</button>
                </div>
              </div>
            </form>

          </template>
        </modal>
      </Teleport>
</template>

<script setup>
  import InputError from '@/Components/InputError.vue';
  import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'
  import { useForm } from '@inertiajs/vue3'
  import { useI18n } from 'vue-i18n';
  import { useEventBus } from '@vueuse/core';
  const { t } = useI18n();


  const { on: onOpenModal } = useEventBus('open:car:category:modal')
  const { emit: emitCloseModal } = useEventBus('close:car:category:modal');
  const emit = defineEmits(['open', 'close', 'success'])

  const isVisible = ref(false)
  const name = ref(null)
  const action_label = ref('add')
  const form = useForm({
    id: null,
    name: '',
    status: 1,
  })
  
  const closeModal = () => {
    emitCloseModal()
    isVisible.value = false
    form.reset()
  }
  onOpenModal((action, item) => {
    isVisible.value = true  
    action_label.value = action
    nextTick(() => {
      form.name = ''
      name.value.focus();
    })
    if(item) {
      nextTick(() => {
        form.id = item.id
        form.name = item.name
        form.status = item.status
      })
      if (name.value) {
        name.value.focus();
      }
    }
    console.log({item})
  })

  defineProps({
      showModal: {
        type: Boolean,
        default: false,
      }
  })

  const categoryFormSubmit = () => {
    if(action_label.value === "edit") {
      form.put(route('cars.categories.update', form.id), {
        onSuccess: () => {
          nextTick(() => {
            form.reset()
            isVisible.value = false
            emit('close', 'emit close modal')
            emit('success', 'emit success modal')
          })
        },
        onError: () => {
          isVisible.value = true
        }
      })
    } else {
      form.post(route('cars.categories.store'), {
        onSuccess: () => {
          nextTick(() => {
            form.reset('name')
            isVisible.value = false
            emit('close', 'emit close modal')
            emit('success', 'emit success modal')
          })
        },
        onError: () => {
          isVisible.value = true
        }
      })
    }
    
  }

    
</script>
