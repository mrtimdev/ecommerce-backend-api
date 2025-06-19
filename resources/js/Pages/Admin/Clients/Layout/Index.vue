<template>
  <DefaultLayout>
    <div class="container">
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <Bc :crumbs="breadcrumbs" />
      </div>

      <div class="content-body p-5">
        <div class="relative">
          <div class="border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
              <li class="me-2">
                <Link
                  :href="route('clients.index')"
                  :class="{
                    'text-purple-600 border-purple-600 dark:text-purple-500 dark:border-purple-500':
                      route().current('clients.index') ||
                      route().current('clients.create') ||
                      route().current('clients.edit'),
                  }"
                  class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg active group group-hover:text-gray-500 dark:group-hover:text-gray-300"
                  aria-current="page"
                >
                  <i
                    :class="{
                      'text-purple-600 dark:text-purple-500':
                        route().current('clients.index') ||
                        route().current('clients.create') ||
                        route().current('clients.edit'),
                    }"
                    class="mt-[5px] fi fi-rr-users-alt me-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                  ></i>
                  {{ $t("clients") }}
                </Link>
              </li>
              <li class="me-2">
                <Link
                  href="#"
                  :class="{
                    'text-purple-600 border-purple-600 dark:text-purple-500 dark:border-purple-500':
                      route().current('orders.index') ||
                      route().current('orders.user.index'),
                  }"
                  class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg active group group-hover:text-gray-500 dark:group-hover:text-gray-300"
                  aria-current="page"
                >
                  <i
                    :class="{
                      'text-purple-600 dark:text-purple-500':
                        route().current('orders.index') ||
                        route().current('orders.user.index'),
                    }"
                    class="mt-[5px] fi fi-rr-shopping-cart me-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                  ></i>
                  {{ $t("reports") }}
                </Link>
              </li>
            </ul>
          </div>

          <div class="tab-content">
            <slot></slot>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { ref, reactive } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
const { t } = useI18n();

const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("admin"), url: null },
  { label: t("clients"), url: null },
  { label: t("reports"), url: null },
]);

const url = usePage().props;
</script>
