import { defineStore } from "pinia";
import { ref, reactive, computed } from 'vue';
import api from '../axios.js';

export const useAppointmentStore = defineStore('appointment', () => {
    const role = ref(localStorage.getItem('role'));
    const doctor = ref(null);
    const loading = ref(false);
    const error = ref(null);
    const appointment = ref(null);
    const patientAppointments = ref([]);
    const doctorAppointments = ref([]);
    const nextAppointment = ref(null);
    const hasAppointments = computed(() => patientAppointments.value.length > 0);

    const fetchAllMyAppointments = async () => {
      loading.value = true;
      error.value = '';
      try {
          const res = await api.get('/getAllMyAppointments');
          patientAppointments.value = res.data.appointments;
      } catch (error) {
          error.value = error.response?.data?.message ?? 'Failing to load appointments';
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
            error.value = error.response?.data?.message;
        } finally {
            loading.value = false;
        }
    }

    const updateAppointment = async (payload, id) => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.put(`/updateAppointment/${id}`, payload);
            const updated = res.data.appointment;

            const index = patientAppointments.value.findIndex(a => a.id === id);
            if(index !== -1) {
                patientAppointments.value[index] = updated;
            }
            return updated;
        } catch (error) {
            error.value = error.response?.data?.message ?? 'Failed to Update';
        } finally {
            loading.value = false;
        }
    }

    const deleteAppointment = async (id) => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.delete(`/deleteAppointment/${id}`);
            patientAppointments.value = patientAppointments.value.filter(a => a.id !== id);
        } catch (error) {
            error.value = error.response?.data?.message ?? 'Failed to delete';
        } finally {
            loading.value = false;
        }
    }

    const getAppointment = async (id) => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.get(`/getMyAppointment/${id}`);
            appointment.value = res.data.appointment;
            doctor.value = res.data.doctor_name;
        } catch (error) {
            error.value = error.response?.data?.message ?? 'Failed to get Appointment';
        } finally {
            loading.value = false;
        }
    }

    const fetchUpcomingAppointment = async () => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.get('/getAllMyAppointments');

            nextAppointment.value = res.data.appointments?.[0] ?? null;

            if (nextAppointment.value && nextAppointment.value.doctor) {
                doctor.value = nextAppointment.value.doctor.name;
            } else {
                doctor.value = null;
            }
        } catch (error) {
            error.value = error.response?.data?.message ?? 'Failed to fetch Appointment';
        } finally {
            loading.value = false;
        }
    };

    const getUpcomingDoctorAppointments = async () => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.get('/getUpcomingAppointmentsDoctor');
            doctorAppointments.value = res.data.appointments;
        } catch (error) {
            error.value = error.response?.data?.message ?? 'Failing to fetch appointments';
        } finally {
            loading.value = false;
        }
    }

    //go back at some point and fix error handling

     return {
        role,
        loading,
        error,
         appointment,
         patientAppointments,
         doctorAppointments,
         nextAppointment,
         hasAppointments,
         fetchAllMyAppointments,
         createAppointment,
         updateAppointment,
         deleteAppointment,
         fetchUpcomingAppointment,
         getUpcomingDoctorAppointments,
     };

});
