import { ref, reactive, computed } from "vue";
import {defineStore} from "pinia";
import api from "../axios.js";
export const useMedicalRecordStores = defineStore('medicalRecords', () => {
    const loading = ref(false);
    const role = ref(localStorage.getItem('role'));
    const error = ref(null);
    const patientRecords = ref([]);
    const patientSelectedRecord = ref(null);
    const hasRecord = computed(() => patientRecords.value.length > 0);

    const fetchPatientRecord = (async) => {}
    loading.value = true;
    try {
        const res = await api.get()
    }
})
