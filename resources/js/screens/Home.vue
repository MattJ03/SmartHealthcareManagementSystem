<template>
    <NavBar></NavBar>
    <div class="welcome-container">
        <h1 class="welcome-name">Welcome back, {{ name }}</h1>
        <p class="welcome-name">How are feeling today?</p>
    </div>

    <div class="red-container">
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
        <div v-if="formattedAppointment.appointmentDate" class="checkup-square">
            <p class="checkup-dates">Date</p>
            <p class="checkup-dates">
                {{ formattedAppointment.appointmentDate }}
            </p>
        </div>

            <div v-if="formattedAppointment.appointmentTime" class="time-square">
                <p class="checkup-dates">Time</p>
                <p class="checkup-dates">
                    {{ formattedAppointment.appointmentTime }}
                    {{ formattedAppointment.appointmentPeriod }}
                </p>
            </div>

        </div>
    </div>
    <div class="quick-actions-container">
        <h1 class="quick-actions-header">Quick Actions</h1>
        <div class="quick-actions-row">
            <div class="quick-actions-white-square" @click="moveToBook">
                <img :src="book" alt="book" class="quick-actions-img" />
                <p class="quick-actions-text">Book Appointment</p>
            </div>
            <div class="quick-actions-white-square" @click="moveToRecords">
                <img :src="history" alt="history" class="quick-actions-img" />
                <p class="quick-actions-text">Medical Records</p>
            </div>
        </div>
        <div class="quick-actions-row">
            <div class="quick-actions-white-square">
                <img :src="messages" alt="messages" class="quick-actions-img" />
                <p class="quick-actions-text">Messages</p>
            </div>
            <div class="quick-actions-white-square">
                <img :src="alert" alt="alert" class="quick-actions-img" />
                <p class="quick-actions-text">Alerts</p>
            </div>
        </div>
        <h2>Upcoming Appointments</h2>
        <UpcomingAppointmentsGrid
            v-if="role === 'patient'"
            v-for="appointment in patientAppointments"
            :key="appointment.id"
            :appointment="appointment"
            @delete="handleDeleteAppointment"
            @update="goToUpdate"
        />
        <DoctorUpcomingAppointmentsGrid v-else-if="role === 'doctor'" v-for="appointment in doctorAppointments" :key="appointment.id" :appointment="appointment" />
    </div>

</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import UpcomingAppointmentsGrid from "../components/UpcomingAppointmentsGrid.vue";
import { ref, reactive, computed, onMounted } from 'vue';
import { useAuthStore } from "../stores/AuthStore.js";
import { useAppointmentStore } from "../stores/AppointmentStore.js";
import { storeToRefs } from "pinia";
import clock from '../assets/clock.png';
import alert from '../assets/alert.png';
import messages from '../assets/messages.png'
import book from '../assets/book.png';
import history from '../assets/history.png'
import {useFormattedAppointment} from "../composobles/useFormattedAppointment.js";
import DoctorUpcomingAppointmentsGrid from "../components/DoctorUpcomingAppointmentsGrid.vue";
import {useRouter} from "vue-router";


const store = useAuthStore();
const appointmentStore = useAppointmentStore();

const router = useRouter();

const { name, role } = storeToRefs(store);
const { nextAppointment, patientAppointments, doctorAppointments } = storeToRefs(appointmentStore)

onMounted(() => {

    appointmentStore.fetchUpcomingAppointment();
    if(role.value === 'patient') {
        appointmentStore.fetchAllMyAppointments();
    }
    if(role.value === 'doctor') {
        appointmentStore.getUpcomingDoctorAppointments();
    }
})

const doctorName = computed(() => nextAppointment.value?.doctor?.name ?? '');



const formattedAppointment = useFormattedAppointment(nextAppointment);



async function moveToBook() {
    await router.push('/book-appointment');
}

async function moveToRecords() {
    await router.push('/medical-records');
}

const handleDeleteAppointment = async (appointmentId) => {
    try {
        await appointmentStore.deleteAppointment(appointmentId);

        if (role.value === "patient") {
            patientAppointments.value = patientAppointments.value.filter((a) => a.id !== appointmentId);
        } else if (role.value === "doctor") {
            doctorAppointments.value = doctorAppointments.value.filter((a) => a.id !== appointmentId);
        }

        if (nextAppointment.value?.id === appointmentId) {
            await appointmentStore.fetchUpcomingAppointment();
        }
    } catch (error) {
        console.log('Failed to delete');
    }
}

function goToUpdate(appointmentId) {
    router.push({
        name: 'BookAppointment',
        params: { id: appointmentId, },
    });
}



</script>
<style style>

.red-container {
    background-color: #C0392B;
    width: 85%;
    margin: auto;
    margin-top: 24px;
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
    height: 50px;
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
.quick-actions-container {
    width: 85%;
    display: flex;
    flex-direction: column;
    margin: 24px auto 0 auto;
    gap: 16px;
}

.quick-actions-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.quick-actions-white-square {
    position: relative;
    background-color: #FFFFFF;
    border: 1px solid #E9DCC9;
    border-radius: 14px;
    padding-top: 50px;
    padding-bottom: 50px;
    padding-left: 40px;
    padding-right: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.quick-actions-white-square img {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    height: 35px;
    width: 35px;
}


.quick-actions-text{
    margin: 0;
    font-size: 18px;
}




</style>
