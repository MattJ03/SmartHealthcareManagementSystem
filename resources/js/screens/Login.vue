<template>
    <div class="page">
        <div class="left-half"></div>

        <div class="right-half"></div>

        <div class="card">
            <LoginForm @submit="handleLogin" />
        </div>
    </div>
</template>

<script setup>
import LoginForm from "../components/LoginForm.vue";
import { ref, reactive, computed } from 'vue';
import { useAuthStore } from "../stores/AuthStore.js";
import router from "../router/index.js";

const email = ref('');
const password = ref('');

const authStore = useAuthStore();

async function handleLogin({email, password}) {
    try {
        await authStore.login(email, password);
        await router.push('/home');
    } catch(error) {
       console.log(error);
    }
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

    /* Flex layout for inner form */
    display: flex;
    flex-direction: column;
    gap: 1rem;

    /* Prevent stretching */
    height: auto; /* do not fill parent */
    max-height: fit-content;
}

</style>
