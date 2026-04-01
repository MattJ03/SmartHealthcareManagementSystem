<template>
    <div class="container">
        <div class="log-rec">

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
}
</style>
