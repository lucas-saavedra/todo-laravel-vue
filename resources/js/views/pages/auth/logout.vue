<template>
  <div class="container vh-100">
    <div class="row h-100 d-flex justify-content-center align-items-center">
      <h1 class="display-6 col-6">Loggin out...</h1>
      <div
        class="spinner-border tex-start"
        role="status"
        aria-hidden="true"
      ></div>
    </div>
  </div>
</template>
<style></style>
<script setup>
import axios from "axios";
import { onMounted } from "vue";
import { useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import axiosApi from "../../../service/axiosApi";
const router = useRouter();
onMounted(async () => {
  try {
    const token = localStorage.getItem("token");
    await axiosApi.get("/sanctum/csrf-cookie");

    const res = await axios({
      url: "http://127.0.0.1:8000/api/logout",
      method: "post",
      headers: {
        Authorization: "Bearer " + token,
      },
    });
    toast.success(res.data.message);
    localStorage.removeItem("token");
  } catch (error) {
    console.log(error);
  }

  router.replace("/login");
});
</script>
