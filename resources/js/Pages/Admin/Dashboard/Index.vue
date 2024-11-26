<template>
  <DefaultLayout>
    <div class="container">
      <div class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between">
        <Bc :crumbs="breadcrumbs" />
      </div>
      <div class="content-body p-5">
        <div class="relative">
          <div class="p-4 md:p-6 2xl:p-10 bg-gray-200 dark:bg-[#1a2035] min-h-screen">
            <h1 class="text-2xl font-bold mb-6">Welcome back, {{ user.name }}!</h1>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
              <div class="bg-blue-500 text-white rounded-lg p-4 shadow-md flex items-center justify-between">
                <div>
                  <div class="text-3xl font-semibold">31</div>
                  <div class="mt-1">Payment Logs</div>
                </div>
                <i class="fas fa-dollar-sign text-4xl"></i>
              </div>
          
              <div class="bg-green-500 text-white rounded-lg p-4 shadow-md flex items-center justify-between">
                <div>
                  <div class="text-3xl font-semibold">13</div>
                  <div class="mt-1">Cars</div>
                </div>
                <i class="fas fa-car text-4xl"></i>
              </div>
          
              <div class="bg-blue-600 text-white rounded-lg p-4 shadow-md flex items-center justify-between">
                <div>
                  <div class="text-3xl font-semibold">13</div>
                  <div class="mt-1">Products</div>
                </div>
                <i class="fas fa-box text-4xl"></i>
              </div>
          
              <div class="bg-yellow-500 text-white rounded-lg p-4 shadow-md flex items-center justify-between">
                <div>
                  <div class="text-3xl font-semibold">7</div>
                  <div class="mt-1">Orders</div>
                </div>
                <i class="fas fa-shopping-cart text-4xl"></i>
              </div>
          
              <div class="bg-blue-400 text-white rounded-lg p-4 shadow-md flex items-center justify-between">
                <div>
                  <div class="text-3xl font-semibold">6</div>
                  <div class="mt-1">Blog</div>
                </div>
                <i class="fas fa-blog text-4xl"></i>
              </div>
          
              <div class="bg-purple-500 text-white rounded-lg p-4 shadow-md flex items-center justify-between">
                <div>
                  <div class="text-3xl font-semibold">8</div>
                  <div class="mt-1">Vendors</div>
                </div>
                <i class="fas fa-users text-4xl"></i>
              </div>
          
              <div class="bg-pink-400 text-white rounded-lg p-4 shadow-md flex items-center justify-between">
                <div>
                  <div class="text-3xl font-semibold">38</div>
                  <div class="mt-1">Users</div>
                </div>
                <i class="fas fa-user text-4xl"></i>
              </div>
          
              <div class="bg-gray-800 text-white rounded-lg p-4 shadow-md flex items-center justify-between">
                <div>
                  <div class="text-3xl font-semibold">5</div>
                  <div class="mt-1">Subscribers</div>
                </div>
                <i class="fas fa-bell text-4xl"></i>
              </div>
            </div>
          </div>
          
        </div>
      </div>  
    </div>
  </DefaultLayout>
</template>

<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { reactive } from 'vue'
import { useI18n } from 'vue-i18n';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const { t } = useI18n();
const breadcrumbs = reactive([
  {   label: t('dashboard'), url: route('dashboard') },
  {   label: t('reporting'), url: null },
])

const user = usePage().props.auth.user;

</script>
