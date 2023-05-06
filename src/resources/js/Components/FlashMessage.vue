<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const status = computed(() => page.props.flash.status)
const message = computed(() => page.props.flash.message)
</script>

<template>
    <p v-if="status === 'success'" class="bg-blue-300 text-white p-4 w-1/4">{{ message }}</p>
    <p v-if="status === 'danger'" class="bg-red-300 text-white p-4 w-1/4">{{ message }}</p>
</template>


<style scoped>

</style>
