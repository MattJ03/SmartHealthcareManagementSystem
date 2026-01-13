import { createRouter, createWebHistory } from 'vue-router';
import RegisterPatient from '../screens/RegisterPatient.vue';
import Login from "../screens/Login.vue";
import Home from "../screens/Home.vue";
import RegisterAdmin from "../screens/RegisterAdmin.vue";
import RegisterSelect from "../screens/RegisterSelect.vue";
import RegisterDoctor from "../screens/RegisterDoctor.vue";
import BookAppointment from "../screens/BookAppointment.vue";
import MedicalRecords from "../screens/MedicalRecords.vue";

const routes = [
    { path: '/login', component: Login },
    { path: '/home', component: Home },
    { path: '/register-patient', component: RegisterPatient, meta: {role: 'admin'}},
    { path: '/register-admin', component:RegisterAdmin, meta: {role: 'admin'}},
    { path: '/register-select', component: RegisterSelect, meta: {role: 'admin'}},
    { path: '/register-doctor', component: RegisterDoctor, meta: {role: 'admin'}},
    { path: '/book-appointment/:id?', name: 'BookAppointment', component: BookAppointment },
    { path: '/medical-records', name: 'MedicalRecords', component: MedicalRecords },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
