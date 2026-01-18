<template>
    <div class="grid">
        <div class="top-of-grid">
            <div class="img-wrapper">
            <img :src="file" alt="fileImage" class="img" />
            </div>
            <div class="type-wrapper">
            <p class="title-report">Title Report</p>
        </div>
        </div>
        <div class="info-record">
           <p class="time"> {{ medicalRecord.created_at }}</p>
            <p class="time"> {{ medicalRecord.doctor }}</p>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import file from '../assets/file.png';

const props = defineProps({
    medicalRecord: {
        type: Object,
        required: true,
    },
});

</script>
<style scoped>
.grid {
    display: flex;
    border: 1px solid #000000;
    border-radius: 14px;
    width: 550px;
    height: 300px;
    padding: 35px 35px;

}
.top-of-grid {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    width: 100%;

}
.img-wrapper {
    height: 70px;
    background-color: rgba(55, 64, 166, 0.2);
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 14px;
    width: 70px;
}
.img {
    height: 42px;

}
.title-report {

}
.type-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 35px;
    background-color: rgba(55, 64, 166, 0.2);
    border-radius: 14px;
    text-align: center;
    padding: 3px 10px;
}
.info-record {
    display: flex;
    flex-direction: row;

}
.time {
    font-size: 18px;

}
</style>
