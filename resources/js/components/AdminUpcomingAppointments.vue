<template>
    <div class="container">
        <div class="appointment-card">
             <span>hey</span>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { useFormattedAppointment } from "../composobles/useFormattedAppointment.js";

const props = defineProps({
    appointment: {
        type: Object,
        required: true,
    },
});


</script>
<style scoped>
.container {
    background-color: #FFFFFF;
    width: 100%;
}
.appointment-card {
    display: flex;
    padding-left: 15px;
    justify-content: left;
    flex-direction: row;
}
</style>
