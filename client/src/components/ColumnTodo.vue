<script setup>
defineProps({
  label: String,
});
</script>

<template>
  <div class="col-4 bg-light border-end px-3">
    <h3 class="text-center fw-bold py-3">{{ label }}</h3>
    <div class="row row-cols-1 g-1">
      <slot />
    </div>
  </div>
</template>

<style scoped></style>
