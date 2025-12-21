<template>
    <div class="container">
        <form @submit="submit" class="form">
            <div class="img-wrapper">
                <img :src="pill" class="img" alt="logo"/>
                <div class="row">
                    <div class="field">
                        <label class="labels">Name</label>
                        <input type="text" v-model="form.name" class="credentials" />
                    </div>
                    <div class="field">
                        <label class="labels">Email</label>
                        <input type="email" class="credentials" v-model="form.email" />
                    </div>
                </div>
                <div class="row">
                    <div class="field">
                        <label class="labels">Password</label>
                        <input type="password" class="credentials" v-model="form.password" />
                    </div>
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
    gap: 2rem;
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
</style>
