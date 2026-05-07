<template>
    <NavBar></NavBar>
    <div class="container">
        <div class="top-row-squares">
            <div class="container-square">
                <div class="top-content">
                    <img :src="patient" alt="patient" class="patient-img">
                <p class="number-patients-text">Number of patients</p>
                </div>
                <p class="patients-num-result">{{ userStore.numberPatients }}</p>
            </div>
            <div class="container-square">
                <div class="top-content">
                    <img :src="doctor1" alt="doctor" class="doctor-img">
                    <p class="number-doctors-text">Number of doctors</p>
                </div>
                <p class="doctors-num-result"> {{ userStore.numberDoctors }}</p>
            </div>
            <div class="container-square">
                <div class="top-content">
                    <img :src="sevenDays" alt="7days" class="week-img">
                    <p class="appointments-next-7days">Appointments next 7 days</p>
                </div>
                <p class="appointments-next-7days-result"> {{appointmentStore.nextSevenAppointments }}</p>
            </div>
            <div class="container-square">
                <div class="top-content">
                    <img :src="calendar" alt="calendarsmall" class="calendar-small-img">
                    <p class="total-appointments-text">Total appointments booked</p>
                </div>
                <p class="total-appointments-result"> {{ appointmentStore.totalAppointments }}</p>
            </div>
            <div class="container-square">
                <div class="top-content">
                    <img :src="cancel" alt="cancel" class="cancel-img">
                    <p class="cancel-text">Total cancelled appointments</p>
                </div>
                <p class="total-cancelled-appointments-result"> {{ logsStore.numCancelledAppointments }}</p>
            </div>
        </div>
        <div class="bottom-row">
        <div class="todays-appointments-container">
            <span class="busy-doctor-title">Most active doctors</span>
            <hr>
            <div v-for="doctor in availablilityStore.busiestDoctors" :id="doctor.id" class="detials-of-buesit-doctors">
                <div class="img-name-wrapper">
                <img :src="doctor1" alt="doctor" class="small-doctor-image">
                <p class="doctor-busy-name"> {{ doctor.name }} </p>
                </div>
                <p class="number-appointments"> {{doctor.doctor_appointments_count }}</p>
                <hr>
            </div>


        </div>
        <div class="recent-activity-container">
             <span class="recent-activity-title">Recent activity</span>
            <hr>
            <div v-for="log in logsStore.fiveAdminLogs"  class="detaials-of-log">
                <p class="type-log"> {{ log.description }}</p>
                <p class="log-time-ago"> {{ dayjs(log.created_at).fromNow()}}</p>
                <hr>
            </div>

        </div>
    </div>



    </div>
</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed, onMounted } from 'vue';
import { useUserDirectoryStore } from "../stores/UserDirectoryStore.js";
import patient from '../assets/patient.png';
import doctor1 from '../assets/doctor.png';
import sevenDays from '../assets/7-days.png';
import { useAppointmentStore } from "../stores/AppointmentStore.js";
import calendar from '../assets/calendar.png';
import cancel from '../assets/cancel.png';
import { useActivityLogsStore } from "../stores/ActivityLogsStore.js";
import dayjs from "dayjs";
import relativeTime from 'dayjs/plugin/relativeTime';
import { useAvailabilityStore } from "../stores/AvailabilityStore.js";


const userStore = useUserDirectoryStore();
const appointmentStore = useAppointmentStore();
const logsStore = useActivityLogsStore();
const availablilityStore = useAvailabilityStore();

dayjs.extend(relativeTime);


onMounted(() => {
    userStore.fetchAllPatients();
    userStore.fetchDoctors();
    appointmentStore.getNextSevenAppointments();
    appointmentStore.getTotalNumberAppointments();
    logsStore.getNumberCancelledAppointments();
    logsStore.getFiveRecentLogsAdmin();
    availablilityStore.getBusyDoctorsAndLeast();
});

</script>
<style scoped>
.container {
    display: flex;
    flex-direction: column;
    border: 1px solid #305cde;
    border-radius: 14px;
    margin: auto;
    margin: 40px;

    background-color: #FFFFFF;
    min-height: 800px;
}
.top-row-squares {
    display: flex;
    flex-direction: row;
    margin-left: 120px;
    margin-right: 40px;
    margin-top: 40px;
    gap: 60px;

}
.container-square {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background-color: #FFFFFF;
    height: 200px;
    width: 240px;
    border: 1px solid #305cde;
    border-radius: 16px;
}
.top-content {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 5px;
    margin-left: 3px;
}
.number-patients-text {
    font-size: 18px;

    color: #4a5568;

}
.patients-num-result {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 70px;
    margin: auto;
}
.img-wrapper {
    display: flex;
    flex-direction: column;

}
.patient-img {
    height: 35px;
    margin-right: 5px;
}
.doctor-img {
    height: 35px;
}
.doctors-num-result {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 70px;
    margin: auto;
}
.number-doctors-text {
      font-size: 18px;
      color: #4a5568;
    padding-top: 20px;
}
.week-img {
    height: 35px;
}
.appointments-next-7days {
    color: #4a5568;
    font-size: 18px;
}
.appointments-next-7days-result {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 70px;
    margin: auto;
}

.calendar-small-img {
    height: 20px;
}
.total-appointments-text {
    color: #4a5568;
    font-size: 18px;
}
.total-appointments-result {
   font-size: 75px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: auto;
}
.cancel-img {
    height: 30px;
}
.cancel-text {
    font-size: 18px;
    color: #4a5568;
}
.total-cancelled-appointments-result {
    font-size: 75px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: auto;
}
.bottom-row {
    display: flex;
    flex-direction: row;
    gap: 20px;
    margin: 30px 40px;
}

.todays-appointments-container {
    flex: 1;
    border: 1px solid #305cde;
    border-radius: 14px;
    padding: 20px;
}

.recent-activity-container {
    flex: 1;
    border: 1px solid #305cde;
    min-height: 200px;
    border-radius: 14px;
    padding: 20px;
}
.break-line {
    color: #305cde;
}
.recent-activity-title {
    font-size: 22px;
}
.details-of-log {
    display: flex;
}
.log-time-ago {
    font-size: 14px;
    color: #4a5568;
}
.busy-doctor-title {
    font-size: 22px;
}
.doctor-busy-name {
    font-size: 18px;
    margin: 0;
}
.number-appointments {
    font-size: 22px;
    color: #4a5568;
}
.small-doctor-image {
    height: 20px;
}
.img-name-wrapper {
    display: flex;
    flex-direction: row;
    gap: 10px;
    justify-content: left;
    align-items: center;

}

</style>
