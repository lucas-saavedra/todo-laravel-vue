<script setup>
import { ref } from "vue";
import Todo from "./Todo.vue";
const props = defineProps({
  todos: Array,
  status: String,
});
</script>

<template>
  <div v-for="todo in todos" :key="todo.id">
    <Todo
      :id="todo.id"
      :title="todo.title"
      :description="todo.description"
      :status="todo.status"
    />
  </div>
</template>

<style scoped></style>
