<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { getToday } from '@/common';
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive, ref } from 'vue';
import Chart from '@/Components/Chart.vue';
import ResultTable from '@/Components/ResultTable.vue';
import InputError from '@/Components/InputError.vue'

const errors = ref({})

const form = reactive({
    startDate: null,
    endDate: null,
    type: 'perDay',
})

//vue-chartjsのバグのためキーを付与してグラフが再レンダリングされるようにしている。
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
                data.type = res.data.type
                componentKey.value = !componentKey.value
                errors.value = {}
            })
    } catch (e) {
        if (e.response && e.response.status === 422) {
            for (const errorKey in e.response.data.errors) {
                const errorMessage = e.response.data.errors[errorKey][0];
                errors.value[errorKey] = errorMessage
            }
        }
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
                        <InputError v-for="(error, key) in errors" :key="key" :message="error" />
                        <form @submit.prevent="getData">
                            <div class="p-2 w-full flex items-center">
                                From: <input type="date" v-model="form.startDate" class="mx-5">
                                To: <input type="date" v-model="form.endDate" class="mx-5">
                                <button
                                    class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg mr-5">分析する
                                </button>
                                分析方法
                                <input type="radio" v-model="form.type" value="perDay" checked><span class="mr-2">日別</span>
                                <input type="radio" v-model="form.type" value="perMonth"><span class="mr-2">月別</span>
                                <input type="radio" v-model="form.type" value="perYear"><span class="mr-2">年別</span>
                                <input type="radio" v-model="form.type" value="decile"><span class="mr-2">デシル分析</span>
                            </div>
                        </form>
                        <div v-if="data.data">
                            <Chart :data="data" :key="componentKey" />
                            <ResultTable :data="data" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
