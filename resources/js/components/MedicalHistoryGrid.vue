<template>
        <div class="record-grid" @click="handleClick">
            <p class="title"> {{ medicalRecord.title }}</p>
            <img :src="openFile" alt="openFile" class="openFile" />

        </div>

</template>
<script setup>
import { ref, reactive, computed } from "vue";
import openFile from '../assets/openFile.png';


const emit = defineEmits(['open']);

const props = defineProps({
    medicalRecord: {
        type: Object,
        required: true,
    },
});

const handleClick = () => {
    emit('open', props.medicalRecord);
}

</script>
<style scoped>
.record-grid {
    display: flex;
    flex-direction: column;
    justify-content: space-between;

    background-color: #C0392B;
    border-radius: 14px;
    padding: 12px;
    min-height: 100px;
    border: 1px solid #C0392B;

    cursor: pointer;
}

.title {
    font-size: 18px;
    font-weight: 600;
    color: #fff;
}


.openFile {
    height: 40px;
    align-items: center;
    width: 50%;
    margin: auto;
}
</style>
