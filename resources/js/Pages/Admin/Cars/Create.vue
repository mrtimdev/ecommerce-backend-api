<template>
  <DefaultLayout>
    <Head :title="$t('categories')" />
    <div class="container">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <Bc :crumbs="breadcrumbs" />
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div>
                <Label :isRequired="true"
                  for_id="code"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("code") }}</Label
                >
                <input
                  autofocus
                  type="text"
                  ref="code"
                  id="code"
                  v-model="form.code"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('code')"
                />
                <InputError :message="form.errors.code" class="mt-2" />
              </div>

              <div>
                <Label :isRequired="true"
                  for_id="name"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("name") }}</Label
                >
                <input
                  type="text"
                  ref="name"
                  id="name"
                  v-model="form.name"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('name')"
                />
                <InputError :message="form.errors.name" class="mt-2" />
              </div>
            </div>

            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div>
                <Label :isRequired="true"
                  for_id="current_price"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("current_price") }}</Label
                >
                <input
                  autofocus
                  type="text"
                  ref="current_price"
                  id="current_price"
                  v-model="form.current_price"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('current_price')"
                />
                <InputError :message="form.errors.current_price" class="mt-2" />
              </div>

              <div>
                <Label
                  for_id="previous_price"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("previous_price") }}</Label
                >
                <input
                  type="text"
                  ref="previous_price"
                  id="previous_price"
                  v-model="form.previous_price"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('previous_price')"
                />
                <InputError :message="form.errors.previous_price" class="mt-2" />
              </div>
            </div>

            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div>
                <Label :isRequired="true"
                  for_id="year"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("year") }}</Label
                >
                <input
                  type="number"
                  ref="year"
                  id="year"
                  v-model="form.year"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('year')"
                />
                <InputError :message="form.errors.year" class="mt-2" />
              </div>
              <div>
                <Label :isRequired="true"
                  for_id="mileage"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("mileage") }}</Label
                >
                <input
                  autofocus
                  type="text"
                  ref="mileage"
                  id="mileage"
                  v-model="form.mileage"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('mileage')"
                />
                <InputError :message="form.errors.mileage" class="mt-2" />
              </div>
            </div>

            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div class="grid gap-6 md:grid-cols-1">
                <div>
                  <Label :isRequired="true"
                    for_id="plate_number"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                    >{{ $t("plate_number") }}</Label
                  >
                  <input
                    autofocus
                    type="text"
                    ref="plate_number"
                    id="plate_number"
                    v-model="form.plate_number"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder="$t('plate_number')"
                  />
                  <InputError :message="form.errors.plate_number" class="mt-2" />
                </div>
              </div>
              <div class="grid gap-6 md:grid-cols-2">
                <div>
                  <Label :isRequired="true"
                    for_id="is_featured"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >{{ $t("is_featured") }}</Label
                  >
                  <select
                    v-model="form.is_featured"
                    id="is_featured"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                  >
                    <option value="0">{{ $t("no") }}</option>
                    <option value="1">{{ $t("yes") }}</option>
                  </select>
                  <InputError :message="form.errors.is_featured" class="mt-2" />
                </div>
  
                <div>
                  <Label :isRequired="true"
                    for_id="is_active"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >{{ $t("select_status") }}</Label
                  >
                  <select
                    v-model="form.is_active"
                    id="is_active"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                  >
                    <option value="" disabled>{{ $t("select_status") }}</option>
                    <option value="1">{{ $t("active") }}</option>
                    <option value="0">{{ $t("inactive") }}</option>
                  </select>
                  <InputError :message="form.errors.is_active" class="mt-2" />
                </div>
              </div>
              
            </div>


            <div class="mb-6">
              <Label for_id="description" class="block font-medium mb-1">{{ $t('description') }}</Label>
              <Textarea v-model="form.description" @change="onEditorChange"></Textarea>
              <InputError :message="form.errors.description" class="mt-2" />
            </div>
              <div class="mb-6">
              <fieldset class="border border-gray-300 dark:border-gray-400 p-4 rounded-lg">
                <legend class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('specifications') }}</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Category Selection -->
                  <div>
                    <Label :isRequired="true" for_id="category" class="block font-medium mb-1">{{ $t('category') }}</Label>
                    <select id="category" v-model="form.category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                      <option value="" disabled>{{ $t('select_a_category') }}</option>
                      <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                      </option>
                    </select>
                    <InputError :message="form.errors.category_id" class="mt-2" />
                  </div>
            
                  <!-- Brand Selection -->
                  <div>
                    <Label :isRequired="true" for_id="brand" class="block font-medium mb-1">{{ $t('brand') }}</Label>
                    <select id="brand" v-model="form.brand_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                      <option value="" disabled>{{ $t('select_a_brand') }}</option>
                      <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                        {{ brand.name }}
                      </option>
                    </select>
                    <InputError :message="form.errors.brand_id" class="mt-2" />
                  </div>

                  <div>
                    <Label :isRequired="true" for_id="model" class="block font-medium mb-1">{{ $t('model') }}</Label>
                    <select id="model" v-model="form.model_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                      <option value="" disabled>{{ $t('select_a_model') }}</option>
                      <option v-for="model in models" :key="model.id" :value="model.id">
                        {{ model.name }}
                      </option>
                    </select>
                    <InputError :message="form.errors.model_id" class="mt-2" />
                  </div>
            
                  <!-- Condition Selection -->
                  <div>
                    <Label :isRequired="true" for_id="condition" class="block font-medium mb-1">{{ $t('condition') }}</Label>
                    <select id="condition" v-model="form.condition_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                      <option value="" disabled>{{ $t('select_a_condition') }}</option>
                      <option v-for="condition in conditions" :key="condition.id" :value="condition.id">
                        {{ condition.name }}
                      </option>
                    </select>
                    <InputError :message="form.errors.condition_id" class="mt-2" />
                  </div>
            
                  <!-- Fuel Type Selection -->
                  <div>
                    <Label :isRequired="true" for_id="fuel_type" class="block font-medium mb-1">{{ $t('fuel_type') }} </Label>
                    <select id="fuel_type" v-model="form.fuel_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                      <option value="" disabled>{{ $t('select_a_fuel_type') }}</option>
                      <option v-for="fuel in fuel_types" :key="fuel.id" :value="fuel.id">
                        {{ fuel.name }}
                      </option>
                    </select>
                    <InputError :message="form.errors.fuel_type_id" class="mt-2" />
                  </div>
                </div>
              </fieldset>
            </div>

            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div >
                <Label  
                  for_id="featured_image"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("featured_image") }}</Label
                >
                <FileUpload v-model="form.featured_image" target_input="featured_image" />
                <InputError :message="form.errors.featured_image" class="mt-2" />
              </div>
              <div>
                <Label  
                  for_id="gallery_images"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("gallery_images") }}</Label
                >
                <FileUpload :multiple="true" target_input="gallery_images" v-model="form.gallery_images" />
                <InputError :message="form.errors.gallery_images" class="mt-2" />
              </div>
            </div>
            
            <div class="button-group">
              <div class="flex justify-start gap-5 items-center">
                <input
                  type="submit"
                  :value="$t('save')"
                  @click="form.is_save_and_more = false"
                  class="focus:outline-none text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-1 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-900"
                />
                <input
                  type="submit"
                  :value="$t('save_and_more')"
                  @click="form.is_save_and_more = true"
                  class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
                />
                <Link
                  :href="route('cars.index')"
                  class="focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900"
                >
                  {{ $t("cancel") }}
                </Link>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import Textarea  from "@/Components/Others/Textarea.vue";
