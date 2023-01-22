import { createRouter, createWebHistory } from 'vue-router';
import useStore from '../store/index'


const routes = [
    {
        path: '/', name: 'home',
        component: () => import(/* webpackChunkName: "app" */'../views/index.vue'),
        meta: {
            layout: 'app',
            isRequredAuth: true
        }
    },
    {
        path: '/report', name: 'report',
        component: () => import(/* webpackChunkName: "app" */'../views/pages/report.vue'),
        meta: {
            layout: 'app',
            isRequredAuth: true
        }
    },
    {
        path: '/logout', name: 'logout',
        component: () => import(/* webpackChunkName: "app" */'../views/pages/auth/logout.vue'),
        meta: {
            layout: 'auth',
            isRequredAuth: true
        }
    },

    {
        path: '/login',
        name: 'login',
        component: () => import(/* webpackChunkName: "auth" */ '../views/pages/auth/login.vue'),
        meta: { layout: 'auth', isRequredAuth: false },
    },

    {
        path: '/register',
        name: 'register',
        component: () => import(/* webpackChunkName: "auth" */ '../views/pages/auth/register.vue'),
        meta: { layout: 'auth', isRequredAuth: false },
    },

    {
        path: '/:pathMatch(.*)*',
        component: () => import(/* webpackChunkName: "auth" */ '../views/pages/error404.vue'),
        meta: { layout: 'auth', isRequredAuth: false },
    }
];


const router = new createRouter({
    history: createWebHistory(),
    linkExactActiveClass: 'active',
    routes
});

router.beforeEach((to, from) => {
    const store = useStore();
    const { setLayout } = store;

    /*    let token = localStorage.getItem("token") ? localStorage.getItem("token") : null;
       if (!token && to.meta.isRequredAuth) {
           setLayout('auth');
           return { name: 'login', query: { redirect: to.fullPath } }
       } */

    if (to.meta && to.meta.layout && to.meta.layout == 'auth') { // (to.meta && to.meta.layout && to.meta.layout == 'auth') 
        setLayout('auth');
    } else {
        setLayout('app');
    }
});

export default router;
