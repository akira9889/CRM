<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import dayjs from 'dayjs'
import FlashMessage from "@/Components/FlashMessage.vue";

const props = defineProps({
  'orders': Array,
  'total': Number
})
</script>

<template>
  <Head title="購入詳細" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">購入詳細</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <FlashMessage />
            <section class="text-gray-600 body-font relative">
              <div class="container px-5 py-8 mx-auto">
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                  <div class="flex flex-wrap -m-2">
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="date" class="leading-7 text-sm text-gray-600">日付</label>
                        <div
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          {{ dayjs(orders[0].created_at).format('YYYY-MM-DD HH:mm:ss') }}
                        </div>
                      </div>
                    </div>
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="customer" class="leading-7 text-sm text-gray-600">会員名</label>
                        <div
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          {{ orders[0].customer_name }}
                        </div>
                      </div>
                    </div>
                    <div class="w-full mx-auto overflow-auto p-2">
                      <table class="table-auto w-full text-left whitespace-no-wrap">
                        <thead>
                          <tr>
                            <th
                              class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                              ID</th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                              商品名
                            </th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                              金額
                            </th>
                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                              数量
                            </th>
                            <th
                              class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 w-24">
                              小計</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="order in orders" :key="order.id">
                            <td class="border-b-2 border-gray-200 px-4 py-3">
                              {{ order.item_id }}
                            </td>
                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ order.item_name }}</td>
                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ order.item_price }}</td>
                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ order.quantity }}</td>
                            <td class="border-b-2 border-gray-200 px-4 py-3 text-lg text-gray-900">
                              {{ order.subtotal }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="p-2 w-full">
                      <div>
                        <label for="price" class="leading-7 text-sm text-gray-600">合計金額</label>
                        <div
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          {{ total }} 円
                        </div>
                      </div>
                    </div>
                    <div class="p-2 w-full">
                      <div>
                        <label for="price" class="leading-7 text-sm text-gray-600">ステータス</label>
                        <div v-if="orders[0].status === 1"
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          未キャンセル
                        </div>
                        <div v-if="orders[0].status === 0"
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 text-base text-red-500 outline-none py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          キャンセル済み
                        </div>
                      </div>
                    </div>
                    <div v-if="!orders[0].status" class="p-2 w-full">
                      <div>
                        <label for="price" class="leading-7 text-sm text-gray-600">キャンセル日</label>
                        <div
                          class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          {{ dayjs(orders[0].updated_at).format('YYYY-MM-DD HH:mm:ss') }}
                        </div>
                      </div>
                    </div>
                    <div class="my-2 mx-auto" v-if="props.orders[0].status">
                      <Link as="button" :href="route('purchases.edit', { 'purchase': orders[0].id })"
                        class="flex  text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">
                      編集する</Link>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
