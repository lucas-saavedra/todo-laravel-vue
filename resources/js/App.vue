<script setup>
import { storeToRefs } from "pinia";
import appLayout from "./layouts/app-layout.vue";
import authLayout from "./layouts/auth-layout.vue";
import useStore from "./store/index";

const store = useStore();
const { layout } = storeToRefs(store);
</script>

<template>
    <template v-if="layout === 'app'">
        <appLayout />
    </template>
    <template v-else>
        <authLayout/>
    </template>
</template>

<style scoped></style>
