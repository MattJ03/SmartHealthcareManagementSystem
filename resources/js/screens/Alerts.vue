<template>
    <NavBar></NavBar>
    <div class="container">
        <div class="log-container">
        <div class="log-headings">
            <h2>hello</h2>
            <h2>goodbye</h2>
            <h2>question</h2>
        </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import { useActivityLogsStore } from "../stores/ActivityLogsStore.js";
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
import LogGrid from "../components/LogGrid.vue";
import NavBar from "../components/NavBar.vue";
import {useAuthStore} from "../stores/AuthStore.js";

const logsStore = useActivityLogsStore();
const authStore = useAuthStore();

const { role } = storeToRefs(authStore);
const { patientLogs, doctorLogs, allLogs } = storeToRefs(logsStore);

onMounted (() => {
    if(role.value === 'patient') {
        logsStore.getPatientLogs();
    }
    if(role.value === 'doctor') {
        logsStore.getDoctorLogs();
    }
    if(role.value === 'admin') {
        logsStore.getAllLogs();
    }

});
</script>
<style scoped>
.container {
    display: flex;
    justify-content: left;
    width: 100%;
}
.log-container {
    display: flex;
    justify-content: space-between;
    gap: 50px;
    flex-direction: row;
    background-color: #0a53be;
    border-radius: 14px;
    width: 50%;
}
.log-headings {

}
</style>
