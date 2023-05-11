<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({ status: Number })

const title = computed(() => {
  return {
    503: '503 Service Unavailable',
    500: '500 Server Error',
    404: '404 Page Not Found',
    403: '403 Forbidden',
  }[props.status]
})

const description = computed(() => {
  return {
    503: '申し訳ありませんが、メンテナンス中です。しばらくしてからもう一度お試しください。',
    500: '申し訳ありませんが、サーバーで問題が発生しました。',
    404: '申し訳ありませんが、お探しのページが見つかりませんでした。',
    403: '申し訳ありませんが、このページへのアクセスは禁止されています。',
  }[props.status]
})
</script>

<template>
  <Head :title="title" />
  <AuthenticatedLayout>
      <div class="message-container">
        <h1>{{ title }}</h1>
        <div>{{ description }}</div>
      </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.message-container {
  height: calc(100vh - 4rem);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.message-container h1 {
  font-size: 24px;
  margin-bottom: 16px;
}

.message-container div {
  font-size: 18px;
}
</style>
