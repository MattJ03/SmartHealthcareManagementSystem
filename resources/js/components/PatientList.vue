<template>
    <div class="container">
        <div class="patient-info-container">
            <p class="patient-name"> {{ props.patient.user.name }}</p>
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


</script>
<style scoped>
.container {
    display: flex;
}
.patient-info-container {

}
</style>
