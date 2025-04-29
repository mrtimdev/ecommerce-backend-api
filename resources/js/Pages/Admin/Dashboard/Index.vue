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
          <div class="p-4 md:p-6 2xl:p-10 bg-gray-200 dark:bg-[#1a2035] min-h-screen">
            <h1 class="text-2xl font-bold mb-6">Welcome back, {{ user.name }}!</h1>
            <div>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <Link :href="route('cars.index')">
                  <div
                    class="bg-green-500 text-white rounded-lg p-4 shadow-md flex items-center justify-between"
                  >
                    <div>
                      <div class="text-3xl font-semibold">{{ total_car }}</div>
                      <div class="mt-1">{{ $t("cars") }}</div>
                    </div>
                    <i class="fas fa-car text-4xl"></i>
                  </div>
                </Link>
                <Link :href="route('orders.index')" v-if="isRole('owner')">
                  <div
                    class="bg-yellow-500 text-white rounded-lg p-4 shadow-md flex items-center justify-between"
                  >
                    <div>
                      <div class="text-3xl font-semibold">{{ total_order }}</div>
                      <div class="mt-1">{{ $t("orders") }}</div>
                    </div>
                    <i class="fas fa-shopping-cart text-4xl"></i>
                  </div>
                </Link>
                <Link :href="route('users.index')">
                  <div
                    class="bg-pink-400 text-white rounded-lg p-4 shadow-md flex items-center justify-between"
                  >
                    <div>
                      <div class="text-3xl font-semibold">{{ total_user }}</div>
                      <div class="mt-1">{{ $t("users") }}</div>
                    </div>
                    <i class="fas fa-user text-4xl"></i>
                  </div>
                </Link>
                <Link :href="route('clients.index')">
                  <div
                    class="bg-gray-800 text-white rounded-lg p-4 shadow-md flex items-center justify-between"
                  >
                    <div>
                      <div class="text-3xl font-semibold">{{ total_register }}</div>
                      <div class="mt-1">{{ $t("clients_registered") }}</div>
                    </div>
                    <i class="fas fa-bell text-4xl"></i>
                  </div>
                </Link>
              </div>
            </div>

            <div v-if="isRole('owner')">
              <div class="grid grid-cols-1 md:grid-cols-1 gap-6 py-4">
                <div>
                  <h1>{{ $t("monthly_orders_chart") }}</h1>
                  <MostView />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import MostView from "@/Components/Charts/MostView.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { reactive, onMounted, getCurrentInstance } from "vue";
import { useI18n } from "vue-i18n";
import { Link, router, usePage } from "@inertiajs/vue3";

const { t } = useI18n();
const breadcrumbs = reactive([
  { label: t("dashboard"), url: route("dashboard") },
  { label: t("reporting"), url: null },
]);

const { proxy } = getCurrentInstance();
import { useHelpers } from "@/helpers/useHelpers";
const { isRole, isPermission } = useHelpers();

const props = defineProps({
  is_access_denied: Boolean,
  message: String,
  total_car: Number,
  total_order: Number,
  total_user: Number,
  total_register: Number,
});

const user = usePage().props.auth.user;

onMounted(() => {
  if (props.is_access_denied) {
    const Toast = proxy.$swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = proxy.$swal.stopTimer;
        toast.onmouseleave = proxy.$swal.resumeTimer;
      },
    });
    Toast.fire({
      icon: "warning",
      html: `${props.message}`,
    });

    router.get(route("dashboard"));
  }
});
</script>
