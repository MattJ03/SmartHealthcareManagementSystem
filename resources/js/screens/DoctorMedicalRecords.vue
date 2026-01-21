<template>
<NavBar></NavBar>
    <div class="container">
        <div class="top-of-container">
            <div class="patient-details">
                <h2 class="patient-name">Patient: lorem Ipsum</h2>
                <p class="patient-dates">DOB: 04/12/03 | Patient ID: #12345</p>
            </div>
            <div class="search-wrapper">
                <input type="text" class="search-bar" placeholder="Search patient..." >
                <button class="cancel-button" type="button">X</button>
            </div>
        </div>

        <div class="attach-container">
            <div class="attach-items">
                <div class="attach-circle">
                    <img :src="attach" alt="attach" />
                </div>
                <h3>Upload new records</h3>
                <p>Add medical documents for this patient</p>
            </div>
        </div>

    </div>
</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed } from 'vue';
import { useFormattedAppointment } from "../composobles/useFormattedAppointment.js";
import { useMedicalRecordStores } from "../stores/MedicalRecordStore.js";
import pill from '../assets/pill.PNG';
import attach from '../assets/attach.svg';



</script>
<style scoped>
.attach-container {
    background-color: rgba(72, 86, 242, 0.12);
    border-radius: 14px;
    border: dotted rgba(72, 86, 242, 0.5);;
    width: 30%;
    height:30%;
    padding: 25px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    cursor: pointer;
}
.attach-items {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.attach-items > h3 {
    font-size: 24px;
    margin-top: 0;
    margin-bottom: 13px;
}
.attach-items > p {
    font-size: 18px;
    margin-top: 0;
    margin-bottom: 0;
}
.attach-circle {
    background-color: rgba(72, 86, 242, 0.18);
    border-radius: 500px;
    margin-bottom: 24px;
    padding: 15px;
    width: 50px;
}
.container {
    margin: auto;
    padding: 25px 25px;
    width: 85%;
    background-color: #FFFFFF;
    margin-top: 30px;
    border-radius: 14px;
    border: 1px solid #FFFFFF;
}

.top-of-container {
    display: flex;
    width: 100%;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 25px;
}
.patient-details {

}

.patient-name {
    font-size: 34px;
    margin-top: 0;
    margin-bottom: 0;
}
.patient-dates {
    opacity: 55%;
    font-size: 20px;

    margin-top: 8px;
    margin-bottom: 0;
}
.search-wrapper {

}
.search-bar {
    height: 48px;
    border: 1px solid #c4c4c4;
    outline: none;
    border-radius: 12px 0 0 12px;
    font-size: 20px;
    padding-left: 20px;
}
.search-bar:focus {
    border-color: #C0392B;
    transition-duration: 0.3s;
}
.cancel-button {
    color: #FFFFFF;
    background-color: #C0392B;
    width: 70px;
    height: 48px;
    border: #C0392B;
    border-radius: 0 12px 12px 0;
    font-size: 20px;
    padding-left: 25px;
    padding-right: 25px;
    cursor: pointer;
}
</style>
