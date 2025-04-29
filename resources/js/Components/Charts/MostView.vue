<!-- resources/js/components/ChartComponent.vue -->
<template>
  <div class="chart-container">
    <Bar v-if="loaded" :data="data" :options="options" />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";
import { Bar } from "vue-chartjs";

const loaded = ref(false);

const data = ref({
  labels: [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ],
  datasets: [
    {
      label: "Orders Count",
      backgroundColor: "#7e22ce",
      data: Array(12).fill(0),
    },
    {
      label: "Total Price",
      backgroundColor: "#41b883",
      data: Array(12).fill(0),
    },
  ],
});

const options = {
  responsive: true,
  maintainAspectRatio: false,
};

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

onMounted(() => {
  axios
    .get(route("order.monthly.report"))
    .then((response) => {
      loaded.value = true;
      const report = response.data;
      // Loop through each month (1 through 12) and update the datasets.
      for (let month = 1; month <= 12; month++) {
        // Find a matching record in the report for the current month.
        const record = report.find((item) => item.month === month);
        console.log({ record });
        if (record) {
          data.value.datasets[0].data[month - 1] = record.orders_count;
          data.value.datasets[1].data[month - 1] = parseFloat(record.total_price);
        } else {
          data.value.datasets[0].data[month - 1] = 0;
          data.value.datasets[1].data[month - 1] = 0;
        }

        console.log({ data });
      }
    })
    .catch((error) => {
      console.error("Error fetching monthly report:", error);
    });
});
</script>
<style scoped lang="scss">
.chart-container {
  position: relative;
  height: 400px;
}
</style>
