<template>
    <div class="container">
        <form @submit.prevent class="form">
            <img class="logo" :src="pill" alt="logo" />
       <label class="labels">Email</label>
        <input class="credentials" type="email" />
            <label class="labels">Password</label>
            <input class="credentials" type="password"/>
        </form>
    </div>

</template>
<script setup>
import pill from '../assets/pill.PNG';
</script>
<style scoped>

.container {
    height: 340px;
    width: 350px;
    max-width: 500px;
    background: #F2F0EF;
    padding: 2rem;
    border-radius: 14px;
}
.form {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: center;
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
    display: flex;
    justify-content: left;
    align-items: center;
}
.logo {
    height: 70px;

}
</style>
