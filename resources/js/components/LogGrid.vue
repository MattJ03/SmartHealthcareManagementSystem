<template>
    <div class="container">
        <div class="log-rec">
            <p class="log-details"> {{ formattedTime }}</p>

            <p class="log-action" :class="actionClass"> {{ logs.action }}</p>

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

const actionClass = computed(() => {
    switch (props.logs.action) {
        case 'appointment_booked':
            return 'action-booked';
        case 'appointment_deleted':
            return 'action-cancelled';
        case 'appointment_updated':
            return 'action-updated';
        case 'store_medical_record':
            return 'action-record';
        case 'download_medical_record':
            return 'action-download';
            default:
            return 'action-default';
    }
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
.log-details-action {
    height: 60px;
   width: 90px;
    background-color: #4e9a06;
    border-radius: 18px;
}
.horizontal-line {
   grid-column: span 3;
}
.log-action {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 16px;
    display: inline-block;
    width: fit-content;
}

.action-booked {
    background-color: #4e9a06;
    color: white;
}

.action-cancelled {
    background-color: #cc0000;
    color: white;
}

.action-record {
    background-color: #3465a4;
    color: white;
}

.action-default {
    background-color: #888;
    color: white;
}

.action-download {
    background-color: #0f5132;
    color: white;
}
.action-updated {
    background-color: #e6af05;
    color: #FFFFFF;
}
</style>
