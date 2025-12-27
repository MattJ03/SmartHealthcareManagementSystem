import { defineStore } from "pinia";
import { ref, reactive, computed } from 'vue';
import api from '../axios.js';

export const useAppointmentStore = defineStore('appointment', () => {
    const role = ref(localStorage.getItem('role'));
    const loading = ref(false);
    const error = ref(null);
    const appointment = ref(null);
    const patientAppointments = ref([]);
    const hasAppointments = computed(() => patientAppointments.value.length > 0);

    const fetchAllMyAppointments = async () => {
      loading.value = true;
      error.value = '';
      try {
          const res = await api.get('/getAllMyAppointments');
          patientAppointments.value = res.data.appointments;
      } catch (error) {
          error.value = error.response?.message ?? 'Failing to load appointments';
        } finally {
          loading.value = false;
      }
    };

    const createAppointment = async (payload) => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.post(`/storeAppointment`, payload);
            patientAppointments.value.push(res.data.appointment);
        } catch (error) {
            error.value = error.response?.message;
        } finally {
            loading.value = false;
        }
    }

    const updateAppointment = async (payload, id) => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.put(`/updateAppointment/${id}`, payload);
            appointment.value = res.data.appointment;
            return res.data;
        } catch (error) {
            error.value = error.response?.message ?? 'Failed to Update';
        } finally {
            loading.value = false;
        }
    }

});