import Label from "@/Components/Others/Label.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import { Head, useForm, Link, usePage } from "@inertiajs/vue3";
import { reactive, ref, watch } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import useHelper from "@/composables/useHelper";
const { generateSlug } = useHelper();
const page = usePage();
const breadcrumbs = reactive([
  { Label: "Home", url: route("dashboard") },
  { Label: t("cars"), url: "/admin/cars" },
  { Label: t("create"), url: null, is_active: true },
]);


const props = defineProps({
  categories: {
    type: Array,
    required: true,
  },
  brands: {
    type: Array,
    required: true,
  },
  models: {
    type: Array,
    required: true,
  },
  conditions: {
    type: Array,
    required: true,
  },
  fuel_types: {
    type: Array,
    required: true,
  }
})



const form = useForm({
  code: "",
  name: "",
  current_price: "",
  previous_price: "",
  year: "",
  mileage: "",
  featured_image: null,
  gallery_images: null,
  plate_number: "",
  is_featured: 0,
  is_active: 1,
  category_id: "",
  condition_id: "",
  brand_id: "",
  model_id: "",
  fuel_type_id: "",
  description: "",
  is_save_and_more: false,
});
watch(
  () => form.name,
  (newName) => {
    form.slug = generateSlug(newName);
  }
);



function onEditorChange(newValue) {
  console.log('Description changed:', newValue);
}

const handleSubmit = () => {
  form.post(route("cars.store"), {
    preserveScroll: true,
    onSuccess: (res) => {
      console.log({ res });
      form.reset("code", "name");
    },
    onError: () => {},
  });
};
</script>
