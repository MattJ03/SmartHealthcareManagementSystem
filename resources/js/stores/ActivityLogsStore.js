import { defineStore } from 'pinia';
import { ref, reactive, computed } from 'vue';
import api from "../axios.js";

export const useActivityLogsStore = defineStore('activity_logs', () => {
    const role = localStorage.getItem('role');
    const allLogs = ref([]);
    const patientLogs = ref([]);
    const doctorLogs = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const getAllLogs = async () => {
        loading.value = true;
        try {
            const res = await api.get('/getCompleteLogList');
            allLogs.value = res.data.data;
        } catch (error) {
            error.value = error.response?.data?.message ?? 'Failed to fetch all logs';
        }
        finally {
            loading.value = false;
        }
    }

    const getPatientLogs = async () => {
        loading.value = true;
        try {
            const res = await api.get('/getPatientsLogList');
            patientLogs.value = res.data.data;
        } catch(error) {
            error.value = error.response?.data?.message ?? 'Failed to fetch patient logs';
        }
        finally {
            loading.value = false;
        }
    }

    const getDoctorLogs = async () => {
        loading.value = true;
        try {
            const res = await api.get('/getDoctorsLogList');
            doctorLogs.value = res.data.data;
        } catch(error) {
            error.value = error.response?.data?.message ?? 'Failed to fetch doctor logs';
        } finally {
            loading.value = false;
        }
    }

});
