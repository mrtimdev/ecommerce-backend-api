<template>
    <Teleport to="body">
        <modal size="lg" :show=" isVisible " :show-footer=" false " :show-confirm-button=" true " button-confirm-label="save" @close="closeModal">
          <template #title>
            {{ t(modal_title) }}
          </template>
          <template #body>
            <form @submit.prevent=" menuFormSubmit " enctype="multipart/form-data">
              <div class="p-4 md:p-5">
                
                  <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                      <Label :isRequired="true" for_id="code">
                        {{ $t('code') }}</Label>
                      <input type="text" id="code" ref="code" v-model=" form.code "
                        :class="mainStore.inputClasses"
                        :placeholder=" $t('code') " />
                      <InputError :message=" form.errors.code " class="mt-2" />
                    </div>
                    <div>
                      <Label :isRequired="true" for_id="name">
                        {{ $t('name') }}</Label>
                      <input type="text" id="name" ref="name" v-model=" form.name "
                        :class="mainStore.inputClasses"
                        :placeholder=" $t('name') " />
                      <InputError :message=" form.errors.name " class="mt-2" />
                    </div>
                  </div>
                  <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                      <Label for_id="brands">
                        {{ $t('brands') }}</Label>
                      <MultiSelect
                        id="brands"
                        v-model="form.brands" 
                        :options="brands"
                        :multiple="true"
                        track-by="id"
                        @select="handleBrandChange"
                        @remove="handleBrandRemove"
                        :custom-label="(option) => `${option.name}`"
                      >
                      </MultiSelect>
                      <InputError :message=" form.errors.brands " class="mt-2" />
                    </div>

                    <div>
                      <Label for_id="categories">
                        {{ $t('categories') }}</Label>
                      <MultiSelect
                        id="categories"
                        v-model="form.categories" 
                        :options="categories"
                        :multiple="true"
                        track-by="id"
                        :custom-label="(option) => `${option.name}`"
                      >
                      </MultiSelect>
                      <InputError :message=" form.errors.categories " class="mt-2" />
                    </div>

                    <div>
                      <Label for_id="models">
                        {{ $t('models') }}</Label>
                      <MultiSelect
                        id="models"
                        v-model="form.models" 
                        :options="models"
                        :multiple="true"
                        track-by="id"
                        :custom-label="(option) => `${option.name}`"
                      >
                      </MultiSelect>
                      <InputError :message=" form.errors.models " class="mt-2" />
                    </div>
                    <div>
                      <Label for_id="fuel_types">
                        {{ $t('fuel_types') }}</Label>
                      <MultiSelect
                        id="fuel_types"
                        v-model="form.fuel_types" 
                        :options="fuel_types"
                        :multiple="true"
                        track-by="id"
                        :custom-label="(option) => `${option.name}`"
                      >
                      </MultiSelect>
                      <InputError :message=" form.errors.fuel_types " class="mt-2" />
                    </div>
                    <div>
                      <Label for_id="steerings">
                        {{ $t('steerings') }}</Label>
                      <MultiSelect
                        id="steerings"
                        v-model="form.steerings" 
                        :options="steerings"
                        :multiple="true"
                        track-by="id"
                        :custom-label="(option) => `${option.name}`"
                      >
                      </MultiSelect>
                      <InputError :message=" form.errors.steerings " class="mt-2" />
                    </div>

                    <div>
                      <Label for_id="drive_types">
                        {{ $t('drive_types') }}</Label>
                      <MultiSelect
                        id="drive_types"
                        v-model="form.drive_types" 
                        :options="drive_types"
                        :multiple="true"
                        track-by="id"
                        :custom-label="(option) => `${option.name}`"
                      >
                      </MultiSelect>
                      <InputError :message=" form.errors.drive_types " class="mt-2" />
                    </div>
                    <div>
                      <Label for_id="passengers">
                        {{ $t('passengers') }}</Label>
                      <MultiSelect
                        id="passengers"
                        v-model="form.passengers" 
                        :options="passengers"
                        :multiple="true"
                        track-by="id"
                        :custom-label="(option) => `${t('seat.count', {count: parseInt(option.name)})}`"
                      >
                      </MultiSelect>
                      <InputError :message=" form.errors.passengers " class="mt-2" />
                    </div>


                    <div>
                      <Label for_id="locations">
                        {{ $t('locations') }}</Label>
                      <MultiSelect
                        id="locations"
                        v-model="form.locations" 
                        :options="locations"
                        :multiple="true"
                        track-by="id"
                        :custom-label="(option) => `${option.name}`"
                      >
                      </MultiSelect>
                      <InputError :message=" form.errors.locations " class="mt-2" />
                    </div>
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
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<script setup>
  import _ from 'lodash';
  import Label from '@/Components/Others/Label.vue'
  import InputError from '@/Components/InputError.vue';
  import { ref, nextTick, onMounted } from 'vue'
  import { useForm } from '@inertiajs/vue3'
  import { useI18n } from 'vue-i18n';
  const { t } = useI18n();
  import { useMainStore } from "@/stores/main";

  import { events } from "@/events"

  const mainStore = useMainStore();


  const isVisible = ref(false)
  const modal_title = ref('slider.add')
  const event_type = ref('add')
  
  

  events.on('modal:success', () => {
    isVisible.value = false
  })

  const props = defineProps({
    showModal: {
      type: Boolean,
      default: false,
    },
    brands: Array,
    // models: Array,
    categories: Array,
    fuel_types: Array,
    steerings: Array,
    locations: Array,
    drive_types: Array,
    passengers: Array,
  })
  const models = ref([])
  const selectedImage = ref(null);
  const code = ref(null)
  const form = useForm({
    id: null,
    code: '',
    name: '',
    brands: [],
    models: [],
    categories: [],
    fuel_types: [],
    steerings: [],
    locations: [],
    drive_types: [],
    passengers: [],
  });


  
  const closeModal = () => {
    
    isVisible.value = false
    clearForm()
    
    events.emit('modal:close')
    isVisible.value = false
  }
  events.on('modal:open', (data) => {
    console.log({data})
    modal_title.value = data.modal_title || 'slider.add'
    event_type.value = data.event_type
    isVisible.value = true
    nextTick(() => {
      code.value.focus();
    })
    form.errors = {}
    if(event_type.value === "edit" && data.item) {
      nextTick(() => {
        form.id = data.item.id
        form.code = data.item.code
        form.name = data.item.name
        form.brands = data.item.brands
        form.models = data.item.models
        form.categories = data.item.categories
        form.fuel_types = data.item.fuel_types
        form.steerings = data.item.steerings
        form.locations = data.item.locations
        form.drive_types = data.item.drive_types
        form.passengers = data.item.passengers

        handleBrandChange()
      })
    } else {
      clearForm()
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

  

  const menuFormSubmit = () => {
    if(event_type.value === "edit") {
      form.post(route('frontend.page.menus.update', form), {
        onSuccess: () => {
          clearForm()
          isVisible.value = false
          events.emit('modal:success')
        },
        onError: () => {
          isVisible.value = true
        }
      })
    } else {
      form.post(route('frontend.page.menus.store'), {
        onSuccess: () => {
          nextTick(() => {
            clearForm()
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
    var routeName = route('frontend.page.menus.destroy.selected', {
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

  const clearForm = () => {
    form.id = null
    form.code = ""
    form.name = ""
    form.brands = []
    form.models = []
    form.categories = []
    form.fuel_types = []
    form.steerings = []
    form.locations = []
    form.drive_types = []
    form.passengers = []
    models.value = []
  }


const handleBrandChange = () => {
  models.value = []
  if(event_type.value === "add") {
    form.models = []
  }
  const ids = _.map(form.brands, 'id');
  if (!_.isEmpty(ids)) {
    axios.get(route('models.by.brand', {ids: ids})).then((res) => {
      models.value = res?.data.models
    })
  }

}

const handleBrandRemove = (brand) => {
  models.value = []
  form.models = []
  handleBrandChange()
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
