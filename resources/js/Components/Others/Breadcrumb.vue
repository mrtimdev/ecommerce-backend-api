<template>
  <nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
      <li class="inline-flex items-center">
        <Link
          :href="route('dashboard')"
          class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white"
        >
          <i class="fi fi-rr-home w-3 h-3 text-gray-400 mx-1 mb-1"></i>
        </Link>
      </li>
      <li v-for="(crumb, index) in crumbs" :key="index">
        <div class="flex items-center">
          <i
            class="fi fi-rr-angle-right rtl:rotate-180 w-3 h-3 text-gray-400 mx-1 mb-1"
          ></i>
          <template v-if="crumb.url && index !== crumbs.length - 1">
            <Link
              :href="crumb.url"
              class="ms-1 text-sm font-medium text-gray-700 hover:text-purple-600 md:ms-2 dark:text-gray-400 dark:hover:text-white"
              >{{ crumb.label }}</Link
            >
          </template>
          <template v-else>
            <span
              :class="{'!text-purple-600 md:ms-2': crumb.is_active}"
              class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400"
              >{{ crumb.label }}</span
            >
          </template>
        </div>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  crumbs: {
    type: Array,
    required: true,
  },
});
</script>

<style scoped>
.breadcrumb-item:last-child::after {
  content: "";
}
</style>
