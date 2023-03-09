<template>
  <div class="vue-modal">
    <div class="vue-modal-inner">
      <div class="vue-modal-content">
        <div class="container">
          <div class="row">
            <div class="col">
              <div class="d-flex justify-content-center">
                <h3 class="display-6">Add todo</h3>
              </div>

              <form @submit="onSubmit">
                <CustomInput
                  type="text"
                  name="title"
                  label="Title"
                  placeholder="Title"
                />
                <CustomTextAreaInput
                  name="description"
                  label="Description"
                  placeholder="Description"
                />
                <div class="d-flex gap-3">
                  <button
                    :disabled="loading"
                    class="w-100 py-1 btn btn-lg btn-primary"
                    type="submit"
                  >
                    <span v-if="!loading">Add</span>
                    <span
                      v-else
                      class="spinner-border spinner-border-sm"
                      role="status"
                      aria-hidden="true"
                    ></span>
                    <span class="visually-hidden">Loading...</span>
                  </button>
                  <button
                    :disabled="loading"
                    class="w-100 py-1 btn btn-lg btn-secondary"
                    @click="emits('close')"
                    type="reset"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
import { useMutation, useQueryClient } from "@tanstack/vue-query";
import { useForm } from "vee-validate";
import { toast } from "vue3-toastify";
import * as Yup from "yup";
import CustomInput from "../../components/CustomInput.vue";
import axiosApi from "../../service/axiosApi.js";
import CustomTextAreaInput from "../CustomTextAreaInput.vue";

const emits = defineEmits(["close"]);
const props = defineProps({
  open: Boolean,
});
const loading = ref(false);
const validationSchema = Yup.object().shape({
  title: Yup.string()
    .required("Please enter a title")
    .max(191, "Title can not be longer than 191 characters."),
  description: Yup.string().max(
    255,
    "Description can not be longer than 255 characters."
  ),
});

const { handleSubmit } = useForm({
  validationSchema: validationSchema,
});

const queryClient = useQueryClient();

const mutation = useMutation({
  mutationFn: async (todo) => {
    const token = localStorage.getItem("token");
    await axiosApi.get("/sanctum/csrf-cookie");
    const res = await axiosApi.post(
      "api/todos",
      {
        title: todo.title,
        description: todo.description,
      },
      {
        headers: {
          Authorization: "Bearer " + token,
        },
      }
    );

    return res.data.data;
  },
  onMutate: () => {
    loading.value = true;
  },
  onSuccess: () => {
    queryClient.invalidateQueries(["todos"]);
    toast.info("Todo Added!", {
      position: toast.POSITION.BOTTOM_CENTER,
    });
  },
  onError: (error) => {
    queryClient.invalidateQueries(["todos"]);
    toast.error(error.message, {
      position: toast.POSITION.BOTTOM_CENTER,
    });
  },
  onSettled: () => {
    loading.value = false;
    emits("close");
  },
});

const onSubmit = handleSubmit(async (values) => {
  mutation.mutate({ title: values.title, description: values.description });
});
</script>
<style scoped>
*,
::before,
::after {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.vue-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow-x: hidden;
  overflow-y: auto;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 1;
}

.vue-modal-inner {
  max-width: 500px;
  margin: 2rem auto;
}

.vue-modal-content {
  position: relative;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.3);
  background-clip: padding-box;
  border-radius: 0.3rem;
  padding: 1rem;
}
</style>
