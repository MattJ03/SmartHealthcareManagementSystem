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
                    <img :src="doctor" alt="doctor" class="doctor-img">
                    <p class="number-doctors-text">Number of doctors</p>
                </div>
                <p class="doctors-num-result">45</p>
            </div>
        </div>

    </div>
</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed, onMounted } from "vue";
import { useUserDirectoryStore } from "../stores/UserDirectoryStore.js";
import patient from '../assets/patient.png';
import doctor from '../assets/doctor.png';

const userStore = useUserDirectoryStore();

onMounted(() => {
    userStore.fetchAllPatients()
})

</script>
<style scoped>
.container {
    display: flex;
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
    margin-left: 40px;
    margin-right: 40px;
    margin-top: 20px;
    gap: 40px;
}
.container-square {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background-color: #FFFFFF;
    height: 200px;
    width: 220px;
    border: 1px solid #305cde;
    border-radius: 16px;
}
.top-content {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
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
</style>
