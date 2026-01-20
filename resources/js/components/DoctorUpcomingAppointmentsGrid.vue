<template>
    <div class="container">
        <div class="appointment-card">
            <div class="details">
            <p class="patient-name"> {{ appointment.patient?.name ?? 'Loading...'}}</p>
            <p class="starts-at"> {{ appointmentDate ?? 'Loading...' }}</p>
            <p class="starts-at"> {{ appointmentTime }} {{ appointmentPeriod }}</p>
        </div>
            <div class="delete-section">
                <button class="delete-btn" @click="$emit('delete', appointment.id)"> Cancel </button>
                <button class="update-btn" @click="$emit('update', appointment.id)"> Update </button>
            </div>
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

const emit = defineEmits(['delete', 'update']);

const appointmentRef = ref(props.appointment)
const { appointmentDate, appointmentTime, appointmentPeriod } = useFormattedAppointment(appointmentRef);

</script>
<style scoped>
.container {
    background-color: #FFFFFF;
    width: 100%;
}
.appointment-card {
    display: flex;
    justify-content: left;
    flex-direction: row;
    border: 1px solid #E9DCC9;
    border-radius: 14px;
}
.starts-at {
    font-size: 18px;
}
.patient-name {
    font-size: 20px;
}
.delete-section {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-right: 15px;
}
.details {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1;
    padding-left: 15px
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
