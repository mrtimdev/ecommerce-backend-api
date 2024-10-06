<script setup>
import { ref } from 'vue';
import { onClickOutside } from '@vueuse/core'

const dropdownOpen = ref(false);
const target = ref(null)

onClickOutside(target, () => {
  dropdownOpen.value = false
})


defineProps({
  item: {
    type: Object,
    required: true
  },
})


</script>

<template>
  <div class="relative inline-block text-left text-black dark:text-white" ref="target">
    <!-- Dropdown Button -->
    <div>
      <button
        type="button"
        class="inline-flex justify-center items-center w-full rounded-md bg-purple-700 hover:bg-purple-800 shadow-sm px-4 py-2 text-sm font-medium text-white focus:outline-none"
        @click.prevent="(dropdownOpen = !dropdownOpen)"
      >
        {{ $t('actions') }} 
        <svg :class="{'transform rotate-180': dropdownOpen}" class="transition ease-in-out duration-300 -mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>
    </div>

    <!-- Dropdown Menu -->
    <div
      v-if="dropdownOpen"
      class="absolute z-10 -right-0 mt-2 flex w-full flex-col rounded-sm border border-gray-200 bg-white shadow-default dark:border-gray-300 sm:right-0 sm:w-40"
    >
      <div class="py-1">
        <slot name="action-buttons"></slot>
      </div>
    </div>
  </div>
</template>

