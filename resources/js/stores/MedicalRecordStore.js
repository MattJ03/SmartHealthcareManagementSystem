import { ref, reactive, computed } from "vue";
import {defineStore} from "pinia";
import api from "../axios.js";
export const useMedicalRecordStores = defineStore('medicalRecords', () => {
    const loading = ref(false);
    const role = ref(localStorage.getItem('role'));
    const error = ref(null);
    const patientRecords = ref([]);
    const records = ref([]);
    const selectedRecord = ref(null);
    const pdfUrl = ref('');
    const hasRecord = computed(() => patientRecords.value.length > 0);

    const fetchPatientRecords = async (patientId) => {
        loading.value = true;
        error.value = null;
        try {
            const res = await api.get("/getAllRecords", {
                params: { patient_id: patientId },
            });
            patientRecords.value = res.data.records;
        } catch (err) {
            error.value = err.response?.data?.message ?? "Failed to load records";
        } finally {
            loading.value = false;
        }
    };

    const openRecord = async (record) => {

        try {

            loading.value = true;
            error.value = null;

            const response = await api.get(
                `/showMedicalRecord/${record.id}`,
                {
                    responseType: "blob",
                    withCredentials: true,
                }
            );
            console.log(response);
            console.log(response.headers['content-type']);
            if (pdfUrl.value) {
                URL.revokeObjectURL(pdfUrl.value);
            }

            pdfUrl.value = URL.createObjectURL(response.data);

            selectedRecord.value = record;

        } catch (err) {

            console.error(err);

            error.value =
                err.response?.data?.message ??
                "Failed to open medical record";

        } finally {

            loading.value = false;
        }
    };

    const closeRecord = () => {

        if (pdfUrl.value) {
            URL.revokeObjectURL(pdfUrl.value);
        }

        pdfUrl.value = "";
        selectedRecord.value = null;
    };


    const deleteRecord = async (id) => {
        loading.value = true;
        try {
            const res = await api.delete(`/deleteMedicalRecord/{$id}`);
            patientRecords.value = patientRecords.value.filter(r => r.id !== id);
            if (selectedRecord.value?.id === id) selectedRecord.value = null;
        } catch (err) {
            error.value = error.response?.data?.message ?? 'couldnt delete record';
        } finally {
            loading.value =  true;
        }
    }

    const createRecord = async (formData) => {
        loading.value = true;
        error.value = null;
        try {
            const res = await api.post("/storeMedicalRecord", formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });
            patientRecords.value.push(res.data.record);
        } catch (err) {
            error.value = err.response?.data?.message ?? "Failed to upload record";
        } finally {
            loading.value = false;
        }
    };


    const fetchDoctorRecords = async (search = '') => {
        loading.value = true;
        error.value = null;
        try {
            const res = await api.get(`doctor/records`, {
                params: search ? {search} : {},
            });
            records.value = res.data.records;
        } finally {
            loading.value = false;
        }
    }

    return {
        loading,
        error,
        patientRecords,
        records,
        selectedRecord,
        hasRecord,
        fetchPatientRecords,
        openRecord,
        closeRecord,
        deleteRecord,
        createRecord,
        pdfUrl,
        fetchDoctorRecords,
    };

});
