<template>
    <div class="modal-overlay">
        <div class="info-container">
            <h2 class="header">Patient Card</h2>
         <div class="row">
             <span class="field">Name: {{ props.patient.user.name }}</span>
         </div>
            <div class="row">
                <span class="field">Email: {{ props.patient.user.email }}</span>
            </div>
            <div class="row">
                <span class="field">Contact Number: {{ props.patient.user.contact_number }}</span>
            </div>
            <div class="row">
                <span class="field">Emergency Contact: {{ props.patient.emergency_contact }}</span>
            </div>
            <div class="row">
                <span class="field">Last Appointment: {{}}</span>
            </div>
        </div>
    </div>

</template>
<script setup>
import { ref, reactive, computed } from 'vue';

const props = defineProps({
    patient: {
        type: Object,
    },
});

const emit = defineEmits(['close'])
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
.info-container {
    width: 440px;
    height: 600px;
    max-width: 90%;
    background: #305cde;
    border-radius: 14px;
    border: 1px solid #FFFFFF;
}
.header {
    color: #FFFFFF;
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
}
.row {
    display: flex;
    justify-content: left;
    margin-top: 35px;
}
.field {
    font-size: 20px;
    color: #FFFFFF;
    margin-left: 10px;
}
</style>
