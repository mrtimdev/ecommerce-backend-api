<script setup>
import { useSidebarStore } from "@/stores/sidebar";
import { onClickOutside } from "@vueuse/core";
import { ref, computed, onMounted } from "vue";
import SidebarItem from "./SidebarItem.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { events } from "@/events";
import { useMainStore } from "@/stores/main";
import LoadingIcon from "../Others/LoadingIcon.vue";

const mainStore = useMainStore();
const target = ref(null);
const sidebarStore = useSidebarStore();
const page = usePage();
const setting = ref(false);

onClickOutside(target, () => {
  sidebarStore.isSidebarOpen = false;
});

events.on("refresh-settings", () => {
  mainStore.loadSetting();
});
onMounted(() => {
  mainStore.loadSetting();
});

import { useHelpers } from "@/helpers/useHelpers";
const { isRole, isPermission } = useHelpers();

const menuGroups = ref([
  {
    name: "MENU",
    menuItems: [
      ...(isRole("owner")
        ? [
            {
              icon: `<i class="mt-[5px] fi fi-tr-dashboard-monitor"></i>`,
              label: "Dashboard",
              routeName: "dashboard",
            },
          ]
        : []),
      ...(isRole("owner") || isRole("admin") || isPermission(["item-specifications"])
        ? [
            {
              icon: `<i class="mt-[5px] fi fi-ts-workflow-setting-alt"></i>`,
              label: "Item Specifications",
              is_in_route: route().current("categories.*") ? true : false,
              children: [
                ...(isRole("owner") ||
                isRole("admin") ||
                isPermission(["item-categories"])
                  ? [
                      {
                        icon: `<i class="fa-sharp fa-circle-dot"></i>`,
                        label: "Categories",
                        routeName: "categories.index",
                      },
                    ]
                  : []),

                ...(isRole("owner") || isRole("admin") || isPermission(["brands"])
                  ? [
                      {
                        icon: `<i class="fa-sharp fa-circle-dot"></i>`,
                        label: "Brands",
                        routeName: "brands.index",
                      },
                    ]
                  : []),
                ...(isRole("owner") || isRole("admin") || isPermission(["models"])
                  ? [
                      {
                        icon: `<i class="fa-sharp fa-circle-dot"></i>`,
                        label: "Models",
                        routeName: "models.index",
                      },
                    ]
                  : []),
                ...(isRole("owner") || isRole("admin") || isPermission(["colors"])
                  ? [
                      {
                        icon: `<i class="fa-sharp fa-circle-dot"></i>`,
                        label: "Colors",
                        routeName: "colors.index",
                      },
                    ]
                  : []),
              ],
            },
          ]
        : []),

      ...(isRole("owner")
        ? [
            {
              icon: `<i class="mt-[5px] fi fi-rr-user-gear"></i>`,
              label: "Products",
              routeName: "products.index",
              is_in_route: route().current("products.*") ? true : false,
            },
            {
              icon: `<i class="mt-[5px] fi fi-rr-user-gear"></i>`,
              label: "Stocks",
              routeName: "stocks.index",
              is_in_route: route().current("stocks.*") ? true : false,
            },
            {
              icon: `<i class="mt-[5px] fi fi-rr-user-gear"></i>`,
              label: "Users",
              routeName: "users.index",
              is_in_route: route().current("users.*") ? true : false,
            },
            {
              icon: `<i class="mt-[5px] fi fi-rr-users-alt"></i>`,
              label: "Clients",
              routeName: "clients.index",
              is_in_route: route().current("clients.*") ? true : false,
            },
            {
              icon: `<i class="mt-[5px] fi fi-rr-users"></i>`,
              label: "Drivers",
              routeName: "drivers.index",
              is_in_route: route().current("drivers.*") ? true : false,
            },
            {
              icon: `<i class="mt-[5px] fi fi-tr-plan"></i>`,
              label: "Permissions",
              routeName: "permissions.index",
              is_in_route: route().current("permissions.*") ? true : false,
            },
            {
              icon: `<i class="mt-[5px] fi fi-ts-plan-strategy"></i>`,
              label: "Roles",
              routeName: "roles.index",
              is_in_route: route().current("roles.*") ? true : false,
            },
          ]
        : []),

      ...(isRole("owner") || isRole("admin") || isPermission(["settings"])
        ? [
            {
              icon: `<i class="mt-[5px] fi fi-tr-customize"></i>`,
              label: "Settings",
              routeName: "settings.index",
              is_in_route: route().current("settings.*") ? true : false,
            },
          ]
        : []),
    ],
  },
]);

console.log({ menuGroups });
</script>

<template>
  <aside
    class="absolute left-0 top-0 z-40 flex h-screen w-72.5 flex-col overflow-y-hidden duration-300 ease-linear dark:bg-boxdark-1 bg-[#ffffff] lg:static lg:translate-x-0"
    :class="{
      'translate-x-0': sidebarStore.isSidebarOpen,
      '-translate-x-full': !sidebarStore.isSidebarOpen,
    }"
    ref="target"
  >
    <!-- SIDEBAR HEADER -->
    <div class="py-1 shadow-2">
      <div
        class="flex flex-grow items-center justify-between py-[3px] px-4 md:px-6 2xl:px-11"
      >
        <a
          :href="
            mainStore.setting.site_link
              ? mainStore.setting.site_link
              : 'https://ftdevs.net'
          "
          target="_blank"
          class="flex gap-2 items-center justify-between"
        >
          <LoadingIcon v-if="mainStore.setting.site_logo === undefined" />
          <img
            v-if="mainStore.setting && mainStore.setting.site_logo"
            :src="'/storage/' + mainStore.setting.site_logo"
            alt="Site Logo"
            class="w-[50px] h-[50px] object-cover rounded-full border border-purple-300"
          />
          <span>{{ mainStore.setting.site_name }}</span>
        </a>

        <button class="block lg:hidden" @click="sidebarStore.isSidebarOpen = false">
          <svg
            class="fill-current"
            width="20"
            height="18"
            viewBox="0 0 20 18"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z"
              fill=""
            />
          </svg>
        </button>
      </div>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
      <!-- Sidebar Menu -->
      <nav class="mt-5 py-4 px-4 lg:mt-9 lg:px-6">
        <div v-for="(menuGroup, index) in menuGroups" :key="index">
          <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">
            {{ menuGroup.name }}
          </h3>
          <ul class="mb-6 flex flex-col gap-1.5">
            <SidebarItem
              v-for="(menuItem, index) in menuGroup.menuItems"
              :item="menuItem"
              :key="index"
              :index="index"
              :currentRoute="page.url"
            />
          </ul>
        </div>
      </nav>
      <!-- Sidebar Menu -->
    </div>
  </aside>
</template>
