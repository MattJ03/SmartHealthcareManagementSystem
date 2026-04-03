<template>
    <div class="container">
        <div class="log-rec">
            <p class="log-details"> {{ logs.description }}</p>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from 'vue';

const props = defineProps({
    logs: {
        type: Object,
        required: true,
    },
});
</script>
<style scoped>
.container {
    background-color: #FFFFFF;
    width: 75%;
    padding-left: 40px;
}
.log-rec {
    display: flex;
    justify-content: left;
    border: 1px solid #FFFFFF;
    background-color: #0a53be;
    padding-left: 20px;
    border-radius: 14px;
}
.log-details {
    font-size: 16px;
}
</style>
