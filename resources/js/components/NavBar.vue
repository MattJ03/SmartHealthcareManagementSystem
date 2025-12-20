<template>
   <nav class="nav-bar">
       <div class="logo">
           <img :src="pill" class="logo" alt="logo" />
       </div>
       <div class="nav-links">
           <button v-if="authStore.isAdmin" class="btn-create-account" type="button" @click="moveToRegister"> Create New User</button>
       </div>
   </nav>
</template>
<script setup>
import pill from '../assets/pill.PNG';
import {useAuthStore} from "../stores/AuthStore.js";
import router from "../router/index.js";

async function moveToRegister() {
    await router.push('/register');
}
const authStore = useAuthStore();
</script>
<style scoped>
.nav-bar {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    padding: 2px;
    align-items: center;
    gap: 1rem;
    height: 90px;
    background-color: #FFFFFF;
}
.btn-create-account {
  height: 50px;
    color: #FFFFFF;
    background-color: #C0392B;
    border-radius: 14px;
    font-size: 16px;
}
.logo {
    height: 70px;

}
</style>


