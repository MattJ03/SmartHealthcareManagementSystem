<template>
    <div class="container">

        <form @submit.prevent="submit" class="form">
            <div class="logo-wrapper">
                <img :src="pill" class="logo" alt="logo" />
            </div>
            <label class="credentials"></label>

        </form>
    </div>

</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import pill from '../assets/pill.PNG';



</script>
<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 400px;
    background-color: #F2F0EF;
    width: 350px;
    max-width: 500px;
    padding: 2rem;
    border-radius: 14px;
}

.form {
    display: flex;
    width: 100%;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
}

.logo-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
}
.logo {
    height: 70px;
}
</style>
