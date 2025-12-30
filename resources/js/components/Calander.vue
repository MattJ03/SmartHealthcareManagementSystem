<template>
    <div class="calendar-container">

        <div class="month-nav">
            <button @click="prevMonth">&lt;</button>
            <span class="month-name">{{ monthName }} {{ currentYear }}</span>
            <button @click="nextMonth">&gt;</button>
        </div>


        <div class="weekdays">
            <div v-for="day in weekdays" :key="day">{{ day }}</div>
        </div>


        <div class="calendar-grid">
            <div
                v-for="(day, index) in daysInMonth"
                :key="index"
                class="day-cell"
                @click="selectDate(day)"
            >
                {{ day }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// Current month/year
const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());

const showBookingForm = ref(false);
const selectedDate = ref(null);
const bookingTime = ref(null);

 function selectDate(day) {
     selectedDate.value = new Date(
         currentYear.value,
         currentMonth.value,
         day,
     );
     showBookingForm.value = true;
 }


const weekdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];


const daysInMonth = computed(() => {
    const date = new Date(currentYear.value, currentMonth.value + 1, 0);
    const days = [];
    for(let i = 1; i <= date.getDate(); i++){
        days.push(i);
    }
    return days;
});


const monthName = computed(() => new Date(currentYear.value, currentMonth.value).toLocaleString('default', { month: 'long' }));


function prevMonth() {
    currentMonth.value--;
    if(currentMonth.value < 0){
        currentMonth.value = 11;
        currentYear.value--;
    }
}
function nextMonth() {
    currentMonth.value++;
    if(currentMonth.value > 11){
        currentMonth.value = 0;
        currentYear.value++;
    }
}
</script>

<style scoped>
.calendar-container {
    width: 1500px;
    background-color: #C0392B;
    border-radius: 12px;
    padding: 20px;
    margin: 40px auto;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.month-nav {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
}

.month-nav button {
    padding: 5px 12px;
    cursor: pointer;
    font-size: 18px;
}

.month-name {
    font-weight: bold;
    font-size: 22px;
    color: #FFFFFF;
}

.weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    font-weight: bold;
    margin-bottom: 10px;
    color: #FFFFFF;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}

.day-cell {
    aspect-ratio: 1 / 1;
    background-color: #f0f0f0;
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s;
}

.day-cell:hover {
    background-color: #d0eaff;
}
</style>

