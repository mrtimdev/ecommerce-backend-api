<script setup>
import { ref } from 'vue';
import { useMainStore } from '@/stores/main';
import { onClickOutside } from '@vueuse/core'

const store = useMainStore();
const dropdownOpen = ref(false);
const target = ref(null)

onClickOutside(target, () => {
  dropdownOpen.value = false
})



</script>

<template>
  <div class="relative inline-block text-left text-black dark:text-white" ref="target">
    <!-- Dropdown Button -->
    <div>
      <button
        type="button"
        class="inline-flex justify-center items-center w-full rounded-md bg-stroke hover:bg-gray-200 dark:bg-meta-4 shadow-sm h-8.5 px-1 text-sm font-medium text-gray-700 dark:text-white focus:outline-none"
        @click.prevent="(dropdownOpen = !dropdownOpen)"
      >
        <img v-if="store.locale === 'en'" src=".././../../images/flags/en.png" class="mr-2 h-5 w-5" />
        <img v-else src=".././../../images/flags/kh.png" class="mr-2 h-5 w-5" />
        {{ store.locale === 'en' ? 'English' : 'Khmer' }} 
        <svg :class="{'transform rotate-180': dropdownOpen}" class="transition ease-in-out duration-300 -mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>

    <!-- Dropdown Menu -->
    <div
      v-if="dropdownOpen"
      class="absolute -right-0 mt-2 flex w-full flex-col rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark sm:right-0 sm:w-40"
    >
      <div>
        <button
          @click="store.setLanguage('en')"
          class="block px-4 py-2 text-sm text-gray-700 dark:text-white hover:bg-gray-100 w-full text-left dark:hover:bg-meta-4"
        >
          <img src=".././../../images/flags/en.png" class="inline h-4 w-4 mr-2" /> English
        </button>
        <button
          @click="store.setLanguage('kh')"
          class="border-t border-stroke dark:border-strokedark block px-4 py-2 text-sm text-gray-700 dark:text-white w-full text-left hover:bg-gray-2 dark:hover:bg-meta-4"
        >
          <img src=".././../../images/flags/kh.png" class="inline h-4 w-4 mr-2" /> Khmer
        </button>
      </div>
    </div>
  </div>
</template>

