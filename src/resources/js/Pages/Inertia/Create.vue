<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

defineProps({
  errors: Object
})

const form = reactive({
  title: null,
  content: null
})

const submitFunction = () => {
  router.post('/inertia/create', form)
}
</script>

<template>
  <form @submit.prevent="submitFunction">
    <p v-if="errors.title">{{ errors.title }}</p>
    <input type="text" name="title" v-model="form.title"><br>
    <p v-if="errors.content">{{ errors.content }}</p>
    <input type="text" name="content" v-model="form.content"><br>
    <button type="submit">送信</button>
  </form>
</template>


<style scoped>
</style>
