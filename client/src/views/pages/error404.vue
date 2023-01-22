<script setup>
import { ref } from "vue";
</script>

<template>
  <div class="container vh-100">
    <div class="row h-100 d-flex justify-content-center align-items-center">
      <div class="col">
        <h1 class="display-2">404</h1>
        <h2 class="display-6">Page not found.</h2>
        <div class="col-md-2">
          <router-link
            class="text-white btn btn-sm bg-dark px-3 mt-3"
            :to="{ name: 'home' }"
          >
            Back to home</router-link
          >
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
