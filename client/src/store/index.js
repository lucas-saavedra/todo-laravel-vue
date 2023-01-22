

import { defineStore } from 'pinia'

import router from '../router';
import axiosApi from '../service/axiosApi';

const useStore = defineStore('app-store', {
    state: () => {
        return {
            layout: 'app',
            user: null,
            token: null,
        }
    },
    getters: {
        getLayout() {
            return this.layout;
        },

        getToken() {
            if (!localStorage.getItem('token')) {
                return false;
            }
            return localStorage.getItem('token')
        }
    },
    actions: {

        setToken(token) {
            this.token = token;
        },
        setUser(user) {
            this.user = user;
        },

        async logout() {
            const token = localStorage.getItem("token");
            await axiosApi.get("/sanctum/csrf-cookie");
            await axiosApi.post('api/logout', {
                headers: {
                    Authorization: "Bearer " + token,
                }
            });
            this.user = null;
            this.token = null;
            localStorage.removeItem('token');
            this.layout = 'auth';
            router.replace('/login');
        },
        setLayout(payload) {
            this.layout = payload;
        }
    }
})

export default useStore 