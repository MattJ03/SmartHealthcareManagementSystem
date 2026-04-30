<template>
    <div class="container">
        <div class="appointment-card">
            <div class="details">
                <span class="patient-name"> {{ props.appointment.patient.name }}</span>
                <span class="doctor-name"> with Dr. {{ props.appointment.doctor.name }}</span>
                <span class="starts-at"> {{ appointmentDate }}</span>
                <span class="starts-at"> {{ appointmentTime }} {{ appointmentPeriod }}</span>
            </div>
            <div class="delete-section">
                <button class="delete-btn" @click="$emit('delete', appointment.id)">Cancel</button>
                <button class="update-btn" @click="$emit('update', appointment.id)">Update</button>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { useFormattedAppointment } from "../composobles/useFormattedAppointment.js";
import {storeToRefs} from "pinia";

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
    background-color: #FFFFFF;
    width: 100%;
    border-radius: 14px;
    border: 1px solid #E9DCC9;
}
.appointment-card {
    display: flex;
    padding-left: 15px;
    justify-content: left;
    flex-direction: row;
}
.details {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1;
    gap: 30px;
    padding: 15px 15px 15px 15px;
}
.patient-name {
    font-size: 18px;
}
.doctor-name {
    font-size: 18px;
}
.starts-at {
    font-size: 18px;
}
.delete-section {
    display: flex;
    align-items: center;
    justify-content: right;
    padding-right: 15px;
    gap: 20px;
    flex: 1;
}
.delete-btn {
    padding-bottom: 15px;
    padding-top: 15px;
    padding-left: 25px;
    padding-right: 25px;
    background-color: #C0392B;
    border-radius: 14px;
    font-size: 16px;
    color: #FFFFFF;
    border: 0;
}

.delete-btn:hover {
    cursor: pointer;
    background-color: #8B0000;
}

.update-btn {
    padding-bottom: 15px;
    padding-top: 15px;
    padding-left: 25px;
    padding-right: 25px;
    background-color: #C0392B;
    border-radius: 14px;
    font-size: 16px;
    color: #FFFFFF;
    background-color: #FFBF00;
    border: 0;
}

.update-btn:hover {
    background-color: #FF8C00;
    cursor: pointer;
}
</style>
