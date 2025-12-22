<template>
    <div class="page">
        <div class="left-half"></div>
        <div class="right-half"></div>
        <div class="card">
       <RegisterFormPatient @submit="registerUser"></RegisterFormPatient>
        </div>
        </div>
</template>
<script setup>
import RegisterFormPatient from "../components/RegisterFormPatient.vue";
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed } from "vue";
import { useAuthStore } from "../stores/AuthStore.js";
import router from "../router/index.js";

const authStore = useAuthStore();

async function registerUser(form) {
    await authStore.patientRegister(form);
    await router.push('/home');
}
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
    width: 50%;
    height: 100%;
    background: #C0392B;
}


.right-half {
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: #FFFFFF;
}
.card {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 400px;
    width: auto;
    padding: 2rem;
    border-radius: 14px;
    background: #F2F0EF;

    display: flex;
    flex-direction: column;
    gap: 1rem;


    height: auto;
    max-height: fit-content;
}
</style>
