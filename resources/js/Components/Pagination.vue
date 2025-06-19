<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
  links: {
    type: Array,
    required: true,
  },
  meta: {
    type: Object,
    default: () => ({}),
  },
});
</script>

<template>
  <div v-if="links.length > 3" class="flex items-center justify-between mt-6">
    <!-- Mobile view -->
    <div class="flex-1 flex justify-between sm:hidden">
      <Link
        v-if="links[0].url"
        :href="links[0].url"
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        preserve-scroll
      >
        &laquo; Previous
      </Link>
      <Link
        v-if="links[links.length - 1].url"
        :href="links[links.length - 1].url"
        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        preserve-scroll
      >
        Next &raquo;
      </Link>
    </div>

    <!-- Desktop view -->
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div v-if="meta">
        <p class="text-sm text-gray-700">
          <span v-if="meta.from && meta.to && meta.total">
            Showing <span class="font-medium">{{ meta.from }}</span> to
            <span class="font-medium">{{ meta.to }}</span> of
            <span class="font-medium">{{ meta.total }}</span> results
          </span>
          <span v-else-if="meta.total">
            Total <span class="font-medium">{{ meta.total }}</span> items
          </span>
        </p>
      </div>
      <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
          <template v-for="(link, index) in links">
            <Link
              v-if="index === 0 || index === links.length - 1 || link.url"
              :key="index"
              :href="link.url || '#'"
              :class="{
                'bg-indigo-50 border-indigo-500 text-indigo-600 z-10': link.active,
                'bg-white border-gray-300 text-gray-500 hover:bg-gray-50':
                  !link.active && link.url,
                'bg-white border-gray-300 text-gray-300 cursor-not-allowed': !link.url,
                'rounded-l-md': index === 0,
                'rounded-r-md': index === links.length - 1,
              }"
              class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
              v-html="link.label"
              preserve-scroll
            />
          </template>
        </nav>
      </div>
    </div>
  </div>
</template>
