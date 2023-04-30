<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from "@inertiajs/vue3";

const page = usePage()
const message = computed(() => page.props.flash.message)

defineProps({
  blogs: Array
})
</script>
<template>
  <p v-if="message" class="bg-blue-300">{{ message }}</p>

  <ul>
    <li v-for="blog in blogs" :key="blog.id">
      件名: <Link :href="route('inertia.show', { id: blog.id })" class="text-blue-400">{{ blog.title }}</Link>
      本文: {{ blog.content }}
    </li>
  </ul>
</template>
