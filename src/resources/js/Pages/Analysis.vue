<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { getToday } from '@/common';
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive, ref } from 'vue';
import Chart from '@/Components/Chart.vue';

const form = reactive({
    startDate: null,
    endDate: null,
    type: 'perDay',
})

const componentKey = ref(0)

const getData = async () => {
    try {
        await axios.get('/api/analysis', {
            params: {
                startDate: form.startDate,
                endDate: form.endDate,
                type: form.type
            }
        })
            .then(res => {
                data.data = res.data.data
                data.labels = res.data.labels
                data.totals = res.data.totals
                componentKey.value = !componentKey.value
            })
    } catch (e) {
        console.log(e.message);
    }
}


const data = reactive({
    data: null,
    labels: null,
    totals: null,
})

onMounted(() => {
    form.startDate = getToday()
    form.endDate = getToday()
})
</script>

<template>
    <Head title="データ分析" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">データ分析</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="getData">
                            <div class="p-2 w-full flex items-center">
                                From: <input type="date" v-model="form.startDate" class="mx-5">
                                To: <input type="date" v-model="form.endDate" class="mx-5">
                                <button
                                    class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析する
                                </button>
                            </div>
                        </form>

                        <div v-if="data.data">
                            <Chart :data="data" :key="componentKey"/>
                        </div>

                        <div v-show="data.data" class="lg:w-2/3 w-full mx-auto overflow-auto mt-5">
                            <table class="table-auto w-full text-left whitespace-no-wrap">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                                            年月日</th>
                                        <th
                                            class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                            金額
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in data.data" :key="item.date">
                                        <td class="border-b-2 border-gray-200 px-4 py-3">{{ item.date }}</td>
                                        <td class="border-b-2 border-gray-200 px-4 py-3">{{ item.total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
