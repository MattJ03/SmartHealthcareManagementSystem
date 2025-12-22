<template>
    <div class="block">
    <div class="patient-img-container">
        <img :src="patientIcon" alt="patientImage" class="patient-img" />
    </div>
    <div class="doctor-img-container">
        <img :src="doctorIcon" alt="doctorImage" class="doctor-img" />
    </div>
        <div class="admin-img-container">
            <img :src="adminIcon" alt="adminImage" class="admin-img" />
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import patientIcon from '../assets/patienticon.webp';
import doctorIcon from '../assets/newDoctor.png';
import adminIcon from '../assets/adminicon.webp';


</script>
<style scoped>
.block {
    display: flex;
    width: 100%;
    gap: 300px;
}
.patient-img-container {
    height: 100vh;
    display: flex;
    justify-content: left;
    align-items: center;
    width: 20%;
    margin-left: 20px;
}
.patient-img {
    height: 400px;
    display: flex;
    justify-content: left;
    align-items: center;
    border: none;
}
.doctor-img-container {
    display: flex;
    justify-content: center;
    align-items: center;
}
.doctor-img {
    height: 400px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
}
.admin-img-container {
    display: flex;
    justify-content: right;
    align-items: center;
}
.admin-img {
    height: 400px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
}

</style>
