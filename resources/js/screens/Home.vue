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
        <h2 class="annual-checkup"><strong>Annual Checkup</strong></h2>
        <p> With Dr. </p>
    </div>


</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed } from 'vue';
import router from "../router/index.js";
import { useAuthStore } from "../stores/AuthStore.js";
import { storeToRefs } from "pinia";
import clock from '../assets/clock.png';

const store = useAuthStore();

const { name } = storeToRefs(store);


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

.annual-checkup {
    font-size: 30px;
}

</style>
