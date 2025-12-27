<template>
    <NavBar></NavBar>
    <div class="welcome-container">
        <h1 class="welcome-name">Welcome back {{ name }}</h1>
        <p class="welcome-name">How are feeling today?</p>
    </div>
    <div class="next-appointment-container">
       <h2>Next Appointment</h2>
        <button class="view-details-btn">View Details</button>
    </div>


</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed } from 'vue';
import router from "../router/index.js";
import { useAuthStore } from "../stores/AuthStore.js";
import { storeToRefs } from "pinia";

const store = useAuthStore();

const { name } = storeToRefs(store);


</script>
<style style>
.welcome-container {
   padding-left: 30px;
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
    background-color: #C0392B;
    color: #FFFFFF;
    width: 65%;
    padding: 20px;

}

.view-details-btn {
    padding-bottom: 15px;
    padding-top: 15px;
    padding-left: 30px;
    padding-right: 30px;
    background-color: #FFFFFF;
    border-radius: 14px;
    font-size: 16px;
}
.view-details-btn:hover {
    background-color: #f0f0f0;
    transition-duration: 300ms;
    cursor: pointer;
}


</style>
