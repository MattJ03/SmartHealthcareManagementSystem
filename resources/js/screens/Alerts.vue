<template>
<h1>Make this a log thing for bookings, postings, doctor moves, patient cancel or new patient</h1>
</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import { useActivityLogsStore } from "../stores/ActivityLogsStore.js";
import { storeToRefs } from 'pinia';

const logsStore = useActivityLogsStore();



</script>
<style scoped>
</style>
