<template>
    <div class="container">

        <form @submit.prevent="submit" class="form">
            <div class="logo-wrapper" >
            <img class="logo" :src="pill" alt="logo" />
            </div>
                <label class="labels">Email</label>
            <input v-model="email" type="email" class="credentials" />
                <label class="labels">Password</label>
            <input v-model="password" class="credentials" type="password"/>
            <button type="submit" class="btn-reg">Log In</button>
        </form>
    </div>

</template>
<script setup>
import pill from '../assets/pill.PNG';
import { ref } from 'vue';

const emit = defineEmits(['submit']);

const email = ref('');
const password = ref('');

async function submit() {
    emit('submit', {email: email.value, password: password.value});
}

</script>
<style scoped>

.container {
    height: 340px;
    width: 350px;
    max-width: 500px;
    background: #F2F0EF;
    padding: 2rem;
    border-radius: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.form {
    display: flex;
    width: 100%;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;

}
.credentials {
    background: #E9DCC9;
    height: 45px;
    width: 90%;
    margin-bottom: 30px;
    border-radius: 12px;
    font-size: 20px;

}
.labels {
    gap: 10px;
    display: block;
    justify-content: left;
    font-size: 18px;

}
.logo-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
}
.logo {
   height: 70px;
    width: auto;
    margin-bottom: 1rem;
}
.btn-reg{
    width: 60%;
    align-self: center;
    height: 45px;
    background: #C0392B;
    font-size: 20px;
    border-radius: 14px;
    color: #FFFFFF;
}
.btn-reg:hover {
    background: #8B0000;
}
</style>
