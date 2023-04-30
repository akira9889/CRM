<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const newTitle = ref('')
const newContent = ref('')
</script>

<template>
  Inertiaテスト<br>
  <a href="/">aタグ経由</a><br>
  <Link href="/">Link経由</Link><br>
  <Link :href="route('inertia.index')">名前付きルート</Link><br>
  <Link :href="route('inertia.show', { id: 50 })">ルートパラメーター</Link><br>
  <Link as="button" method="post" :href="route('inertia.store')" :data="{title: newTitle, content: newContent}">保存</Link>
  <div class="mt-8">
    <input type="text" name="newTitle" v-model="newTitle"><br>
    <input type="text" name="newContent" v-model="newContent"><br>
  </div>
</template>
