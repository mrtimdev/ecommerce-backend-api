<template>
    <div class="h-screen bg-purple-100 flex items-center justify-center">
        <Head title="Not Found"/>
      <div class="text-center">
        <!-- Stop sign-like logo -->
        <div class="flex justify-center mb-4">
          <div class="w-[250px] h-[250px] rounded-full flex items-center justify-center">
            <img src="/assets/images/error/404.png" class="w-full h-full object-cover rounded-lg"/>
          </div>
        </div>
  
        <h1 class="text-5xl font-bold text-purple-700 mb-2">404 Not Found</h1>
        <p class="text-lg text-gray-600 mb-6">
          We're sorry. Something went wrong.
        </p>
  
        <div class="flex justify-center items-center gap-2 mb-8">
          <span class="bg-gray-100 px-4 py-2 rounded-md text-sm">
            <code>971a4ce1-5e78-4665-805d-77d9a44cd78c</code>
          </span>
        </div>
  
        <p class="text-sm text-gray-500 mb-4">
          Please try again on the previous page, or email the error code to 
          <a href="mailto:ngettim14@gmail.com" class="text-purple-600 underline">
            ngettim14@gmail.com
          </a>
          if the same error repeats.
        </p>
  
        <button 
          @click="goBack"
          class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-md">
          Go back to previous page
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Head, router } from '@inertiajs/vue3';
    const goBack = () => {
        router.visit(route('dashboard'));
    }
  </script>

  