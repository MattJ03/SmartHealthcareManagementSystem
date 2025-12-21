<template>
    <div class="page">
        <div class="left-half"></div>
        <div class="right-half"></div>
        <div class="card">
            <RegisterFormAdmin @submit="registerAdmin"></RegisterFormAdmin>
        </div>
    </div>
</template>
<script setup>
import RegisterFormAdmin from "../components/RegisterFormAdmin.vue";
import {useAuthStore} from "../stores/AuthStore.js";
import router from "../router/index.js";

const authStore = useAuthStore();
async function registerAdmin() {
    await authStore.adminRegister(form);
    await router.push('/home');
}
</script>
<style scoped>
.page {
    min-height: 100vh;
    width: 100%;
    position: absolute;
}
.left-half {
    position: absolute;
    top: 0;
    left: 0;
    width: 50%;
    height: 100%;
    background: red;
}


.right-half {
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: white;
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
