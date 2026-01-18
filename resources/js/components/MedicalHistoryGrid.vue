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
            <p class="title"><strong> {{ medicalRecord.title }}</strong></p>
            <div class="time-wrapper">
                <img :src="calenderIcon" alt="calenderImage" class="cal-image" />
            <p class="time"> {{ formatDate(medicalRecord.created_at) }}</p>
            </div>
            <p class="time"> Dr. {{ medicalRecord.doctor?.name }}</p>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import file from '../assets/file.png';
import { useFormattedDate } from "../composobles/useFormattedDate.js";
import calenderIcon from '../assets/calendar.png'

const { formatDate } = useFormattedDate();

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
    flex-direction: column;
    border-radius: 14px;
    width: 550px;

    padding: 20px;

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

.title {
   font-size: 25px;
    margin: 2px;
    padding-top: 25px;
    padding-bottom: 10px;
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
    flex-direction: column;

}
.time {
    font-size: 18px;
    padding-top: 6px;
    margin: 2px;
}
.time-wrapper {
    display: flex;
    align-items: center;
    gap: 7px;
}
.cal-image {
    height: 18px;

}
</style>
