<template>
    <div class="block">
    <div class="patient-img-container">
        <strong><label class="title-labels">Patient</label></strong>
        <img :src="patientIcon" alt="patientImage" class="patient-img" @click="movePatientCreate" />
    </div>
    <div class="doctor-img-container">
        <strong><label class="title-labels">Doctor</label></strong>
        <img :src="doctorIcon" alt="doctorImage" class="doctor-img" @click="moveDoctorCreate" />
    </div>
        <div class="admin-img-container">
            <strong><label class="title-labels">Admin</label></strong>
            <img :src="adminIcon" alt="adminImage" class="admin-img" @click="moveAdminCreate" />
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import patientIcon from '../assets/patienticon.webp';
import doctorIcon from '../assets/newNewDoctor.png';
import adminIcon from '../assets/adminicon.webp';
import router from "../router/index.js";

async function movePatientCreate() {
    await router.push('/register-patient');
}
async function moveDoctorCreate() {
    await router.push('/register-doctor');
}
async function moveAdminCreate() {
    await router.push('/register-admin');
}

</script>
<style scoped>
.block {
    display: flex;
    height: 100vh;
    justify-content: center;
    align-items: center;
    width: 100%;
    gap: 300px;
}

.patient-img {
    height: 400px;
    display: flex;
    justify-content: left;
    align-items: center;
    border: none;
}

.doctor-img {
    height: 400px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
}
.patient-img-container,
.doctor-img-container,
.admin-img-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}
.admin-img {
    height: 400px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
}
.title-labels {
    font-size: 40px;
    text-align: center;
    color: #FFFFFF;
}
.patient-img:hover {
    filter: grayscale(100%);
}
.doctor-img:hover {
    filter: grayscale(100%);
}
.admin-img:hover {
    filter: grayscale(100%);
}

</style>
