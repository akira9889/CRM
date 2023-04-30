<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
  id: String,
  blog: Object
})

const deleteConfirm = id => {
  // console.log(id);
  router.delete(`/inertia/${id}`, {
    onBefore: () => confirm('本当に削除しますか？')
  })

}
</script>

<template>
  <h1 class="text-7xl">{{ blog.title }}</h1>
  <p>{{ blog.content }}</p>
  <button @click="deleteConfirm(blog.id)">削除</button>
</template>
