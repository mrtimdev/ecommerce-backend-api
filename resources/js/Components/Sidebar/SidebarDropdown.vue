<script setup>
import { useSidebarStore } from "@/stores/sidebar";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const sidebarStore = useSidebarStore();

const props = defineProps(["items", "page"]);
const items = ref(props.items);

const handleItemClick = (index) => {
  const pageName =
    sidebarStore.selected === props.items[index].label ? "" : props.items[index].label;
  sidebarStore.selected = pageName;
};
</script>

<template>
  <ul class="mt-4 mb-5.5 flex flex-col gap-2.5 pl-6">
    <li v-for="(childItem, index) in items" :key="index">
      <Link
        :href="route(childItem.routeName)"
        @click="handleItemClick(index)"
        class="group relative flex items-center gap-2.5 rounded-md px-4 font-normal duration-300 ease-in-out text-gray-500 hover:text-purple-700 hover:font-extrabold dark:text-gray-400 hover:dark:text-gray-200"
        :class="{
          '!text-purple-600 font-extrabold': childItem.label === sidebarStore.selected,
          '!bg-purple-600': childItem.is_in_route === true,
        }"
      >
        <span v-html="childItem.icon"></span>
        {{ childItem.label }}
      </Link>
    </li>
  </ul>
</template>
