<script setup>
import { onMounted, ref } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import dayjs from 'dayjs'

const props = defineProps({
  orders: Object
})

const search = ref('')

const searchCustomers = () => {
  router.get(route('purchases.index', { search: search.value }))
}

const showPurchase = id => {
  router.get(route('purchases.show', { purchase: id }))
}

onMounted(() => {
  console.log(props.orders.data);
})
</script>

<template>
  <Head title="購買履歴" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">購買履歴</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <section class="text-gray-600 body-font">
              <div class="container px-5 py-8 mx-auto">
                <FlashMessage />
                <div class="flex pl-4 my-4 lg:w-2/3 w-full mx-auto">
                  <input type="text" v-model="search" class="rounded text-gray-900 w-60" placeholder="氏名（カナ）または電話番号">
                  <button @click="searchCustomers"
                    class="ml-6 text-white bg-indigo-500 border-0 py-2 px-6  hover:bg-indigo-600 rounded">検索</button>
                </div>
                <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                  <table class="table-auto w-full text-center whitespace-no-wrap">
                    <thead>
                      <tr>
                        <th
                          class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                          ID</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">氏名
                        </th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">合計金額
                        </th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                          ステータス</th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                          購入日</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="order in orders.data" :key="order.id" class="hover:bg-blue-100 cursor-pointer" @click="showPurchase(order.id)">
                        <td class="border-b-2 border-gray-200 px-4 py-3">{{ order.id }}</td>
                        <td class="border-b-2 border-gray-200 px-4 py-3">{{ order.customer_name }}</td>
                        <td class="border-b-2 border-gray-200 px-4 py-3">{{ order.total }}</td>
                        <td class="border-b-2 border-gray-200 px-4 py-3 text-lg text-gray-900">{{ order.status }}</td>
                        <td class="border-b-2 border-gray-200 px-4 py-3 text-lg text-gray-900">{{ dayjs(order.created_at).format('YYYY-MM-DD HH:mm:ss') }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <Pagination class="mt-6 flex justify-center" :links="orders.links" />
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
</style>
