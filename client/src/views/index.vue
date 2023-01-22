<script setup>
import { ref, onMounted } from "vue";
import Todos from "../components/Todos.vue";
import axiosApi from "../service/axiosApi";
import { useQuery } from "@tanstack/vue-query";

import ColumnTodo from "../components/ColumnTodo.vue";
import ModalAddTodoForm from "../components/Modals/ModalAddTodoForm.vue";
import TodoPlaceholder from "../components/TodoPlaceholder.vue";

const isOpen = ref(false);
const fectTodos = async () => {
  const token = localStorage.getItem("token");
  await axiosApi.get("/sanctum/csrf-cookie");
  const res = await axiosApi.get("api/todos", {
    headers: {
      Authorization: "Bearer " + token,
    },
  });
  return res.data.data;
};
const { isFetching, data, error } = useQuery({
  queryKey: ["todos"],
  queryFn: fectTodos,
});
</script>

<template>
  <ModalAddTodoForm v-if="isOpen" @close="isOpen = !isOpen" />

  <div class="container">
    <div class="d-flex justify-content-between">
      <h1 class="display-5 text-center">Todos</h1>
      <button
        class="btn btn-light border text-dark mb-3"
        @click="isOpen = !isOpen"
        type="button"
      >
        Create todo
        <i class="bi bi-plus-lg"></i>
      </button>
    </div>
    <div
      class="row"
      :style="{ minHeight: '250px', width: '100%' }"
      v-if="!isFetching"
    >
      <ColumnTodo label="Pending">
        <Todos :todos="data.filter((t) => t.status === 'pending')" />
      </ColumnTodo>
      <ColumnTodo label="In Progress">
        <Todos :todos="data.filter((t) => t.status === 'in_progress')" />
      </ColumnTodo>
      <ColumnTodo label="Completed">
        <Todos :todos="data.filter((t) => t.status === 'completed')" />
      </ColumnTodo>
    </div>

    <div class="row" :style="{ minHeight: '250px', width: '100%' }" v-else>
      <ColumnTodo label="Pending">
        <TodoPlaceholder />
        <TodoPlaceholder />
      </ColumnTodo>
      <ColumnTodo label="In Progress">
        <TodoPlaceholder />
        <TodoPlaceholder />
        <TodoPlaceholder />
      </ColumnTodo>
      <ColumnTodo label="Completed">
        <TodoPlaceholder />
      </ColumnTodo>
    </div>
    
  </div>
</template>

<style scoped></style>
