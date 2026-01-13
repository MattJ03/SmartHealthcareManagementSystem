<template>
        <div class="record-grid">
            <img :src="openFile" alt="openFile" class="openFile" />

        </div>

</template>
<script setup>
import { ref, reactive, computed } from "vue";
import openFile from '../assets/openFile.png';


const emit = defineEmits(['open']);


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

    cursor: pointer;
}

.title {
    font-size: 18px;
    font-weight: 600;
    color: #fff;
}


.openFile {
    height: 40px;
    align-self: flex-end;
}
</style>
