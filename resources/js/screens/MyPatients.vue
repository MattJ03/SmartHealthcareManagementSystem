<template>
<NavBar></NavBar>
    <h2 class="header-title">My Patients</h2>
    <div class="container">
        <div class="patients-info">
       <PatientList
           v-if="role === 'doctor'"
           v-for="patient in patients" :key="patient.id" :patient="patient"
           @open="openModal"
           />
        </div>
        </div>
    <div v-if="showModal === true"
         class="modal-overlay"
    >
    </div>

</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import { onMounted } from 'vue';
import NavBar from "../components/NavBar.vue";
import { useAuthStore } from "../stores/AuthStore.js";
import {useUserDirectoryStore} from "../stores/UserDirectoryStore.js";
import { storeToRefs } from 'pinia';
import PatientList from "../components/PatientList.vue";

const authStore = useAuthStore();
const userStore = useUserDirectoryStore();

const { role } = storeToRefs(authStore);
const { doctors, patients } = storeToRefs(userStore);
const showModal = ref(false);

onMounted(() => {
    userStore.fetchPatientsOfDoctor();
});
const openModal = () => {
    showModal.value = true;
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
    margin-left: 20px;
}
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}
</style>
