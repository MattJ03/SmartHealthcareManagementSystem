<template>
    <NavBar></NavBar>
    <div class="container">
     <MedicalHistoryGrid
     v-for="record in recordStore.patientRecords"
     :key="record.id"
     :medical-record="record"
     @open="recordStore.openRecord"
     @download="downloadRecord"
     ></MedicalHistoryGrid>
    </div>

    <div v-if="recordStore.selectedRecord" class="modal-overlay"  @click.self="recordStore.closeRecord()">
        <div class="modal-content">
          <button @click="recordStore.closeRecord" class="close-btn">X</button>
            <iframe
                :src="recordStore.pdfUrl"
                width="100%"
                height="100%"
            ></iframe>
        </div>
    </div>

</template>
<script setup>
import {ref, reactive, computed, onMounted} from 'vue';
import NavBar from "../components/NavBar.vue";
import MedicalHistoryGrid from "../components/MedicalHistoryGrid.vue";
import {useMedicalRecordStores} from "../stores/MedicalRecordStore.js";
import {useAuthStore} from "../stores/AuthStore.js";
import api from "../axios.js";


const recordStore = useMedicalRecordStores();
const authStore = useAuthStore();
const role = localStorage.getItem('role');
const selectedPatientId = ref(null);



console.log('role is: ' + role);

onMounted(async () => {
if(role === 'patient') {
const res = await api.get('/me');
selectedPatientId.value = res.data.profile.id;
console.log(selectedPatientId.value);

await recordStore.fetchPatientRecords(selectedPatientId.value);
    console.log('Fetched records:', recordStore.patientRecords);
}

if (role === 'doctor') {
    const res = await api.get('/me');
    const patients = res.data.patients;

    if (patients.length > 0) {
        selectedPatientId.value = patients[0].id;
        await recordStore.fetchPatientRecords(selectedPatientId.value);
    }
}
});

const downloadRecord = async (record) => {

        const res = api.get(`/downloadFile/${record.id}/download`, {
            responseType: 'blob',
        });
        const url = window.URL.createObjectURL(new Blob([res.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', res.data);
        document.body.appendChild(link);
        link.click();
        link.remove();

}



</script>
<style scoped>
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
.modal-content {
    position: relative;
    width: 90%;
    height: 90%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
}
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #FFFFFF;
    width: 85%;
    height: 400px;
    margin: auto;
    border-radius: 14px;
    border: 1px solid #E9DCC9;
    margin-top: 30px;
    gap: 40px;
}

.close-btn {
    padding-bottom: 15px;
    padding-top: 15px;
    padding-left: 25px;
    padding-right: 25px;
    background-color: #C0392B;
    border-radius: 14px;
    font-size: 16px;
    color: #FFFFFF;
    border: 0;
    margin-top: 10px;
    margin-left: 10px;
    margin-bottom: 10px;
    cursor: pointer;
}
</style>
