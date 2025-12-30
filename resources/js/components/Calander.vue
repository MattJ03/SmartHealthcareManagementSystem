<template>
    <NavBar></NavBar>

    <div class="calendar-container">
       <h1 class="book-appointment">Book Appointment</h1>
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

        <div v-if="showBookingForm" class="modal-overlay">
            <div class="modal-content">
                <h2>Book Appointment - {{ selectedDateString }}</h2>

                <div v-if="availabilityStore.loading">Loading slots...</div>
                <div v-else>
                    <div v-if="availabilityStore.slots.length === 0">No available slots</div>
                    <ul class="slot-list">
                        <li v-for="slot in availabilityStore.slots" :key="slot">
                            <button @click="selectSlot(slot)">{{ slot }}</button>
                        </li>
                    </ul>
                </div>

                <div class="modal-actions">
                    <button @click="confirmBooking">Confirm</button>
                    <button @click="closeModal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, computed, onMounted} from 'vue';
import {useAvailabilityStore} from "../stores/AvailabilityStore.js";
import api from "../axios.js";
import NavBar from "./NavBar.vue";


const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());

const availabilityStore = useAvailabilityStore();
const showBookingForm = ref(false);
const selectedDate = ref(null);
const bookingTime = ref(null);
const doctorId = ref(null);

function selectDate(day) {
    selectedDate.value = new Date(currentYear.value, currentMonth.value, day);
    showBookingForm.value = true;
    if (doctorId.value) {
        const dateParam = selectedDate.value.toISOString().split('T')[0]; // YYYY-MM-DD
        availabilityStore.getAvailableSlots(doctorId.value, dateParam);
    }
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

onMounted(async () => {
    const res = await api.get('/patient/doctor');
    doctorId.value = res.data.doctorId;
    console.log(doctorId.value);
});

function selectSlot(slot) {
    bookingTime.value = slot;
}

function closeModal() {
    showBookingForm.value = false;
    bookingTime.value = null;
}

const selectedDateString = computed(() => {
    if(!selectedDate.value)  return '';
    return selectedDate.value.toLocaleDateString();
    });

async function confirmBooking() {
    if (!bookingTime.value) return alert("Select a slot");

    const startsAt = new Date(selectedDate.value);
    const [hours, minutes] = bookingTime.value.split(':').map(Number);
    startsAt.setHours(hours);
    startsAt.setMinutes(minutes);

    const endsAt = new Date(startsAt.getTime() + 30 * 60 * 1000); // 30 min appointment

    try {
        await api.post('/appointments', {
            doctor_id: doctorId.value,
            starts_at: startsAt.toISOString(),
            ends_at: endsAt.toISOString(),
            status: 'pending',
        });
        alert('Appointment booked!');
        showBookingForm.value = false;
        bookingTime.value = null;
    } catch (err) {
        console.error(err);
        alert('Failed to book appointment');
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

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    width: 400px;
    max-width: 90%;
    border: 2px solid #FFFFFF;
}

.slot-list {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.slot-list li button {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #C0392B;
    cursor: pointer;
    background: #fff;
    transition: background 0.2s;
}

.slot-list li.selected button {
    background: #C0392B;
    color: #fff;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;

}

.modal-actions button {
    padding: 10px 15px;
    border: 1px solid #FFFFFF;
    border-radius: 14px;
    color: #FFFFFF;
    background-color: #C0392B;
    cursor: pointer;
    font-size: 16px;
}
.modal-actions button:hover {
    background-color: #8B0000;
}
.book-appointment {
    color: #FFFFFF;
}
</style>

