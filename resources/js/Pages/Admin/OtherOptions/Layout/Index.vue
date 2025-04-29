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
            <ul
              class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400"
            >
              <li
                class="me-2"
                v-if="
                  isRole('owner') || isRole('admin') || isPermission(['cars-hot-marks'])
                "
              >
                <Link
                  :href="route('hotmarks.index')"
                  :class="{
                    'text-purple-600 border-purple-600 dark:text-purple-500 dark:border-purple-500': route().current(
                      'hotmarks.index'
                    ),
                  }"
                  class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg active group"
                  aria-current="page"
                >
                  <i
                    :class="{
                      'text-purple-600 dark:text-purple-500': route().current(
                        'hotmarks.index'
                      ),
                    }"
                    class="fi fi-rr-rectangle-list w-4 h-4 me-2 text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300"
                  ></i>
                  {{ $t("hot_marks") }}
                </Link>
              </li>
              <li
                class="me-2"
                v-if="
                  isRole('owner') || isRole('admin') || isPermission(['cars-options'])
                "
              >
                <Link
                  :href="route('options.index')"
                  :class="{
                    'text-purple-600 border-purple-600 dark:text-purple-500 dark:border-purple-500': route().current(
                      'options.index'
                    ),
                  }"
                  class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg active group"
                  aria-current="page"
                >
                  <svg
                    :class="{
                      'text-purple-600 dark:text-purple-500': route().current(
                        'options.index'
                      ),
                    }"
                    class="w-4 h-4 me-2"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 18 18"
                  >
                    <path
                      d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"
                    />
                  </svg>
                  {{ $t("options") }}
                </Link>
              </li>
              <li
                class="me-2"
                v-if="
                  isRole('owner') || isRole('admin') || isPermission(['cars-options'])
                "
              >
                <Link
                  :href="route('optionGroups.index')"
                  :class="{
                    'text-purple-600 border-purple-600 dark:text-purple-500 dark:border-purple-500': route().current(
                      'optionGroups.index'
                    ),
                  }"
                  class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg active group"
                  aria-current="page"
                >
                  <svg
                    :class="{
                      'text-purple-600 dark:text-purple-500': route().current(
                        'optionGroups.index'
                      ),
                    }"
                    class="w-4 h-4 me-2"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 18 18"
                  >
                    <path
                      d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"
                    />
                  </svg>
                  {{ $t("option_groups") }}
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
import { usePage, Link, router } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useHelpers } from "@/helpers/useHelpers";
const { isRole, isPermission } = useHelpers();

const breadcrumbs = reactive([
  { label: t("home"), url: route("dashboard") },
  { label: t("car"), url: null },
  { label: t("other_options"), url: null },
]);
</script>
