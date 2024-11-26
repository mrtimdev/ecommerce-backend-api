<template>
  <div>
    <label
      for="country-select"
      class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
      >Select Country:</label
    >
    <select
      id="country-select"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
      v-model="model"
    >
      <option :value="null" disabled>Select a country</option>
      <option v-for="country in countries" :key="country.code" :value="country">
        {{ country.name }} ({{ country.dial_code }})
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";

import countryPath from "@/country/countries.json";

const model = defineModel({
  type: Object,
  default: null,
  required: false,
});

const countries = ref([]);
onMounted(() => {
  countries.value = countryPath;
});
</script>

<style scoped>
select {
  margin-top: 10px;
  padding: 8px;
  font-size: 16px;
}
</style>
