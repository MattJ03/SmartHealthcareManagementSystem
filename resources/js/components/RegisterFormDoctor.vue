<template>
<div class="container">
    <form @submit.prevent="submit" class="form" >
        <div class="img-wrapper">
            <img :src="pill" class="img" />
        </div>
        <div class="row">
            <div class="field">
                <label class="label">Name</label>
                <input v-model=""
            </div>
        </div>
    </form>

</div>
</template>
<script setup>
import { ref, reactive, computed } from "vue";
import pill from '../assets/pill.PNG';

const form = reactive({
    name: '',
    email: '',
    password: '',
    confirm_password: '',
    contact_number: '',
    speciality: '',
    license_number: '',
});

const days = [
    'monday',
    'tuesday',
    'wednesday',
    'thursday',
    'friday',
];

const clinicHours = reactive({
   monday: { start: '', end: '' },
   tuesday: { start: '', end: '' },
   wednesday: { start: '', end: '' },
   thursday: { start: '', end: '' },
   friday: { start: '', end: '' },
});
</script>
<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
    border: none;
    border-radius: 14px;
    height: auto;
    min-height: 400px;
    width: 350px;
    max-width: 800px;
    background-color: #F2F0EF;
}
.form {
    display: flex;
    justify-content: center;
    width: 100%;
    align-items: flex-start;
    gap: 8px;
}
.row {
    display: flex;
    gap: 1rem;
    width: 100%;
}
.field {
    display: flex;
    flex-direction: column;
}
.img-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
}
.img {
    height: 70px;
}
.label {
    display: block;
    flex-direction: column;
    font-size: 18px;
    height: 44px;
    width: 100%;
    justify-content: left;
}
.credentials {
    background-color: #E9DCC9;
    height: 40px;
    width: 100%;
    margin-bottom: 14px;
    border-radius: 14px;
    font-size: 18px;
}
</style>
