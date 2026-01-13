<template>
    <div class="container">
        <div class="record-grid">
            <p> {{}}</p>

        </div>

    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";

const props = defineProps({
    appointment: {
        type: Object,
        required: true,
    }
});

const appointmentRef = ref(props.appointment);

</script>
<style scoped>
.container {
    width: 100%;
    background-color: #FFFFFF;
    display: flex;
    justify-content: center;
    flex-direction: row;
}
.record-grid {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    width: 20%;
    border: 1px solid #FFFFFF;
    background-color: #C0392B;
}
</style>
