<script setup>
import { useSidebarStore } from '@/stores/sidebar'
import { useRoute } from 'vue-router'
import SidebarDropdown from './SidebarDropdown.vue'
import { Link } from '@inertiajs/vue3';
const sidebarStore = useSidebarStore()

const props = defineProps(['item', 'currentRoute', 'index'])

const handleItemClick = () => {
    const pageName = sidebarStore.page === props.item.label ? '' : props.item.label
    sidebarStore.page = pageName
    if (props.item.children) {
        return props.item.children.some((child) => sidebarStore.selected === child.label)
    }
}
</script>

<template>
  <li>
    <a
        v-if="item.children"
        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-gray-500 hover:text-gray-200 dark:text-gray-400 hover:dark:text-gray-200 hover:bg-gray-600 dark:hover:bg-gray-600"
        @click.prevent="handleItemClick"
        :class="{ 'bg-gray-600 bg-active': sidebarStore.page === item.label }"
        :aria-expanded="sidebarStore.page === item.label"
    >
        <span class="menu-icon  flex items-center" v-html="item.icon"></span>
        <span class="menu-label">{{ item.label }}</span>
        <svg
            class="menu-icon absolute right-4 top-1/2 -translate-y-1/2 fill-current"
            :class="{ 'rotate-180': sidebarStore.page === item.label }"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M4.41107 6.9107C4.73651 6.58527 5.26414 6.58527 5.58958 6.9107L10.0003 11.3214L14.4111 6.91071C14.7365 6.58527 15.2641 6.58527 15.5896 6.91071C15.915 7.23614 15.915 7.76378 15.5896 8.08922L10.5896 13.0892C10.2641 13.4147 9.73651 13.4147 9.41107 13.0892L4.41107 8.08922C4.08563 7.76378 4.08563 7.23614 4.41107 6.9107Z"
                fill="currentColor"
            />
        </svg>
    </a>
    <Link
        v-else
        :href="route(item.routeName)"
        class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-gray-500 hover:text-gray-200 dark:text-gray-400 hover:dark:text-gray-200 hover:bg-gray-600 dark:hover:bg-gray-600"
        @click.prevent="handleItemClick"
        :class="{ 'bg-gray-600 bg-active': sidebarStore.page === item.label }"
    >
        <span class="menu-icon flex items-center" v-html="item.icon"></span>
        <span class="menu-label">{{ item.label }}</span>
    </Link>

    <div class="translate transform overflow-hidden" v-show="sidebarStore.page === item.label">
      <SidebarDropdown v-if="item.children" :items="item.children" :page="item.label" />
    </div>
  </li>
</template>

<style lang="scss" scoped>
.bg-active {
    .menu-label,
    .menu-icon {
        color: rgb(156 163 175) !important;
    }
}
</style>
