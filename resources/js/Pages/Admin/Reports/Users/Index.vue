<template>
  <DefaultLayout>
    <Head :title="$t('orders_report')" />
    <div class="container">
      <!-- Breadcrumbs -->
      <div
        class="content-header rounded-tl-md rounded-tr-md p-5 border-b bg-white dark:border-gray-700 dark:bg-boxdark-1 flex flex-grow items-center justify-between"
      >
        <Bc :crumbs="breadcrumbs" />
      </div>

      <!-- Content Body -->
      <div class="content-body p-5">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-6">
          <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                {{ $t("Year") }}
              </label>
              <select
                v-model="filters.year"
                @change="updateFilters"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              >
                <option value="">All Years</option>
                <option v-for="year in availableYears" :key="year" :value="year">
                  {{ year }}
                </option>
              </select>
            </div>

            <div class="flex-1 min-w-[200px]">
              <label
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
              >
                {{ $t("Month") }}
              </label>
              <select
                v-model="filters.month"
                @change="updateFilters"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
              >
                <option value="">All Months</option>
                <option v-for="(month, index) in months" :key="index" :value="index + 1">
                  {{ month }}
                </option>
              </select>
            </div>

            <div class="flex items-end">
              <button
                @click="resetFilters"
                class="mt-[22px] px-4 py-2 bg-rose-200 hover:bg-rose-300 rounded-md text-gray-700 dark:bg-rose-600 dark:hover:bg-rose-500 dark:text-white transition"
              >
                {{ $t("Reset") }}
              </button>
            </div>
          </div>
        </div>

        <!-- Chart -->
        <!-- <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-8">
          <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">
            {{ $t("Cars Created Per Month") }}
          </h2>
          <div class="relative h-80">
            <canvas id="carChart"></canvas>
          </div>
        </div> -->

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-8">
          <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">
            {{ $t("Top Users by Cars Created") }}
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
              ({{ filters.year || "All time"
              }}{{ filters.month ? " - " + months[filters.month - 1] : "" }})
            </span>
          </h2>
          <div class="relative h-96">
            <!-- Increased height for better visibility -->
            <canvas id="userCarChart"></canvas>
          </div>
        </div>

        <!-- User Table -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
              {{ $t("User Car Report") }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
              {{ $t("Showing") }} {{ users.length }} {{ $t("users") }}
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
              <thead>
                <tr class="bg-gray-100 text-left dark:bg-gray-700">
                  <th class="p-3 border dark:border-gray-600 text-center">#</th>
                  <th class="p-3 border dark:border-gray-600">{{ $t("User Name") }}</th>
                  <th class="p-3 border dark:border-gray-600">{{ $t("Email") }}</th>
                  <th class="p-3 border dark:border-gray-600">
                    {{ $t("Number of Cars Created") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(user, index) in users"
                  :key="user.id"
                  class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                >
                  <td class="p-3 border dark:border-gray-600 text-center">
                    {{ index + 1 }}
                  </td>
                  <td class="p-3 border dark:border-gray-600">{{ user.name }}</td>
                  <td class="p-3 border dark:border-gray-600">{{ user.email }}</td>
                  <td class="p-3 border dark:border-gray-600 text-center">
                    <span
                      class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs dark:bg-blue-900 dark:text-blue-200"
                    >
                      {{ user.cars_count }}
                    </span>
                  </td>
                </tr>
                <tr v-if="users.length === 0">
                  <td
                    colspan="4"
                    class="p-4 text-center text-gray-500 dark:text-gray-400"
                  >
                    {{ $t("No users found") }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </DefaultLayout>
</template>

<script setup>
import { Head, router } from "@inertiajs/vue3";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { onMounted, reactive, watch, ref } from "vue";
import { useI18n } from "vue-i18n";
import Chart from "chart.js/auto";
import { debounce } from "lodash";

const { t } = useI18n();

// Breadcrumbs
const breadcrumbs = reactive([
  { label: "Home", url: route("dashboard") },
  { label: t("reports"), url: null },
  { label: t("User Car Report"), url: null, is_active: true },
]);

// Props
const props = defineProps({
  users: Array,
  chartData: Object,
  filters: Object,
  availableYears: Array,
});

// Local filters
const filters = reactive({
  year: props.filters.year || "",
  month: props.filters.month || "",
});

// Months for dropdown
const months = ref([
  t("January"),
  t("February"),
  t("March"),
  t("April"),
  t("May"),
  t("June"),
  t("July"),
  t("August"),
  t("September"),
  t("October"),
  t("November"),
  t("December"),
]);

// Chart reference
let chart = null;

// Update filters with debounce
const updateFilters = debounce(() => {
  router.get(route("reports.users.cars"), filters, {
    preserveState: true,
    replace: true,
  });
}, 300);

// Reset filters
const resetFilters = () => {
  filters.year = "";
  filters.month = "";
  updateFilters();
};

// Initialize chart
onMounted(() => {
  renderUserChart();
});

// Watch for chart data changes
watch(
  () => props.chartData,
  () => {
    if (chart) {
      chart.destroy();
    }
    renderUserChart();
  },
  { deep: true }
);

// Render chart function
// Render chart function for user data
const renderUserChart = () => {
  const ctx = document.getElementById("userCarChart");

  // Generate a nice gradient
  const gradient = ctx.getContext("2d").createLinearGradient(0, 0, 0, 400);
  gradient.addColorStop(0, "rgba(79, 70, 229, 0.8)");
  gradient.addColorStop(1, "rgba(99, 102, 241, 0.2)");

  chart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: props.chartData.labels,
      datasets: [
        {
          label: t("Cars Created"),
          data: props.chartData.data,
          backgroundColor: gradient,
          borderColor: "rgba(79, 70, 229, 1)",
          borderWidth: 1,
          borderRadius: 6,
          hoverBackgroundColor: "rgba(79, 70, 229, 0.9)",
        },
      ],
    },
    options: {
      indexAxis: "y", // Horizontal bars
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              return `${context.raw} ${context.raw === 1 ? "car" : "cars"}`;
            },
          },
        },
      },
      scales: {
        x: {
          beginAtZero: true,
          grid: {
            color: "rgba(0, 0, 0, 0.05)",
            drawBorder: false,
          },
          ticks: {
            stepSize: 1,
            precision: 0,
          },
        },
        y: {
          grid: {
            display: false,
          },
        },
      },
    },
  });
};
</script>

<style scoped>
.content-body {
  background-color: #f9fafb;
  min-height: calc(100vh - 80px);
}

.dark .content-body {
  background-color: #1a202c;
}

table {
  border-radius: 8px;
  overflow: hidden;
}

th {
  font-weight: 600;
  color: #374151;
}

.dark th {
  color: #f3f4f6;
}

td {
  color: #4b5563;
}

.dark td {
  color: #d1d5db;
}
</style>
