<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import Label from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'

const emitTest = e => console.log(e);
</script>

<template>
<GuestLayout>
  <Label value="件名">ラベル</Label>
  <TextInput modelValue="初期値が入ります"
  autofocus
  @update:modelValue="emitTest"></TextInput>
</GuestLayout>
</template>


<style scoped>

</style>
