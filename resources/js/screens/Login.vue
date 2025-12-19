<template>
    <div class="page">
        <div class="card">
            <LoginForm></LoginForm>

        </div>
    </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import { computed } from 'vue';
import {useAuthStore} from "../stores/AuthStore.js";
import LoginForm from "../components/LoginForm.vue";
import regPic from "../assets/regPic.png";
</script>
<style scoped>
.page {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.card {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 900px;
    gap: 3rem;
    padding: 2rem;

}
</style>
