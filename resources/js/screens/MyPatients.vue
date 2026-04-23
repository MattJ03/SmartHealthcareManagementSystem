<template>
<NavBar></NavBar>
    <div class="top-of-file">
    <h2 class="header-title">My Patients</h2>
        <div class="search-wrapper">
            <input v-model="search" type=text placeholder="patient" class="search-bar" />
            <button class="cancel-button"  type="button" @click="cancelSearch">X</button>
        </div>
    </div>
        <div class="container">
        <div class="patients-info">
       <PatientList
           v-if="role === 'doctor'"
           v-for="patient in patients" :key="patient.id" :patient="patient"
           @open="openModal"
           />
        </div>
        </div>
    <PatientModal
           v-if="showModal"
           :patient="selectedPatient"
           @click.self="showModal = false"
           :last_appointment="lastAppointment"
    >
    </PatientModal>


</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import { onMounted } from 'vue';
import NavBar from "../components/NavBar.vue";
import { useAuthStore } from "../stores/AuthStore.js";
import {useUserDirectoryStore} from "../stores/UserDirectoryStore.js";
import { storeToRefs } from 'pinia';
import PatientList from "../components/PatientList.vue";
import PatientModal from "../components/PatientModal.vue";
import api from "../axios.js";

const authStore = useAuthStore();
const userStore = useUserDirectoryStore();

const { role } = storeToRefs(authStore);
const { doctors, patients } = storeToRefs(userStore);
const showModal = ref(false);
const selectedPatient = ref(null);
const lastAppointment = ref(null);
const cleanDate = ref(null);
const loading = ref(false);
const search = ref('');

onMounted(() => {
    userStore.fetchPatientsOfDoctor();

});
const openModal =  async (patient) => {
    selectedPatient.value = patient;
    showModal.value = true;
   const res = await api.get(`getLastVisit/${patient.id}`);
    lastAppointment.value = res.data.last_visit;
   console.log(res.data.last_visit);
}


</script>
<style scoped>
.container {
    display: flex;
   margin-left: 20px;
    width: 100%;


}
.patients-info {
    display: flex;
    flex-direction: column;
    width: 40%;
    justify-content: space-between;
    gap: 5px;
    border-radius: 14px;

}
.header-title {
    margin-left: 50px;
}
.top-of-file {
    display: flex;
    margin-top: 40px;

}
.search-wrapper {
    display: flex;
    padding-top: 10px;

}
.search-bar {
    height: 48px;
    border-radius: 12px 0 0 12px;
    font-size: 20px;

    margin-left: 240px;
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
.cancel-button:hover {
    background-color: #8B0000;
    transition: 50ms;
}


</style>
