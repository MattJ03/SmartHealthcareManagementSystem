<template>
    <div class="container">
        <div class="appointment-card">
        <p class="starts-at"> {{ appointmentDate ?? 'Loading..' }}</p>
        <p class="doctor-name"> With Dr. {{  appointment.doctor?.name ?? 'Loading...' }}</p>
        <p class="starts-at"> {{ appointmentTime }} {{ appointmentPeriod ?? 'Loading...' }}</p>
        </div>
    </div>

</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import {useFormattedAppointment} from "../composobles/useFormattedAppointment.js";

const props = defineProps({
    appointment: {
        type: Object,
        required: true,
    },
});

const appointmentRef = ref(props.appointment);

const { appointmentDate, appointmentTime, appointmentPeriod } = useFormattedAppointment(appointmentRef);

</script>
<style scoped>
.container {
    width: 100%;
    background-color: #FFFFFF;
}
.appointment-card {
    display: flex;
    flex-direction: column;
    justify-content: left;
    border: 1px solid #000000;
    background-color: #FFFFFF;

    padding-left: 10px;
    border-radius: 14px;
}
.starts-at {
    font-size: 18px;
}
.doctor-name {
    font-size: 16px;
}
</style>
