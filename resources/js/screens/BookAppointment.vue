<template>
    <Calander
        v-if="!isEditMode || appointmentLoaded"
        :appointment="appointment"
        @submit="handleSubmit"
    />
</template>

<script setup>
import Calander from "../components/Calander.vue";
import { ref, computed, onMounted } from "vue";
import { useAppointmentStore } from "../stores/AppointmentStore.js";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();
const appointmentStore = useAppointmentStore();

const id = route.params.id;
const isEditMode = computed(() => !!id);

const appointment = ref(null); // start with null
const appointmentLoaded = ref(false); // only true once appointment is fetched

onMounted(async () => {
    if (isEditMode.value) {
        // Fetch the appointment by ID
        const fetched = await appointmentStore.getAppointment(id);
        // assuming getAppointment returns the appointment object
        appointment.value = fetched;
    }
    appointmentLoaded.value = true;
});

async function handleSubmit(payload) {
    if (isEditMode.value) {
        await appointmentStore.updateAppointment(id, payload);
    } else {
        await appointmentStore.createAppointment(payload);
    }
    router.push("/home");
}
</script>

