<script setup>
import { ref } from "vue";
import { useMutation, useQueryClient } from "@tanstack/vue-query";
import { toast } from "vue3-toastify";
import axiosApi from "../service/axiosApi";
const queryClient = useQueryClient();

const props = defineProps({
  id: Number,
  title: String,
  description: String,
  status: String,
});
const selected = ref(props.status);
const loading = ref(false);
const mutationsOptions = {
  onMutate: () => {
    loading.value = true;
  },
  onSuccess: () => {
    loading.value = false;
    queryClient.invalidateQueries(["todos"]);
    toast.info("Todo Updated!", {
      position: toast.POSITION.BOTTOM_CENTER,
    });
  },
  onError: (error) => {
    queryClient.invalidateQueries(["todos"]);
    toast.error(error, {
      position: toast.POSITION.BOTTOM_CENTER,
    });
  },
};

const updateTodo = async (status) => {
  updateMutation.mutate({ id: props.id, status });
};

const deleteTodo = async (id) => {
  deleteMutation.mutate({ id });
};

const deleteMutation = useMutation({
  mutationFn: async (todo) => {
    const token = localStorage.getItem("token");
    await axiosApi.get("/sanctum/csrf-cookie");
    const res = await axiosApi.delete("api/todos/" + todo.id, {
      headers: {
        Authorization: "Bearer " + token,
      },
    });
    return res.data.data;
  },
  ...mutationsOptions,
  onSuccess: () => {
    loading.value = false;
    queryClient.invalidateQueries(["todos"]);
    toast.warning("Todo deleted!", {
      position: toast.POSITION.BOTTOM_CENTER,
    });
  },
});

const updateMutation = useMutation({
  mutationFn: async (todo) => {
    const token = localStorage.getItem("token");
    await axiosApi.get("/sanctum/csrf-cookie");
    const res = await axiosApi.put(
      "api/todos/" + todo.id,
      { status: todo.status },
      {
        headers: {
          Authorization: "Bearer " + token,
        },
      }
    );

    return res.data.data;
  },
  ...mutationsOptions,
});

const statusClass = ref({
  "border-primary": selected.value === "pending",
  "border-warning": selected.value === "in_progress",
  "border-success": selected.value === "completed",
});
</script>

<template>
  <div class="col">
    <div class="card mb-3 position-relative" :class="statusClass">
      <div
        class="position-absolute top-50 start-50 translate-middle"
        v-if="loading"
      >
        <div class="spinner-grow text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <div class="card-body mb-0 pb-0">
        <h5 class="card-title">{{ title }}</h5>
        <p class="card-text text-muted pt-2" v-if="description">
          {{ description }}
        </p>
        <select
          class="form-select fw-bold mt-3"
          v-model="selected"
          :disabled="selected === 'completed' ? true : false"
          @change="(e) => updateTodo(e.target.value)"
        >
          <option value="pending">Pending</option>
          <option value="in_progress">Progress</option>
          <option value="completed">Completed</option>
        </select>
      </div>
      <div class="text-end p-1 mt-3">
        <button @click="deleteTodo(id)" class="btn btn-sm bg-danger">
          <i class="bi bi-trash text-white"> </i>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
