<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <router-link class="navbar-brand" to="/">Home</router-link>

      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <router-link class="nav-link" :to="{ name: 'report' }"
            >Report</router-link
          >
        </li>
      </ul>
    </div>
    <div class="text-white navbar-nav px-2 me-auto dropdown">
      <a
        :style="{ cursor: 'pointer' }"
        class="nav-link bg-transparent dropdown-toggle"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        {{ store?.user?.name }}
      </a>
      <ul class="dropdown-menu p-3 dark">
        <router-link class="nav-link text-dark" :to="{ name: 'logout' }"
          >Logout</router-link
        >
      </ul>
    </div>
  </nav>
</template>

<script setup>

import useStore from "../../store/index";

const store = useStore();
</script>
