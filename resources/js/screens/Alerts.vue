<template>
    <NavBar></NavBar>
    <div class="container">
        <div class="log-wrapper">
            <div class="pagination-container">
               <button @click="prevLogs"><</button>
                <button @click="nextLogs">></button>
            </div>

        <div class="log-container">
        <div class="log-headings">
            <h3>Timestamp</h3>
            <h3>Action</h3>
            <h3>Description</h3>
        </div>
            <div class="horizontal-line">
                <hr>
            </div>
            <div class="log-results">
                <LogGrid
                    v-if="role === 'patient'"
                    v-for="log in patientLogs"
                    :id="log.id"
                    :logs="log"
                    />
                <LogGrid
                    v-if="role === 'doctor'"
                    v-for="log in doctorLogs"
                    :id="log.id"
                    :logs="log"
                    />
            </div>
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
    padding-left: 30px;
    padding-top: 20px;

}
.log-container {
    background-color: #FFFFFF;
    border: #E9DCC9 solid 1px;
    border-radius: 14px;

}
.log-headings {
    display: grid;
    padding-left: 20px;
    grid-template-columns: 1fr 1fr 2fr;
    width: 100%;
}

.horizontal-line {
    width: 100%;
    border: none;

    margin: 10px 0;
    padding-left: 0;
}

.log-results {

}
.log-wrapper {
    width: 75%;
}

.pagination-container {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 10px;

}

.next-prev {

}
</style>
