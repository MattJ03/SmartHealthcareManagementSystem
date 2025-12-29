<template>
    <div class="container">
        <div class="appointment-card">
        <p class="starts-at"> {{ appointment.starts_at }}</p>
        <p class> {{appointment.doctor.name }}</p>
        <p class="starts-at"> {{appointment.starts_at}}</p>
        </div>
    </div>

</template>
<script setup>
import { ref, reactive, computed } from 'vue';

const props = defineProps({
    appointment: {
        type: Object,
        required: true,
    },

});

</script>
<style scoped>
.container {
    width: 85%;
    background-color: #FFFFFF;
}
.appointment-card {
    display: flex;
    justify-content: left;
    background-color: #FFFFFF;
}
.starts-at {
    font-size: 18px;
}
.doctor-name {
    font-size: 16px;
}
</style>
