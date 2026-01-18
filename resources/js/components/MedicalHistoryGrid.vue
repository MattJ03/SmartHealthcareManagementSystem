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
            <div class="time-wrapper">
            <img :src="user" alt="peopleIcon" class="cal-image" />
            <p class="time"> Dr. {{ medicalRecord.doctor?.name }}</p>
            </div>
        </div>
        <hr class="divider" />
           <div class="button-row">
               <button class="view-button">
                   <img :src="eye" /> View
               </button>
               <button class="download-button">
                   <img :src="download" />
               </button>
           </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import file from '../assets/file.png';
import { useFormattedDate } from "../composobles/useFormattedDate.js";
import calenderIcon from '../assets/calendar.png';
import user from '../assets/user.png';
import eye from '../assets/eye.png';
import download from '../assets/download.png';


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
    color: rgba(55, 64, 166, 1);
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
    height: 25px;
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
    opacity: 70%;
    display: flex;
    align-items: center;
    gap: 9px;
}
.cal-image {
    height: 18px;
}

.divider {
    margin-top: 25px;
    border-top: 1px solid rgba(0, 0, 0, 0.01);
    width: 100%;
}
.button-row {
    display: flex;
    margin-top: 15px;
    justify-content: space-between;
}
.view-button {
    width: 83%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #4856f2;
    border-radius: 14px;
    padding-top: 10px;
    padding-bottom: 10px;
    color: #FFFFFF;
    gap: 6px;
    font-size: 18px;

}
.view-button img {
    height: 28px;

}
.download-button {
    display: flex;
    justify-content: center;
     align-items: center;
    background-color: #FFFFFF;
    border-radius: 14px;
    padding-right: 20px;
    padding-left: 20px;
    border: 2px solid #4856f2;
}
.download-button img {
    height: 22px;
}
</style>
