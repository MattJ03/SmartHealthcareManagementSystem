<template>
<div class="container">
    <form @submit.prevent="submit" class="form" >
        <div class="img-wrapper">
            <img :src="pill" class="img" />
        </div>
    </form>

</div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import pill from '../assets/pill.PNG';
</script>
<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
    border: none;
    border-radius: 14px;
    height: auto;
    min-height: 400px;
    width: 350px;
    max-width: 800px;
    background-color: #F2F0EF;
}
.form {
    display: flex;
    justify-content: center;
    width: 100%;
    align-items: flex-start;
    gap: 8px;
}
.img-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
}
.img {
    height: 70px;
}
</style>
