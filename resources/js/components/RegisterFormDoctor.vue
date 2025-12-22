<template>
<div class="container">
    <form @submit.prevent="submit" class="form" >
        <div class="img-wrapper">
            <img :src="pill" class="img" />
        </div>
        <div class="row">
            <div class="field">
                <label class="label">Name</label>
                <input type="text" v-model="form.name" class="credentials" />
            </div>
            <div class="field">
                <label class="label">Email</label>
                <input type="email" v-model="form.email" class="credentials" />
            </div>
        </div>
        <div class="row">
            <div class="field">
                <label class="label">Password</label>
                <input type="password" v-model="form.password" class="credentials" />
            </div>
            <div class="field">
                <label class="label">Password Confirmation</label>
                <input type="password" v-model="form.confirm_password" class="credentials" />
            </div>
        </div>
        <div class="row">
            <div class="field">
                <label class="label">Contact Number</label>
                <input type="text" v-model="form.contact_number" class="credentials" />
            </div>
            <div class="field">
                <label class="label">Speciality</label>
                <input type="text" v-model="form.speciality" class="credentials" />
            </div>
        </div>
        <div class="row">
            <div class="field">
                <label class="label">License Number</label>
                <input type="text" v-model="form.license_number" class="credentials" />
            </div>
        </div>
        <button type="submit" class="btn-reg">Register</button>

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
   monday: { enabled: false, start: '', end: '' },
   tuesday: { enabled: false, start: '', end: '' },
   wednesday: { enabled: false, start: '', end: '' },
   thursday: { enabled: false, start: '', end: '' },
   friday: { enabled: false, start: '', end: '' },
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
    flex-direction: column;
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
    margin-bottom: 30px;
    border-radius: 14px;
    font-size: 18px;
    max-width: 100%;
}
.btn-reg {
    display: flex;
    justify-content: center;
    align-items: center;
    align-self: center;
    height: 50px;
    width: 40%;
    border-radius: 14px;
    background-color: #C0392B;
    font-size: 20px;
    color: #FFFFFF;
}
</style>
