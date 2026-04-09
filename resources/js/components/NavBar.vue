<template>
   <nav class="nav-bar">
       <div class="logo">
           <img :src="whitePill" class="logo" alt="logo" @click="returnHome"/>
       </div>
       <div class="nav-links">
           <button v-if="role === 'admin'" class="btn-create-account" type="button" @click="moveToRegister"> Create New User</button>
           <router-link v-if="role === 'doctor'" to="doctors-patients" class="my-patients">My Patients</router-link>
           <button class="btn-logout" type="button" @click="logoutUser">Logout</button>
       </div>
   </nav>
</template>
<script setup>
import whitePill from "../assets/whitePill.png"
import {useAuthStore} from "../stores/AuthStore.js";
import router from "../router/index.js";
import {storeToRefs} from "pinia";
import { RouterLink } from 'vue-router';

const authStore = useAuthStore();

const { role } = storeToRefs(authStore);
async function moveToRegister() {
    await router.push('/register-select');
}

async function returnHome() {
    await router.push('/home');
}

async function logoutUser() {
    await authStore.logout();
    await router.push('/login')
}

async function moveToMyPatients() {
    await router.push('/');
}
</script>
<style scoped>
.nav-bar {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding-left: 30px;
    padding-right: 30px;
    height: 90px;
    background-color: #305cde;
}
.nav-links {
    display: flex;
    align-items: center;
    gap: 30px;
}
.btn-create-account {
  height: 50px;
    color: #000000;
    background-color: #FAF9F6;
    border-radius: 14px;
    font-size: 16px;
    gap: 10px;
    margin: 10px;
    border: none;
    cursor: pointer;
    padding: 0 15px;
}

.btn-create-account:hover {
    background-color: #E9DCC9;
}

.btn-logout {
    height: 50px;
    color: #000000;
    background-color: #FAF9F6;
    border-radius: 14px;
    font-size: 16px;
    gap: 10px;
    margin: 10px;
    border: none;
    cursor: pointer;
    padding: 0 15px;
}

.btn-logout:hover {
    background-color: #E9DCC9;
}
.my-patients {
    font-size: 18px;

    color: #FFFFFF;
}
.logo {
    height: 70px;
    cursor: pointer;
}
</style>


