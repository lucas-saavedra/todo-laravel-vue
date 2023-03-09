<script setup>
import { useQuery } from "@tanstack/vue-query";

import axiosApi from "../../service/axiosApi";
const fechtReport = async () => {
  const token = localStorage.getItem("token");
  await axiosApi.get("/sanctum/csrf-cookie");
  const res = await axiosApi.get("api/todos-report", {
    headers: {
      Authorization: "Bearer " + token,
    },
  });
  return res.data.data;
};
const { isFetching, data, error } = useQuery({
  queryKey: ["todos-report"],
  queryFn: fechtReport,
});
</script>

<template>
  <div class="container">
    <div class="row">
      <h1 class="display-6 mb-3">Report</h1>
      <h3>Todos</h3>
      <div class="col" v-if="data">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Added</th>
              <th scope="col">Deleted</th>
              <th scope="col">Average</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <th scope="row  ">{{ data.created }}</th>
              <th scope="row">{{ data.deleted }}</th>
              <th scope="row">
                <div v-if="data.created - data.deleted === 0">0</div>
                <div v-else-if="data.created - data.deleted < 0">
                  <span class="h4 pe-2">{{ data.created - data.deleted }}</span>
                  <i class="rounded bi h3 bi-caret-down bg-danger"></i>
                </div>
                <div v-else>
                  <span class="h4 pe-2">{{ data.created - data.deleted }}</span>
                  <i class="rounded bi h3 bi-caret-up bg-success"></i>
                </div>
              </th>
            </tr>
          </tbody>
        </table>

        <h3>Average time to complete a todo.</h3>
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Current todos</th>
              <th scope="col">Total todos(with deleted todos)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row ">{{ data.average.toFixed(2) }} minutes</th>
              <th scope="row">
                {{ data.averageWithTrashed.toFixed(2) }} minutes
              </th>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <div class="placeholder w-100 py-3 mb-2"></div>
        <div class="placeholder w-100 py-3 mb-3"></div>
        <div class="placeholder w-100 py-3 mt-2 mb-2"></div>
        <div class="placeholder w-100 py-3 mb-2"></div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
