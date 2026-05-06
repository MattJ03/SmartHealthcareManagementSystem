import { defineStore } from "pinia";
import { ref, reactive, computed } from "vue";
import api from "../axios.js";
export const useUserDirectoryStore = defineStore('user', () => {
    const doctors = ref([]);
    const patients = ref([]);
    const numberPatients = ref(0);
    const numberDoctors = ref(0);
    const loading = ref(null);
    const error = ref(null);

    const fetchDoctors = async () => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.get('/getDoctors');
            doctors.value = res.data.doctors
            numberDoctors.value = res.data.numDoctors;
        } catch (error) {
            error.value = error.response?.message;
        } finally {
            loading.value = false;
        }
    }

    const fetchPatientsForDoctor = async (doctorId) => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.get(`/admin/doctors/${doctorId}/patients`);
            patients.value = res.data.patients;
        } catch(err) {
            error.value = err.response?.message;
        } finally {
            loading.value = false;
        }
    }

    const fetchPatientsOfDoctor = async (search = '') => {
        loading.value = true;
        error.value = '';
        try {
            const res = await api.get(`/doctorPatients`, {
                params: {
                    search: search,
                }
            });

            patients.value = res.data.patients;
            console.log(res.data);
        } catch(error) {
            error.value = error.response?.message;
        } finally {
            loading.value = false;
        }
    }

    const fetchAllPatients = async () => {
        loading.value = true;
        error.value = '';
        console.log('pre fetch pat');
        try {
            const res = await api.get('getAllPatients');
            patients.value = res.data.patients;
            numberPatients.value = res.data.numberPatients;
        } catch(error) {
            error.value = error.response?.message;
        } finally {
            loading.value = false;
        }
    }



    return {
        doctors,
        patients,
        numberPatients,
        numberDoctors,
        loading,
        error,
        fetchDoctors,
        fetchPatientsForDoctor,
        fetchPatientsOfDoctor,
        fetchAllPatients,
    }
});
