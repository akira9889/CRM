<script setup>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

import { reactive, computed, onMounted, watch, ref } from 'vue';

const props = defineProps({
  data: Object
})

const labels = computed(() => props.data.labels)
const totals = computed(() => props.data.totals)

const barData = reactive({
  labels: labels,
  datasets: [
    {
      label: '売上',
      data: totals,
      backgroundColor: "rgb(75, 192, 192)",
      tension: 0.1
    },
  ]
})

watch(barData, (newValue, oldValue) => {
  console.log('barData changed:', newValue);
});
</script>

<template>
  <div>
    <Bar :data="barData" :key="componentKey"/>
  </div>
</template>
<style scoped>
</style>
