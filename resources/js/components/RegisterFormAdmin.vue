<template>
    <div class="container">
        <form @submit.prevent="submit" class="form">
            <div class="img-wrapper">
                <img :src="pill" class="img" />
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
                    <input type="text" v-model="form.contact_number" class="credentials" />
                </div>
            </div>
        </form>
    </div>
</template>
<script setup>
import { ref, reactive, computed} from "vue";
import pill from '../assets/pill.PNG'

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    contact_number: '',
})

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
.labels {
    font-size: 18px;
    gap: 10px;
    display: block;
    height: 44px;
    justify-content: left;
}
.credentials {
    background-color: #E9DCC9;
    height: 40px;
    width: 100%;
    margin-bottom: 30px;
    border-radius: 12px;
    font-size: 20px;
}
.password-confirmation {
    background-color: #E9DCC9;
    height: 40px;
    width: 100%;
    margin-bottom: 30px;
    border-radius: 12px;
    font-size: 20px;
}
</style>
