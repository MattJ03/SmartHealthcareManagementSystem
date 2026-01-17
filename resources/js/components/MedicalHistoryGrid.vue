<template>
    <div class="grid">
        <div class="top-of-grid">
            <div class="img-wrapper">
            <img :src="file" alt="fileImage" class="img" />
            </div>
            <p class="title-report">Title Report</p>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import file from '../assets/file.png';

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
.img-wrapper {
    height: 60px;
    background-color: rgba(55, 64, 166, 0.2);
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 14px;

}
.img {
    height: 42px;

}
.title-report {

}
</style>
