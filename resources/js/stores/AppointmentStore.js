import { defineStore } from "pinia";
import { ref, reactive, computed } from 'vue';
import api from '../axios.js';

export const useAppointmentStore = defineStore('appointment', () => {
    const role = ref(localStorage.getItem('role'));
    const loading = ref(false);
    const error = ref('');
    const appointment = ref(null);
    const patientAppointments = ref([]);
    const hasAppointments = computed(() => patientAppointments.value.length > 0);
});
