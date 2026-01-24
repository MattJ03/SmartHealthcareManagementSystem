<template xmlns="http://www.w3.org/1999/html">
<NavBar></NavBar>
    <div class="container">
        <div class="top-of-container">
            <div class="patient-details">
                <h2 class="patient-name">Patient: lorem Ipsum</h2>
                <p class="patient-dates">DOB: 04/12/03 | Patient ID: #12345</p>
            </div>
            <div class="search-wrapper">
                <input v-model="search" type="text" name="search" class="search-bar" placeholder="Search patient or title...">
                <button class="cancel-button" type="button" @click="cancelSearch">X</button>
            </div>
        </div>
        <div class="grid-records-container" >
        <div class="attach-container" @click="showUploadModal = true">
            <div class="attach-items">
                <div class="attach-circle">
                    <img :src="attach" alt="attach" />
                </div>
                <h3>Upload new records</h3>
                <p>Add medical documents for this patient</p>
            </div>
        </div>
        <MedicalHistoryGrid
            v-for="record in medicalRecordStores.records"
            :key="record.id"
            :medical-record="record"
            @open="medicalRecordStores.openRecord"
            @download="downloadRecord"
        ></MedicalHistoryGrid>
        </div>
    </div>
    <div v-if="medicalRecordStores.selectedRecord" class="modal-overlay" @click.self="medicalRecordStores.closeRecord()">
        <div class="modal-content">
            <button @click="medicalRecordStores.closeRecord()" class="close-btn">X</button>
           <iframe
               :src="medicalRecordStores.pdfUrl"
               width="100%"
               height="100%" >
               </iframe>
        </div>
    </div>
    <div
        v-if="showUploadModal"
        class="modal-overlay"
        @click.self="showUploadModal = false"
    >
        <div class="upload-model-content">
            <h2>Upload Medical Record</h2>
            <form @submit.prevent="submitRecord" class="upload-form">
                <label>Patient</label>
                <select v-model="form.patientId" required >
                    <option disabled value="">Select Patient</option>
                    //create controller method to get all a doctors patient eager loading patients
                </select>
                <label>Title</label>
                <input v-model="form.title" type="text" required />
            </form>
        </div>
    </div>
</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import {ref, reactive, computed, onMounted} from 'vue';
import { watch } from "vue";
import { useFormattedAppointment } from "../composobles/useFormattedAppointment.js";
import { useMedicalRecordStores } from "../stores/MedicalRecordStore.js";
import pill from '../assets/pill.PNG';
import attach from '../assets/attach.svg';
import api from "../axios.js";
import MedicalHistoryGrid from "../components/MedicalHistoryGrid.vue";

const medicalRecordStores = useMedicalRecordStores();

const search = ref('');
const showUploadModal = ref(false);
const form = reactive({
    patientId: '',
    title: '',
    file: null,
});

onMounted(async () => {
    medicalRecordStores.fetchDoctorRecords();
});
 watch(search, (newValue) => {
     medicalRecordStores.fetchDoctorRecords(newValue);
     console.log(newValue);
 });

 const downloadRecord = async (record) => {
     const res = await api.get(`downloadFile/${record.id}/download`, {
         responseType: 'blob',
     });

     const url = window.URL.createObjectURL(new Blob([res.data]));
     const link = document.createElement('a');
     link.href = url;
     link.setAttribute('download', record.title);
     document.body.appendChild(link);
     link.click();
     link.remove();
}

const cancelSearch = async () => {
     search.value = '';

}


</script>
<style scoped>
.attach-container {
    background-color: rgba(72, 86, 242, 0.12);
    border-radius: 14px;
    border: dotted rgba(72, 86, 242, 0.5);;
    width: 30%;
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

.grid-records-container {
    display: flex;
    flex-direction: row;
    gap: 20px;

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
.cancel-button:hover {
    background-color: #8B0000;
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
.modal-content {
    position: relative;
    width: 90%;
    height: 90%;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
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
.close-btn:hover {
    background-color: #8B0000;
}
.show-upload-model-content {
    width: 420px;
    max-width: 90%;
    background: #FFFFFF;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
}
.upload-modal-content h2 {
    margin-top: 0;
    margin-bottom: 16px;
}

.upload-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.upload-form input {
    height: 42px;
    border-radius: 10px;
    border: 1px solid #ccc;
    padding: 0 12px;
    font-size: 15px;
}

.button-row {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
}

button.cancel {
    background: transparent;
    border: none;
    font-size: 15px;
    cursor: pointer;
}

button.submit {
    background-color: #4856f2;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px 16px;
    cursor: pointer;
}
</style>
