import { createRouter, createWebHistory } from 'vue-router';
import Register from '../screens/Register.vue';
import Login from "../screens/Login.vue";


const routes = [
    { path: '/register', component: Register },
    { path: '/login', component: Login }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
