import { ref, reactive, computed } from "vue";
import {defineStore} from "pinia";
import api from "../axios.js";
export const useMedicalRecordStores = defineStore('medicalRecords', () => {
    const loading = ref(false);
    const role = ref(localStorage.getItem('role'));
    const error = ref(null);
    const patientRecords = ref([]);
    const selectedRecord = ref(null);
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

    const openRecord = (record) => {
         selectedRecord.value = record;
    }

    const closeRecord = () => {
        selectedRecord.value = null;
    }

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

    const pdfUrl = computed(() =>
        selectedRecord.value ? `/showMedicalRecord/${selectedRecord.value.id}` : ""
    );

    return {
        loading,
        error,
        patientRecords,
        selectedRecord,
        hasRecord,
        fetchPatientRecords,
        openRecord,
        closeRecord,
        deleteRecord,
        createRecord,
        pdfUrl,
    };

});
