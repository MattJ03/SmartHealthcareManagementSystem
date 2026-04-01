<template>
    <div class="container">
        <div class="log-rec">
            <p class="log-details"> {{ logs.data.logs.data.description }}</p>
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
    width: 100%;
}
.log-rec {
    display: flex;
    justify-content: left;
    border: 1px solid #FFFFFF;
    border-radius: 14px;
}
.log-details {
    font-size: 16px;
}
</style>
