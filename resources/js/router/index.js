import { createRouter, createWebHistory } from 'vue-router';
import Register from '../screens/Register.vue';
import Login from "../screens/Login.vue";
import Home from "../screens/Home.vue";

const routes = [
    { path: '/login', component: Login },
    { path: '/home', component: Home },
    { path: '/register', component: Register, meta: {role: 'admin'}},

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
