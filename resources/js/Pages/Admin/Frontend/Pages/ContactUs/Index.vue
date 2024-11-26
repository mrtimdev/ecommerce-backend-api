<template>
  <LayoutTab>
    <Head :title="$t('frontend.contactus')"/>
    <div class="">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 px-0 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <div class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
          <h2 class="text-md font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white">{{ $t('contact-us') }}</h2>
        </div>
        <div class="flex items-center gap-2 justify-center">
          <button
            v-if="mainStore.selectedRows.length"
            @click="btnDeleteSelected"
            class="text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-purple-300"
          >
            <i class="fi fi-rr-trash w-4 h-4 me-2"></i>
            {{ $t("delete") }}
          </button>
        </div>
      </div>
      <div class="relative">
        <form @submit.prevent=" contactUsStore " enctype="multipart/form-data" class="mt-4">
          <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
            <div>
              <Label for_id="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('phone_number') }}</Label>
              <input type="text" id="phone_number" ref="phone_number" v-model=" form.phone_number "
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder=" $t('phone_number') " />
                <InputError :message="form.errors.phone_number" class="mt-2" />
            </div>
            <div>
              <Label for_id="telegram" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('telegram') }}</Label>
              <input type="text" id="telegram" ref="telegram" v-model=" form.telegram "
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder=" $t('telegram') " />
                <InputError :message="form.errors.telegram" class="mt-2" />
            </div>
            <div>
              <Label for_id="telegram_channel" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('telegram_channel') }}</Label>
              <input type="text" id="telegram_channel" ref="telegram_channel" v-model=" form.telegram_channel "
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder=" $t('telegram_channel') " />
                <InputError :message="form.errors.telegram_channel" class="mt-2" />
            </div>
            <div>
              <Label for_id="facebook_page" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('facebook_page') }}</Label>
              <input type="text" id="facebook_page" ref="facebook_page" v-model=" form.facebook_page "
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder=" $t('facebook_page') " />
                <InputError :message="form.errors.facebook_page" class="mt-2" />
            </div>
            <div>
              <Label for_id="youtube" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('youtube') }}</Label>
              <input type="text" id="youtube" ref="youtube" v-model=" form.youtube "
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder=" $t('youtube') " />
                <InputError :message="form.errors.youtube" class="mt-2" />
            </div>
            <div>
              <Label for_id="tiktok" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('tiktok') }}</Label>
              <input type="text" id="tiktok" ref="tiktok" v-model=" form.tiktok "
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                :placeholder=" $t('tiktok') " />
                <InputError :message="form.errors.tiktok" class="mt-2" />
            </div>
            
          </div>
          <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 mt-6">
              <div class="mb-6">
                <Label for_id="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $t('address') }}</Label>
                <Textarea rows="8" v-model="form.address"></Textarea>
                <InputError :message="form.errors.address" class="mt-2" />
              </div>
              <div>
                <Label for_id="image_path"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                    $t("image_path") }}</Label>
                <FileUpload v-model=" form.image_path " target_input="image_path"
                    :selectedFile=" contactus.image_path " />
                <InputError :message=" form.errors.image_path " class="mt-2" />
            </div>
          </div>

          <div class="button-group mt-6">
            <div class="flex justify-start gap-5 items-center">
              <input
                type="submit"
                :value="$t('save')"
                class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
              />
            </div>
          </div>
          
        </form>
      </div>
    </div>
    <AddOrEdit :show-modal="false"/>
  </LayoutTab>
</template>

<script setup>

import Textarea  from "@/Components/Others/Textarea.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import LayoutTab from '../../Layout/Index.vue';
import Label from '@/Components/Others/Label.vue';
import AddOrEdit from '../Videos/AddOrEdit.vue';
import { useI18n } from 'vue-i18n';

import { useMainStore } from "@/stores/main";
import { useFrontend } from "@/stores/frontend";

import { events } from "@/events"
import { useForm, Head } from '@inertiajs/vue3'

const mainStore = useMainStore();
const frontendStore = useFrontend();

const { t } = useI18n();

const props = defineProps({
  contactus: {
    type: Object,
    required: true
  }
})

const form = useForm({
  phone_number: props.contactus.phone_number,
  telegram: props.contactus.telegram,
  telegram_channel: props.contactus.telegram_channel,
  facebook_page: props.contactus.facebook_page,
  youtube: props.contactus.youtube,
  tiktok: props.contactus.tiktok,
  image_path: null,
  address: props.contactus.address ? props.contactus.address :  "",
});

const contactUsStore = () => {
  form.post(route('frontend.page.contactus.store'), {
    onSuccess: () => {
    },
    onError: () => {
    }
  })
}


</script>

<style lang="scss">
  .table tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, .05);
  }
  
  .dark  {
    .table tr th,
    .table tr td{
      border-color:  rgba(209, 199, 199, 0.05) !important;
      border: 1px solid;
    }
  }
  .table {
    tr {
      td, th
      {
        border-color: #dedada !important;
        border: 1px solid;
      }
    }
  }
  .checkbox-col,
  .action-col {
    text-align: center !important;
  }

  .dt-length
  {
    >select {
      width: 50px !important;
    }
  }
  .table {
    .text-center {
      text-align: center !important;
    }
  }
</style>
