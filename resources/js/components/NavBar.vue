<template>
   <nav class="nav-bar">
       <div class="logo">
           <img :src="whitePill" class="logo" alt="logo" @click="returnHome"/>
       </div>
       <div class="nav-links">
           <button v-if="authStore.isAdmin" class="btn-create-account" type="button" @click="moveToRegister"> Create New User</button>
           <button class="btn-create-account" type="button" @click="logoutUser">Logout</button>
       </div>
   </nav>
</template>
<script setup>
import whitePill from "../assets/whitePill.png"
import {useAuthStore} from "../stores/AuthStore.js";
import router from "../router/index.js";

async function moveToRegister() {
    await router.push('/register-select');
}

async function returnHome() {
    await router.push('/home');
}
const authStore = useAuthStore();

async function logoutUser() {
    await authStore.logout();
    await router.push('/login')
}
</script>
<style scoped>
.nav-bar {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    padding: 2px;
    align-items: center;
    gap: 1rem;
    padding-left: 30px;
    padding-right: 30px;
    height: 90px;
    background-color: #C0392B;
}
.btn-create-account {
  height: 50px;
    color: #000000;
    background-color: #FAF9F6;
    border-radius: 14px;
    font-size: 16px;
    gap: 10px;
    border: none;
    cursor: pointer;
}
.logo {
    height: 70px;

}
</style>


