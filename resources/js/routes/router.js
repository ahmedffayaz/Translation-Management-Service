import register from "../Auth/Register.vue"
import login from "../Auth/login.vue"
import homePage from "../Pages/HomePage.vue"
import AddTranslation from "../Pages/AddTranslation.vue"

import { createRouter, createWebHistory } from 'vue-router';

var routes = [

    {
        name: 'login',
        component: login,
        path: '/',
        meta: {
            requiresAuth: false,
        }
    },
    {
        name: 'register',
        component: register,
        path: '/register',
        meta: {
            requiresAuth: false,
        }
    },
    {
        name: 'home',
        component: homePage,
        path: '/home-page',
        meta: {
            requiresAuth: true,
        }
    },
    {
        name: 'AddTranslation',
        component: AddTranslation,
        path: '/add-translation',
        meta: {
            requiresAuth: true,
        }
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from) => {
    if (to.meta.requiresAuth == true && !localStorage.getItem('token')) {
        return { name: "login" }
    }
    if (to.meta.requiresAuth == false && localStorage.getItem('token')) {
        return { name: "home" }
    }
})



export default router;