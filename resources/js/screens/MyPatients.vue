<template>
<NavBar></NavBar>
    <div class="container">
        <h2>My Patients</h2>
        <div class="patients-info">

        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import { onMounted } from 'vue';
import NavBar from "../components/NavBar.vue";
import { useAuthStore } from "../stores/AuthStore.js";
</script>
<style scoped>
.container {
    display: flex;
    margin: auto;
    margin-left: 40px;
}
.patients-info {

}
</style>
