import { createRouter, createWebHistory } from 'vue-router';
import RegisterPatient from '../screens/RegisterPatient.vue';
import Login from "../screens/Login.vue";
import Home from "../screens/Home.vue";
import RegisterAdmin from "../screens/RegisterAdmin.vue";
import RegisterSelect from "../screens/RegisterSelect.vue";

const routes = [
    { path: '/login', component: Login },
    { path: '/home', component: Home },
    { path: '/register-patient', component: RegisterPatient, meta: {role: 'admin'}},
    { path: '/register-admin', component:RegisterAdmin, meta: {role: 'admin'}},
    { path: '/register-select', component: RegisterSelect, meta: {role: 'admin'}},

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
