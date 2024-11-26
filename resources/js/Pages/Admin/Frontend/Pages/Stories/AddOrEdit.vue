<template>
    <Teleport to="body">
        <modal size="md" :show=" isVisible " :show-footer=" false " :show-confirm-button=" true " button-confirm-label="save" @close="closeModal">
          <template #title>
            {{ t(modal_title) }}
          </template>
          <template #body>
            <form @submit.prevent=" storyFormSubmit " enctype="multipart/form-data">
              <div class="p-4 md:p-5">
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                  <div>
                    <Label :is-required="true" for_id="title" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">
                      {{ $t('title') }}</Label>
                    <input type="text" id="title" ref="title" v-model=" form.title "
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder=" $t('title') " />
                    <InputError :message=" form.errors.title " class="mt-2" />
                  </div>
                  <div>
                    <Label for_id="is_active" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                      {{ $t('select') }} {{ $t('status') }}
                    </Label>
                    <select id="is_active" v-model=" form.is_active "
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11">
                      <option disabled>{{ $t('select') }} {{ $t('status') }}</option>
                      <option :value="true">{{ $t('active') }}</option>
                      <option :value="false">{{ $t('inactive') }}</option>
                    </select>
                    <InputError :message=" form.errors.is_active " class="mt-2" />
                  </div>
                  <div>
                    <Label for_id="youtube_link" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">
                      {{ $t('youtube_link') }}</Label>
                    <input type="text" id="youtube_link" ref="youtube_link" v-model=" form.youtube_link "
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder=" $t('youtube_link') " />
                    <Label for_id="youtube_link" class="block mb-2 text-xs font-normal text-gray-700 dark:text-white">
                      Ex) https://www.youtube.com/watch?v=JZCwYaujhcQ&t=250s</Label>
                    <InputError :message=" form.errors.youtube_link " class="mt-2" />
                  </div>
                  <div>
                    <Label for_id="country" class="block font-medium mb-1">{{ $t('country') }}</Label>
                    <select id="country" v-model=" form.country_id "
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11">
                        <option :value="null" disabled>{{ $t('select') }} {{ $t('country') }}</option>
                        <option v-for="(country, index) in settingStore.countries" :value="country.id" :key="index">{{ country.name }}</option>
                      </select>
                    <InputError :message="form.errors.country" class="mt-2" />
                    
                  </div>
                </div>
                
                <div class="mb-6">
                  <Label for_id="description" class="block font-medium mb-1">{{ $t('description') }}</Label>
                  <Textarea id="description" v-model="form.description"></Textarea>
                  <InputError :message="form.errors.description" class="mt-2" />
                </div>


                <div class="gap-6 mb-6 relative">
                  <Label  
                    :is-required="true"
                    for_id="image_path"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >{{ $t("image") }}</Label
                  >
                  <FileUpload v-model="form.image_path" target_input="image_path" :selectedFile="selectedImage" />
                  <InputError :message="form.errors.image_path" class="mt-2" />            
                </div>
              </div>
                <div class="modal-footer p-4 md:p-5 border-t border-gray-300 dark:border-gray-400">
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
  import Label from '@/Components/Others/Label.vue'
  import FileUpload from '@/Components/Others/FileUpload.vue'
  import Textarea from '@/Components/Others/Textarea.vue'
  import InputError from '@/Components/InputError.vue';
  import { ref, nextTick } from 'vue'
  import { useForm } from '@inertiajs/vue3'
  import { useI18n } from 'vue-i18n';
  const { t } = useI18n();
  import { events } from "@/events"
  import { useSetting } from "@/stores/setting"
  

  const settingStore = useSetting()


  const isVisible = ref(false)
  const modal_title = ref('story.add')
  const event_type = ref('add')
  
  

  events.on('modal:success', () => {
    isVisible.value = false
  })

  const selectedImage = ref(null);
  const title = ref(null)
  const form = useForm({
    id: null,
    title: '',
    youtube_link: '',
    facebook_link: '',
    description: '',
    is_active: true,
    image_path: null,
    country_id: null,
  });


  
  const closeModal = () => {
    
    isVisible.value = false
    resetForm()
    events.emit('modal:close')
    isVisible.value = false
  }
  events.on('modal:open', (data) => {
    modal_title.value = data.modal_title || 'story.add'
    event_type.value = data.event_type
    isVisible.value = true
    nextTick(() => {
      title.value.focus();
    })
    form.errors = {}
    if(event_type.value === "edit" && data.item) {
      nextTick(() => {
        form.id = data.item.id
        form.title = data.item.title
        form.is_active = data.item.is_active
        selectedImage.value = data.item.image_path;
        form.image_path = null
        form.country_id = data.item.country_id;
        form.description = data.item.description ?? "";
        form.youtube_link = data.item.youtube_link;
      })
    } else {
      resetForm()
    }

    settingStore.getCountries()
  })

  const resetForm = () => {
    form.title = ''
    form.is_active = true
    form.youtube_link = ""
    form.facebook_link = ""
    form.description = ""
    form.country_id = null
    form.image_path = null
    selectedImage.value = null;
    form.clearErrors()
  }

  const props = defineProps({
      showModal: {
        type: Boolean,
        default: false,
      }
  })

  const storyFormSubmit = () => {
    if(event_type.value === "edit") {
      form.post(route('frontend.page.stories.update', form), {
        onSuccess: () => {
          nextTick(() => {
            form.reset()
            isVisible.value = false
            events.emit('modal:success')
          })
        },
        onError: () => {
          isVisible.value = true
        }
      })
    } else {
      form.post(route('frontend.page.stories.store'), {
        onSuccess: () => {
          nextTick(() => {
            form.title = ''
            form.description = ''
            form.is_active = true
            form.image_path = ''
            selectedImage.value = null;
            isVisible.value = false
            events.emit('modal:success')
          })
        },
        onError: () => {
          isVisible.value = true
        }
      })
    }
    
  }


  events.on('delete-items', (ids) => {
    var routeName = route('frontend.page.stories.destroy.selected', {
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
