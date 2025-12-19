<template>
    <div class="page">
        <div class="left-half"></div>

        <div class="right-half"></div>

        <div class="card">
            <LoginForm />
        </div>
    </div>
</template>

<script setup>
import LoginForm from "../components/LoginForm.vue";
</script>

<style scoped>
.page {
    position: relative;
    min-height: 100vh;
    width: 100%;
}
.left-half {
    position: absolute;
    top: 0;
    left: 0;
    width: 50%;
    height: 100%;
    background: red;
}


.right-half {
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: white;
}

.card {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 400px;
    width: auto;
    padding: 2rem;
    border-radius: 14px;
    background: #F2F0EF;

    /* Flex layout for inner form */
    display: flex;
    flex-direction: column;
    gap: 1rem;

    /* Prevent stretching */
    height: auto; /* do not fill parent */
    max-height: fit-content;
}

</style>
