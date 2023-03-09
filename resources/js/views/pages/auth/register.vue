<script setup>
import { useForm } from "vee-validate";
import { useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import * as Yup from "yup";

import CustomInput from "../../../components/CustomInput.vue";
import axiosApi from "../../../service/axiosApi.js";
import useStore from "../../../store/index";

const store = useStore();
const router = useRouter();

const validationSchema = Yup.object().shape({
  name: Yup.string().required("Empty field"),
  email: Yup.string()
    .required("Please enter an email.")
    .email("The format does not match."),
  password: Yup.string()
    .default("")
    .min(8, "At least 8 charaacters")
    .required("Password can not be empty."),
  confirmPassword: Yup.string()
    .oneOf([Yup.ref("password"), null], "Confirm password does not match.")
    .required("Confirm your password")
    .default(""),
});

const { handleSubmit, isSubmitting } = useForm({
  validationSchema: validationSchema,
});

const onSubmit = handleSubmit(async (values) => {
  try {
    await axiosApi.get("/sanctum/csrf-cookie");
    const res = await axiosApi.post("api/register", {
      name: values.name,
      email: values.email,
      password: values.password,
      password_confirmation: values.confirmPassword,
    });

    localStorage.setItem("token", res.data.data.access_token);
    store.setUser(res.data.data.user);
    toast.success(res.data.message);
    router.replace({ name: "home" });
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
      <div class="col-md-8">
        <form @submit="onSubmit">
          <h1 class="display-6 text-center">Register</h1>
          <CustomInput
            type="text"
            name="name"
            label="Name"
            placeholder="Name"
          />
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
          <CustomInput
            type="password"
            name="confirmPassword"
            label="Confirm password"
            placeholder="Confirm password"
          />

          <button
            :disabled="isSubmitting"
            class="w-100 btn btn-lg btn-primary"
            type="submit"
          >
            Register
          </button>
        </form>
        <div className="flex flex-col justify-center pt-3">
          <p className="text-center pb-3">Already registered?</p>
          <div className="text-center mt-6">
            <span className="p-2 text-white bg-blue-800 rounded">
              <router-link :to="{ name: 'login' }"
                >Login here</router-link
              ></span
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
