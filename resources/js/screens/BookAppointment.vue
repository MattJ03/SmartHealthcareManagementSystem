<template>
    <Calander
        :appointment="appointment"
        v-if="appointment || !isEditMode"
        @submit="handleSubmit"
    />



</template>
<script setup>
import Calander from "../components/Calander.vue";
import {computed, onMounted} from 'vue';
import { useAppointmentStore } from "../stores/AppointmentStore.js";
import { useRouter, useRoute } from "vue-router";
import router from "../router/index.js";

const route = useRoute();
const appointmentStore = useAppointmentStore();

const id =  route.params.id;
console.log(id);
const isEditMode = computed(() => !!id);

const appointment = computed(() =>
    appointmentStore.patientAppointments.find(
        a => a.id === Number(id)
    ) ?? null
);

onMounted(async () => {
    if(isEditMode.value && !appointment.value) {
       await appointmentStore.getAppointment(id);
    }
});

async function handleSubmit(payload) {
    if(isEditMode.value) {
        await appointmentStore.updateAppointment(id, payload);
    }
    else {
        await appointmentStore.createAppointment(payload);
    }
    await router.push('/home');
}





</script>
<style scoped>
</style>
