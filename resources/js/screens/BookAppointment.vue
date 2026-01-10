<template>
    <Calander
        :appointment-id="Number(route.params.id)"
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

const appointment = ref(null);
const appointmentLoaded = ref(false);

onMounted(async () => {
    if (isEditMode.value) {
        const fetched = await appointmentStore.getAppointment(id);

        appointment.value = fetched;
    }
    appointmentLoaded.value = true;
});


async function handleSubmit(payload) {
    if (route.params.id) {
        await appointmentStore.updateAppointment(route.params.id, payload);
    } else {
        await appointmentStore.createAppointment(payload);
    }
    router.push('/home');
}
</script>

