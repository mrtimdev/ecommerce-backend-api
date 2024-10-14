<template>
  <DefaultLayout>
  <Head :title="$t('models')" />
  <div class="container">
    <div class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between">
      <Bc :crumbs="breadcrumbs" />
    </div>

    <div class="content-body p-5">
      <div class="relative">
          <form @submit.prevent=" handleSubmit ">
              <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                  <label for="code" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                    $t('code') }}</label>
                  <input type="text" ref="code" id="code" v-model=" form.code "
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder=" $t('model.code') " />
                  <InputError :message=" form.errors.code " class="mt-2" />
                </div>
                <div>
                  <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                    $t('name') }}</label>
                  <input type="text" ref="name" id="name" v-model=" form.name "
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                    :placeholder=" $t('model.name') " />
                  <InputError :message=" form.errors.name " class="mt-2" />
                </div>
              </div>
              <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                  <label for="brand" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                    $t('select_brand') }}</label>
                    <select v-model=" form.brand_id "
                    id="brand"
                    class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                    <option selected>{{ $t('select_brand') }}</option>
                    <template v-for="(brand, index) in brands" :key="index">
                      <option :value="brand.id">{{ brand.name }}</option>
                    </template>
                  </select>
                  <InputError :message=" form.errors.brand_id " class="mt-2" />
                </div>
                <div>
                  <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('select_status') }}</label>
                  <select v-model=" form.is_active "
                    id="status"
                    class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                    <option selected value=""> {{ $t('select_status') }}</option>
                    <option value="1">{{ $t('active') }}</option>
                    <option value="0">{{ $t('inactive') }}</option>
                  </select>
                  <InputError :message=" form.errors.is_active " class="mt-2" />
                </div>
              </div>
              <div class="modal-footer">
                <div class="flex justify-start gap-5 items-center">
                  <Link :href="route('models.index')"
                    class="focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900">{{
                    $t('cancel') }}</Link>
                    <input
                      type="submit"
                      :value="$t('update')"
                      class="focus:outline-none text-white bg-emerald-700 hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-900"
                    />
                  
                </div>
              </div>
            </form>
      </div>
    </div>  
  </div>
  </DefaultLayout>
</template>


<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const page = usePage()
const breadcrumbs = reactive([
  {  label: 'Home', url: route('dashboard') },
  {  label: t('models'), url: '/admin/models' }, 
  {  label: t('update'), url: null, is_active: true } 
])

const props = defineProps({ 
  model: { type: Object, required: true },
  brands: { type: Array, required: true },
});
const form = useForm({
    code: props.model.code,
    name: props.model.name,
    brand_id: props.model.brand_id,
    is_active: props.model.is_active
})
const handleSubmit = () => {
    form.post(route('models.update', props.model.id), {
      _token: page.props.csrf_token,
      preserveScroll: true,
      onSuccess: (res) => {
        form.reset('name')
      },
      onError: () => {
      }
    })
  
}
</script>
