<template>
  <div class="form-floating mb-3">
    <div class="form-floating">
      <textarea
        class="form-control"
        placeholder="Leave a comment here"
        id="floatingTextarea"
        :class="{ 'is-invalid': errorMessage }"
        :id="name"
        :name="name"
        :type="type"
        @change="handleChange"
        :placeholder="placeholder"
      ></textarea>
      <label :for="name">{{ label }}</label>
    </div>

    <div class="col-1 text-sm" v-if="meta.pending">
      <div class="row">
        <div class="col-12">
          <p
            class="spinner-border text-primary spinner-border-sm"
            role="status"
          >
            Validating...
          </p>
        </div>
      </div>
    </div>
    <div v-show="errorMessage" class="text-danger fw-bold pt-1">
      {{ errorMessage }}
    </div>
  </div>
</template>
<script setup>
import { useField } from "vee-validate ";

const props = defineProps({
  name: String,
  type: String,
  label: String,
  placeholder: String,
});

const { errorMessage, meta, handleChange } = useField(props.name, {
  validateOnValueUpdate: false,
  initalValue: "",
});
</script>
