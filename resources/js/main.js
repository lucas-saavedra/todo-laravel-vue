import './bootstrap';
import { createApp } from 'vue'
import { createPinia } from 'pinia';
import { VueQueryPlugin } from "@tanstack/vue-query";
import router from './router';
import App from './App.vue'

import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootswatch/dist/materia/bootstrap.min.css";
import 'bootstrap-icons/font/bootstrap-icons.css'
import './style.css'
const pinia = createPinia();

const app = createApp(App);
import Vue3Toasity from "vue3-toastify";
import "vue3-toastify/dist/index.css";

app.
    use(Vue3Toasity,
        {
            autoClose: 3000,
        })
    .use(VueQueryPlugin)
    .use(pinia)
    .use(router)
    .mount('#app')
