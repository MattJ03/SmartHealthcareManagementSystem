<template>
    <NavBar></NavBar>
    <div class="welcome-container">
        <h1 class="welcome-name">Welcome back, {{ name }}</h1>
        <p class="welcome-name">How are feeling today?</p>
    </div>

    <div class="red-container" >
    <div class="next-appointment-container">
        <div class="next-appoint-clock">
        <img :src="clock" class="img" alt="clock" />
       <h2>Next Appointment</h2>
        </div>
        <button class="view-details-btn">View Details</button>
    </div>
        <h2 class="scheduled-appointment"><strong>Scheduled Appointment</strong></h2>
        <p v-if="nextAppointment" class="doctor-text"> With Dr. {{ doctorName }} </p>
        <div class="three-squares">
        <div v-if="appointmentDate" class="checkup-square">
            <p class="checkup-dates">Date</p>
            <p class="checkup-dates"> {{ appointmentDate }}</p>
        </div>
            <div class="time-square">
                <p class="checkup-dates">Time</p>
                <p class="checkup-dates"> {{ appointmentTime }} {{ appointmentPeriod }}</p>
            </div>
        </div>

    </div>


</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed, onMounted } from 'vue';
import router from "../router/index.js";
import { useAuthStore } from "../stores/AuthStore.js";
import { useAppointmentStore } from "../stores/AppointmentStore.js";
import { storeToRefs } from "pinia";
import clock from '../assets/clock.png';
import {useFormattedAppointment} from "../composobles/useFormattedAppointment.js";

const store = useAuthStore();
const appointmentStore = useAppointmentStore();

const { name } = storeToRefs(store);
const { nextAppointment } = storeToRefs(appointmentStore)

onMounted(() => {
    appointmentStore.fetchUpcomingAppointment();
})

const doctorName = computed(() => nextAppointment.value?.doctor?.name ?? '');

const { appointmentDate, appointmentTime, appointmentPeriod } = useFormattedAppointment(nextAppointment);

</script>
<style style>

.red-container {
    background-color: #C0392B;
    width: 85%;
    margin: auto;
    color: #FFFFFF;
    padding: 20px;
    border-radius: 14px;

}
.welcome-container {
   padding-left: 120px;
    padding-right: 20px;
    padding-top: 15px;
}
.welcome-name {
    color: #000000;
    margin-bottom: 0;
}

.welcome-container p {
    font-size: 20px;
     margin-top: 10px;
}

.next-appointment-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.view-details-btn {
    padding-bottom: 15px;
    padding-top: 15px;
    padding-left: 30px;
    padding-right: 30px;
    background-color: #FFFFFF;
    border-radius: 14px;
    font-size: 16px;
    color: #C0392B;
}
.view-details-btn:hover {
    background-color: #f0f0f0;
    transition-duration: 300ms;
    cursor: pointer;
}
.img {
    height: 25px;
}

.next-appoint-clock {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
}

.scheduled-appointment {
    font-size: 30px;
    margin-bottom: 12px;
}
.three-squares {
    display: flex;
    gap: 24px;

}

.checkup-square {
    height: 44px;
    background-color: #8B0000;
    width: fit-content;
    border: none;
    border-radius: 14px;
    padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 10px;
    padding-right: 10px;

}
.doctor-text {
    font-size: 18px;
    margin: 2px;
    margin-bottom: 12px;
}

.checkup-dates {

    align-items: center;
    font-size: 16px;
    margin: 0;
    margin-bottom: 8px;
}
.time-square {
    background-color: #8B0000;
    border-radius: 14px;
    padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 10px;
    padding-right: 10px;
    margin: 0;
}


</style>
