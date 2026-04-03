<template>
    <NavBar></NavBar>
    <div class="container">
    <LogGrid
     v-if="role === 'patient'"
     v-for="log in patientLogs"
     :key="log.id"
     :logs="log"
    />
    <LogGrid
    v-if="role === 'doctor'"
    v-for="log in doctorLogs"
    :key="log.id"
    :logs="log"
    />
    <LogGrid
    v-if="role === 'admin'"
    v-for="log in allLogs"
    :key="log.id"
    :logs="log"
        />
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
    flex-direction: column;
}
</style>
