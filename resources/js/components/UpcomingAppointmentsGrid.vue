<template>
    <div class="container">
        <div class="appointment-card">
            <div class="details">
        <p class="starts-at"> {{ appointmentDate ?? 'Loading..' }}</p>
        <p class="doctor-name"> With Dr. {{  appointment.doctor?.name ?? 'Loading...' }}</p>
        <p class="starts-at"> {{ appointmentTime }} {{ appointmentPeriod ?? 'Loading...' }}</p>
            </div>
            <div class="delete-section">
                <button class="delete-btn" @click="$emit('delete', appointment.id)"> Cancel </button>
                <button class="update-btn" @click="$emit('update', appointment.id)"> Update </button>
            </div>
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

const emit = defineEmits(['delete']);


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
    align-items: center;
    border: 1px solid #E9DCC9;
    background-color: #FFFFFF;
    padding: 16px;
    border-radius: 14px;
}

.details {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 20px;
    flex: 1;
}


.starts-at {
    font-size: 18px;
    margin: 0;
}

.doctor-name {
    font-size: 16px;
    margin: 0;
}


.delete-section {
    display: flex;
    align-items: center;
    gap: 15px;
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

.update-btn {
    padding-bottom: 15px;
    padding-top: 15px;
    padding-left: 25px;
    padding-right: 25px;
    background-color: #C0392B;
    border-radius: 14px;
    font-size: 16px;
    background-color: #FFBF00;
    border: 0;
}
</style>
