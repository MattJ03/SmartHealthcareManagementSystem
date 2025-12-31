import { defineStore } from "pinia";
import { ref, reactive, computed } from "vue";
import api from "../axios.js";
export const useAvailabilityStore = defineStore('availability', () => {
    const slots = ref([])
    const loading = ref(false);

    async function getAvailableSlots(doctorId, date) {
        loading.value = true;
        try {
            const res = await api.get(`/doctors/${doctorId}/availability`, { params: { date } });
            slots.value = res.data;
        } catch (err) {
            console.error(err);
            slots.value = [];
        } finally {
            loading.value = false;
        }
    }


    return {
        slots,
        loading,
        getAvailableSlots,
    };
});
