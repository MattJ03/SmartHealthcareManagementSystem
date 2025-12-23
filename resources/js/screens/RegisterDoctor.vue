<template>
    <div class="page">
        <div class="left-half"></div>
        <div class="right-half"></div>
        <div class="card">
            <RegisterFormDoctor @submit="registerDoctor"></RegisterFormDoctor>
        </div>

    </div>
</template>
<script setup>
import RegisterFormDoctor from "../components/RegisterFormDoctor.vue";
import { useAuthStore } from "../stores/AuthStore.js";
import router from "../router/index.js";

const store = useAuthStore();

async function registerDoctor(payload) {
    await store.doctorRegister(payload);
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
.card {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 500px;
    width: auto;
    padding: 2rem;
    border-radius: 14px;
    background-color: #F2F0EF;
    height: auto;
    max-height: fit-content;
}
</style>
