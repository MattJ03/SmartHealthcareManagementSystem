<template>
    <div class="container">
        <div class="log-rec">
            <p class="log-details"> {{ formattedTime }}</p>
            <p class="log-details"> {{ logs.action }}</p>
            <p class="log-details"> {{ logs.description }}</p>
            <div class="horizontal-line">
                <hr>
            </div>
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

const formattedTime = computed(() => {
    return new Date(props.logs.created_at).toLocaleDateString('en-GB', {
        day: "2-digit",
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
});
</script>
<style scoped>
.container {
    background-color: #FFFFFF;
    width: 100%;
    padding-left: 20px;
    box-sizing: border-box;
}
.log-rec {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr;
    border: 1px solid #FFFFFF;
    background-color: #FFFFFF;
    border-radius: 14px;
}
.log-details {
    font-size: 16px;
    padding-left: 10px;
    padding-right: 10px;
}
.horizontal-line {
   grid-column: span 3;
}
</style>
