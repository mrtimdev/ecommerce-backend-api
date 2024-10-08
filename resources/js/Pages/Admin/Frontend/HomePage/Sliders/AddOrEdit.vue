<template>
    <Teleport to="body">
        <modal size="md" :show=" isVisible " :show-footer=" false " :show-confirm-button=" true " button-confirm-label="save" @close="closeModal">
          <template #title>
            {{ t(action_label) }}
          </template>
          <template #body>
            <form @submit.prevent=" sliderFormSubmit " enctype="multipart/form-data">

              <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                  <label for="title" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                    $t('title') }}</label>
                  <input type="text" ref="title" v-model=" form.title "
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder=" $t('slider.title') " />
                  <InputError :message=" form.errors.title " class="mt-2" />
                </div>

                <div>
                  <label for="description" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                    $t('description') }}</label>
                  <input type="text" ref="description" v-model=" form.description "
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder=" $t('slider.description') " />
                  <InputError :message=" form.errors.description " class="mt-2" />
                </div>
              </div>


              <div class="grid gap-6 mb-6 md:grid-cols-1">
                

                <div>
                  <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                    status</label>
                  <select v-model=" form.is_active "
                    class="bg-gray-50 border   border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                    <option selected>Choose a status</option>
                    <option value="1">{{ $t('active') }}</option>
                    <option value="0">{{ $t('inactive') }}</option>
                  </select>
                  <InputError :message=" form.errors.is_active " class="mt-2" />
                </div>
              </div>
              <div class="gap-6 mb-6 relative">
                <label for="upload_file"
                  :style="{ 'background-image': selectedImage ? 'url(' + selectedImage + ')' : '', 'background-size': 'contain', 'background-position': 'center', 'background-repeat': 'no-repeat' }"
                      :class="{'h-[200px] selected-image': selectedImage, 'h-52': !selectedImage}"
                      class="bg-white dark:bg-meta-2 dark:text-white text-gray-500 font-semibold text-base rounded flex flex-col items-center justify-center cursor-pointer border-2 border-dashed border-gray-400 mx-auto font-[sans-serif]">
                  <svg v-if="!selectedImage" xmlns="http://www.w3.org/2000/svg" class="w-[100px] mb-2 fill-gray-500" viewBox="0 0 32 32">
                    <path
                      d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"
                      data-original="#000000" />
                    <path
                      d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"
                      data-original="#000000" />
                  </svg>
                  <span v-if="!selectedImage">Upload file</span>
                  <input type="file" id="upload_file" @input="form.image_path = $event.target.files[0]" class="hidden" @change="handleFileChange" />
                  <p class="text-xs font-medium text-gray-400 mt-2">PNG, JPG SVG, WEBP, and GIF are Allowed.</p>
                </label>
                <!-- <div class="image-preview">
                  <img :src="selectedImage" alt="preview" class="object-cover h-52 w-full" />
                </div> -->
                <button v-if="selectedImage" @click="removeImage" type="button" class="absolute right-0 top-0 z-2 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                  </svg>
                  <span class="sr-only">Close modal</span>
              </button>
              <InputError :message=" form.errors.image_path " class="mt-2" />
              </div>
              <div class="modal-footer">
                <div class="flex justify-center gap-5 items-center">
                  <button @click=" closeModal " type="button"
                    class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900">{{
                    $t('close') }}</button>
                  <button
                    class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">{{
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
  import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'
  import { useForm } from '@inertiajs/vue3'
  import { useI18n } from 'vue-i18n';
  import { useEventBus } from '@vueuse/core';
  const { t } = useI18n();


  const { on: onOpenModal } = useEventBus('open:slider:modal')
  const { emit: emitCloseModal } = useEventBus('close:slider:modal');
  const emit = defineEmits(['open', 'close', 'success'])

  const isVisible = ref(false)
  const action_label = ref('add')
  const selectedImage = ref(null);
  const title = ref(null)
  const form = useForm({
    id: null,
    title: '',
    description: '',
    is_active: 1,
    image_path: '',
  });

  const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
      selectedImage.value = URL.createObjectURL(file);
    }
    console.log({form})
  }
  const removeImage = () => {
    selectedImage.value = null;

    const input = document.getElementById("upload_file");
    if (input) {
      input.value = '';
    }
  }
  
  const closeModal = () => {
    emitCloseModal()
    isVisible.value = false
    form.title = ''
    form.description = ''
    form.is_active = '1'
    form.image_path = ''
    selectedImage.value = null;
  }
  onOpenModal((action, item) => {
    isVisible.value = true  
    action_label.value = action
    nextTick(() => {
      title.value.focus();
    })
    form.errors = {}
    if(item) {
      nextTick(() => {
        form.id = item.id
        form.title = item.title
        form.description = item.description
        form.is_active = item.is_active
        selectedImage.value = `/storage/${item.image_path}`;
      })
    } else {
      form.title = ''
      form.description = ''
      form.is_active = '1'
      form.image_path = ''
      selectedImage.value = null
    }
    console.log({item})
  })

  defineProps({
      showModal: {
        type: Boolean,
        default: false,
      }
  })

  const sliderFormSubmit = () => {
    if(action_label.value === "edit") {
      form.post(route('frontend.homepage.sliders.update', form), {
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
      form.post(route('frontend.homepage.sliders.store'), {
        onSuccess: () => {
          nextTick(() => {
            form.title = ''
            form.description = ''
            form.is_active = '1'
            form.image_path = ''
            selectedImage.value = null;
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
