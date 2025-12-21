<template>
    <div class="container">
        <form @submit.prevent="submit" class="form">
            <div class="logo-wrapper">
                <img :src="pill" class="logo" alt="logo" />
            </div>
            <div class="row">
                <div class="field">
                    <label class="labels">Name</label>
                    <input type="text" v-model="form.name" class="credentials" />
                </div>
                <div class="field">
                    <label class="labels">Email</label>
                    <input type="email" v-model="form.email" class="credentials" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <label class="labels">Password</label>
                    <input type="password" v-model="form.password" class="credentials" />
                </div>
                <div class="field">
                    <label class="labels">Password Confirmation</label>
                    <input type="password" v-model="form.password_confirmation" class="credentials" />
                </div>
            </div>
            <div class="row">
                <div class="field">
                    <label class="labels">Contact Number</label>
                    <input type="number" v-model="form.contact_number" class="credentials" />
                </div>
                <div class="field">
                    <label class="labels">Emergency Contact</label>
                    <input type="number" v-model="form.emergency_contact" class="credentials" />
                </div>
            </div>
            <button class="btn-reg" type="submit">Register</button>
        </form>
    </div>

</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import pill from '../assets/pill.PNG';

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    contact_number: '',
    emergency_contact: '',

})
const emit = defineEmits();

async function submit() {
    emit('submit', {...form});
}

</script>
<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
    height: auto;
    background-color: #F2F0EF;
    width: 350px;
    max-width: 800px;

    border-radius: 14px;
}

.form {
    display: flex;
    width: 100%;
    flex-direction: column;
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

.logo-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
}
.logo {
    height: 70px;
}
.labels{
    font-size: 18px;
    gap: 10px;
    display: block;
    justify-content: left;

}
.credentials {
    background: #E9DCC9;
    height: 45px;
    width: 100%;
    margin-bottom: 30px;
    border-radius: 12px;
    font-size: 20px;

}
.btn-reg{
    width: 40%;
    align-self: center;
    height: 50px;
    background: #C0392B;
    font-size: 20px;
    border-radius: 14px;
    color: #FFFFFF;
}
.btn-reg:hover {
    background: #8B0000;
}
</style>
