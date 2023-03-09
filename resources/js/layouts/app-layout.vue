<template>
  <div v-if="!loading">
    <!--  BEGIN NAVBAR  -->
    <Header></Header>
    <!--  END NAVBAR  -->

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
      <router-view v-slot="{ Component }">
        <component :is="Component" />
      </router-view>
      <!-- BEGIN FOOTER -->
      <Footer></Footer>
      <!-- END FOOTER -->
    </div>
    <!--  END CONTENT AREA  -->
  </div>
</template>

<script setup>
import Header from "../components/layout/Header.vue";
import Footer from "../components/layout/Footer.vue";

import { useRoute, useRouter } from "vue-router";
import useStore from "../store/index";
import { onBeforeMount, ref } from "vue";
import axiosApi from "../service/axiosApi";

const store = useStore();
const router = useRouter();
const route = useRoute();
const loading = ref(true);

onBeforeMount(async () => {
  loading.value = true;
  try {
    if (localStorage.getItem("token")) {
      const token = localStorage.getItem("token");
      await axiosApi.get("/sanctum/csrf-cookie");
      const res = await axiosApi.get("/api/profile", {
        headers: { Authorization: "Bearer " + token },
      });
      store.setUser(res.data.data.user);
    } else {
      throw new Error("Unauthenticated");
    }
    loading.value = false;
  } catch (e) {
    loading.value = false;
    router.replace({ name: "login", redirect: route.fullPath });
    e.value = e.message;
  }
});
</script>
