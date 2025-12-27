<template>
    <NavBar></NavBar>
    <div class="welcome-container">
        <h1 class="welcome-name"> Welcome back {{ name }}</h1>
    </div>


</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed } from 'vue';
import router from "../router/index.js";
import { useAuthStore } from "../stores/AuthStore.js";
import { storeToRefs } from "pinia";

const store = useAuthStore();

const { name } = storeToRefs(store);


</script>
<style style>
.welcome-container {
   padding-left: 30px;

    padding-top: 15px;
}
.welcome-name {
    color: #000000;
}
</style>
