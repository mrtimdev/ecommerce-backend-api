<template>
    <DefaultLayout>
    <Head :title="$t('fuel_types')" />
    <div class="container">
      <div class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between">
        <Bc :crumbs="breadcrumbs" />
      </div>

      <div class="content-body p-5">
        <div class="relative">
            <form @submit.prevent=" handleSubmit ">
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                  <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">{{
                      $t('name') }}</label>
                    <input type="text" ref="name" id="name" v-model=" form.name "
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder=" $t('fueltype.name') " />
                    <InputError :message=" form.errors.name " class="mt-2" />
                  </div>
                  <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('select_status') }}</label>
                    <select v-model=" form.is_active "
                      id="status"
                      class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500">
                      <option selected>{{ $t('select_status') }}</option>
                      <option value="1">{{ $t('active') }}</option>
                      <option value="0">{{ $t('inactive') }}</option>
                    </select>
                    <InputError :message=" form.errors.is_active " class="mt-2" />
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="flex justify-start gap-5 items-center">
                    <Link :href="route('fuelTypes.index')"
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
  import { reactive } from 'vue'
  import { useI18n } from 'vue-i18n';
  const { t } = useI18n();
  const page = usePage()
  const breadcrumbs = reactive([
    {  label: 'Home', url: route('dashboard') },
    {  label: t('fuel_types'), url: '/admin/fuelTypes' }, 
    {  label: t('update'), url: null, is_active: true } 
  ])

const props = defineProps({ 
  fuelType: { type: Object, required: true },
});
const form = useForm({
  name: props.fuelType.name,
  is_active: props.fuelType.is_active
})
const handleSubmit = () => {
  form.post(route("fuelTypes.update", props.fuelType.id), {
    _token: page.props.csrf_token,
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
    onError: () => {},
  });
}
</script>
