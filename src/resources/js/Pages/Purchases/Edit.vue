<script setup>
import { Head, router } from "@inertiajs/vue3";
import { computed, onMounted, reactive, ref } from "vue";
import InputError from '@/Components/InputError.vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import dayjs from 'dayjs'

const props = defineProps({
  'errors': Object,
  'orders': Array,
})

const form = reactive({
  id: props.orders[0].id,
  status: props.orders[0].status,
  items: []
})

const itemList = ref([])
const quantity = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']

const updateSubtotal = (item) => {
  item.subtotal = item.price * item.quantity
}

const totalPrice = computed(() => {
  let total = 0
  itemList.value.forEach(item => {
    total += item.price * item.quantity
  })

  return total
})

const updatePurchase = id => {
  itemList.value.forEach(item => {
    if (item.quantity > 0) {
      form.items.push({
        id: item.id,
        name: item.name,
        price: item.price,
        quantity: item.quantity,
      })
    }
  })

  router.put(route('purchases.update', { purchase: id }), form)
}

onMounted(() => {
  props.orders.forEach(order => {
    const item = {
      id: order.item_id,
      name: order.item_name,
      price: order.item_price,
      quantity: order.quantity,
    }
    updateSubtotal(item)
    itemList.value.push(item)
  })
})

</script>

<template>
  <Head title="購入編集" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">購入編集</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <section class="text-gray-600 body-font relative">
              <form @submit.prevent="updatePurchase(form.id)">
                <div class="container px-5 py-8 mx-auto">
                  <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label for="date" class="leading-7 text-sm text-gray-600">日付</label>
                          <div
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{ dayjs(orders[0].created_at).format('YYYY-MM-DD HH:mm:ss') }}
                          </div>
                        </div>
                      </div>
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label for="customer" class="leading-7 text-sm text-gray-600">会員名</label>
                          <div
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{ orders[0].customer_name }}
                          </div>
                        </div>
                      </div>
                      <div class="w-full mx-auto overflow-auto p-2">
                        <label for="customer" class="leading-7 text-sm text-gray-600">購入商品</label>
                        <template v-for="(error, key) in errors">
                          <InputError v-if="key.includes('items.')" :message="error" />
                        </template>
                        <table class="table-auto w-full whitespace-no-wrap text-center">
                          <thead>
                            <tr>
                              <th
                                class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                                ID</th>
                              <th
                                class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                商品名
                              </th>
                              <th
                                class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                金額
                              </th>
                              <th
                                class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                                数量
                              </th>
                              <th
                                class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 w-24">
                                小計</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="item in itemList" :key="item.id">
                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                {{ item.id }}
                              </td>
                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ item.name }}</td>
                              <td class="border-b-2 border-gray-200 px-4 py-3">{{ item.price }}</td>
                              <td class="border-b-2 border-gray-200 px-4 py-3">
                                <select name="quantity" v-model="item.quantity" @change="updateSubtotal(item)">
                                  <option v-for="q in quantity" :value="q">{{ q }}</option>
                                </select>
                              </td>
                              <td class="border-b-2 border-gray-200 px-4 py-3 text-lg text-gray-900">
                                {{ item.subtotal }}
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="p-2 w-full">
                        <div>
                          <label for="price" class="leading-7 text-sm text-gray-600">合計金額</label>
                          <div
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{ totalPrice }} 円
                          </div>
                        </div>
                      </div>
                      <div class="p-2 w-full">
                        <InputError :message="errors.status" />
                        <div class="relative">
                          <label class="leading-7 text-sm text-gray-600">ステータス</label>
                          <input type="radio" id="notCanceled" name="status" v-model="form.status" value="1">
                          <label for="notCanceled" class="ml-2 mr-4">未キャンセル</label>
                          <input type="radio" id="canceled" name="status" v-model="form.status" value="0">
                          <label for="canceled" class="ml-2 mr-4">キャンセルする</label>
                        </div>
                      </div>
                      <div class="p-2 w-full">
                        <button
                          class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
