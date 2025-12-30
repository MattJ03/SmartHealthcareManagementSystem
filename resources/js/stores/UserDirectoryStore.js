import { defineStore } from "pinia";
import { ref, reactive, computed } from "vue";
import api from "../axios.js";
export const useUserDirectoryStore = defineStore('user', () => {
    const doctors = ref([]);
    const loading = ref(null);
    const error = ref(null);

    const fetchDoctors = async () => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.get('/getDoctors');
            doctors.value = res.data.doctors
        } catch (error) {
            error.value = error.response?.message;
        } finally {
            loading.value = false;
        }
    }

    return {
        doctors,
        loading,
        error,
        fetchDoctors,
    }
});
