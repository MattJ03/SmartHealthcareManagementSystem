<template>
<div class="page">
    <div class="container">
        <form @submit.prevent class="form">
       <label class="labels">Email</label>
        <input class="credentials" type="email" />
            <label class="labels">Password</label>
            <input class="credentials" type="password"/>
        </form>
    </div>
</div>
</template>
<script setup>
</script>
<style scoped>
.page {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
}

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
</style>
