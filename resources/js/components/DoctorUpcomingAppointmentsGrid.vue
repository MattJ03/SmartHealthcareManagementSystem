<template>
    <div class="container">
        <div class="appointment-card">
            <p class="patient-name"> {{ appointment.patient?.name ?? 'Loading...'}}</p>
            <p class="starts-at"> {{ appointmentDate ?? 'Loading...' }}</p>
            <p class="starts-at"> {{ appointmentTime }} {{ appointmentPeriod }}</p>
        </div>

    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import {useFormattedAppointment} from "../composobles/useFormattedAppointment.js";

const props = defineProps({
    appointment: {
        type: Object,
        required: true,
    },
});


const { appointmentDate, appointmentTime, appointmentPeriod } = useFormattedAppointment(props.appointment);

</script>
<style scoped>
.container {
    background-color: #FFFFFF;
}
.appointment-card {
    display: flex;
    justify-content: left;
    flex-direction: column;
    border: 1px solid #E9DCC9;
    border-radius: 14px;
}
.starts-at {
    font-size: 18px;
}
.patient-name {
    font-size: 16px;
}
</style>
