<script setup>
import { useForm } from "vee-validate";
import * as Yup from "yup";
import { useRouter } from "vue-router";

import CustomInput from "../../../components/CustomInput.vue";
import axiosApi from "../../../service/axiosApi.js";
import useStore from "../../../store/index";
import { toast } from "vue3-toastify";

const store = useStore();
const router = useRouter();

const validationSchema = Yup.object().shape({
  email: Yup.string()
    .required("Please enter an email.")
    .email("The format does not match."),
  password: Yup.string()
    .default("")
    .min(8, "At least 8 charaacters")
    .required("Password empty."),
});

const { handleSubmit, isSubmitting } = useForm({
  validationSchema: validationSchema,
});

const onSubmit = handleSubmit(async (values) => {
  try {
    await axiosApi.get("/sanctum/csrf-cookie");
    const res = await axiosApi.post("api/login", {
      email: values.email,
      password: values.password,
    });
    localStorage.setItem("token", res.data.data.access_token);
    store.setUser(res.data.data.user);
    toast.success(res.data.message);
    router.push({ name: "home" });
  } catch (error) {
    toast.error(
      error.response.data.message ? error.response.data.message : error.message
    );
  }
});
</script>

<template>
  <div class="container vh-100">
    <div class="row h-100 d-flex justify-content-center align-items-center">
      <div class="col-md-4">
        <form @submit="onSubmit">
          <h1 class="display-6 text-center">Login</h1>
          <CustomInput
            type="email"
            name="email"
            label="Email"
            placeholder="Email"
          />
          <CustomInput
            type="password"
            name="password"
            label="Password"
            placeholder="Password"
          />

          <button
            :disabled="isSubmitting"
            class="w-100 btn btn-lg btn-primary"
            type="submit"
          >
            Login
          </button>
        </form>
        <div className="flex flex-col justify-center pt-3">
          <p className="text-center">Does not have an account?</p>
          <div className="text-center mt-6">
            <span className="p-2 text-white bg-blue-800 rounded">
              <router-link :to="{ name: 'register' }"
                >Register here</router-link
              ></span
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
