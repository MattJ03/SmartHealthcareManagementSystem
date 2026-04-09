<template>
<NavBar></NavBar>
    <div class="container">
        <h2>My Patients</h2>
       <PatientList
           v-if="role === 'doctor'"
           v-for="patient in patients" :key="patient.id" :patient="patient"

           />

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

onMounted(() => {
    userStore.fetchPatientsOfDoctor();
})
</script>
<style scoped>
.container {
    display: flex;
    margin: auto;
    margin-left: 40px;
}
.patients-info {

}
</style>
