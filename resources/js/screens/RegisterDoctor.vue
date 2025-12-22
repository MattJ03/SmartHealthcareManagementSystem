<template>
    <div class="page">
        <div class="left-half"></div>
        <div class="right-half"></div>

    </div>
</template>
<script setup>
import RegisterFormDoctor from "../components/RegisterFormDoctor.vue";
</script>
<style scoped>
.page {
    position: relative;
    min-height: 100vh;
    width: 100%;
}
.left-half {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 50%;
    background-color: #C0392B;
}
.right-half {
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    width: 50%;
    background-color: #FFFFFF;
}
</style>
