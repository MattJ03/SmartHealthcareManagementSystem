<template>
<NavBar></NavBar>
    <div class="container">
        <div class="top-of-container">
            <div class="patient-details">
                <h2 class="patient-name">Patient: lorem Ipsum</h2>
                <p class="patient-dates">DOB: | Patient ID:</p>
            </div>
            <div class="search-wrapper">
                <input class="search-bar" placeholder="Search patient..."  >
            </div>
        </div>

    </div>
</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed } from 'vue';
import { useFormattedAppointment } from "../composobles/useFormattedAppointment.js";
import { useMedicalRecordStores } from "../stores/MedicalRecordStore.js";



</script>
<style scoped>
.container {
    display: flex;
    margin: auto;
    padding: 25px 25px;
    width: 85%;
    height: 400px;
    background-color: #FFFFFF;
    margin-top: 30px;
    border-radius: 14px;
    border: 1px solid #FFFFFF;
}

.top-of-container {
    display: flex;
    width: 100%;
    flex-direction: row;
    justify-content: space-between;
}
.patient-details {

}

.patient-name {
    font-size: 34px;
    margin-top: 0;
    margin-bottom: 0;
}
.patient-dates {
   color: #A9A9A9;
}
.search-wrapper {

}
.search-bar {
    height: 44px;
    border: 1px solid #A9A9A9;
    border-radius: 12px;
    font-size: 20px;
    padding-left: 5px;
}
</style>
