<template>
    <div class="container">
        <div class="log-rec">
            <p class="log-details"> {{ formattedTime }}</p>
            <p class="log-details"> {{ logs.action }}</p>
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

const date = new Date();
const formattedTime = date.toLocaleDateString(props.created_at)
</script>
<style scoped>
.container {
    background-color: #FFFFFF;
    width: 60%;
    padding-left: 40px;
}
.log-rec {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr;
    border: 1px solid #FFFFFF;
    background-color: #0a53be;

    border-radius: 14px;
}
.log-details {
    font-size: 16px;
    padding-left: 10px;
    padding-right: 10px;
}
</style>
