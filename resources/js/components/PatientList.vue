<template>
    <div class="container">
        <div class="patient-info-container">
            <p class="patient-name">{{ props.patient.user.name }}</p>
            <button class="view-details" @click="$emit('open', patient)">Info</button>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from 'vue';

const props = defineProps({
    patient: {
        type: Object,
        required: true,
    },
});
const increment = ref(1);

const emit = (['open']);



const showModal = false;

</script>
<style scoped>
.container {
    display: flex;
    width: 100%;

}
.patient-info-container {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    width: 100%;
   background-color: #FFFFFF;
    padding: 0;
    border-radius: 14px;
    padding-left: 18px;
    padding-top: 8px;
    padding-right: 18px;
    padding-bottom: 8px;
}
.patient-name {
    font-size: 18px;
}
.view-details {
    padding-bottom: 12px;
    padding-top: 12px;
    padding-left: 22px;
    padding-right: 22px;
    background-color: #C0392B;
    border-radius: 14px;
    font-size: 16px;
    background-color: #305cde;
    color: #FFFFFF;
    border: 0;
}
.view-details:hover {
    background-color: #0a58ca;
    cursor: pointer;
    transition: 0.2s;
}
</style>
