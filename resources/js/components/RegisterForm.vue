<template>
    <div class="container">

        <form @submit.prevent="">
            <div class="img-wrapper">
                <img :src="pill" alt="logo" />
            </div>
        </form>
    </div>

</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import pill from '../assets/pill.PNG';

</script>
<style scoped>
</style>
