<template>
    <NavBar />

    <div class="calendar-container">
        <h1 class="book-appointment">
            {{ appointment ? "Edit Appointment" : "Book Appointment" }}
        </h1>

        <!-- Month navigation -->
        <div class="month-nav">
            <button @click="prevMonth">&lt;</button>
            <span class="month-name">{{ monthName }} {{ currentYear }}</span>
            <button @click="nextMonth">&gt;</button>
        </div>

        <!-- Weekdays header -->
        <div class="weekdays">
            <div v-for="day in weekdays" :key="day">{{ day }}</div>
        </div>

        <!-- Calendar grid -->
        <div class="calendar-grid">
            <div
                v-for="(date, index) in daysInMonth"
                :key="index"
                class="day-cell"
                :class="{ empty: !date }"
                @click="date && selectDate(date)"
            >
                {{ date ? date.getDate() : '' }}
            </div>
        </div>

        <!-- Booking modal -->
        <div v-if="showBookingForm" class="modal-overlay">
            <div class="modal-content">
                <h2>
                    {{ appointment ? "Edit Appointment" : "Book Appointment" }} -
                    {{ selectedDateString }}
                </h2>

                <div v-if="availabilityStore.loading">Loading slots...</div>
                <div v-else>
                    <div v-if="availabilityStore.slots.length === 0">No available slots</div>

                    <ul class="slot-list">
                        <li v-for="slot in allPossibleSlots" :key="slot">
                            <button
                                @click="selectSlot(slot)"
                                :disabled="!availabilityStore.slots.includes(slot)"
                                :class="{
                  'unavailable-slot': !availabilityStore.slots.includes(slot),
                  selected: bookingTime === slot
                }"
                            >
                                {{ slot }}
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="modal-actions">
                    <button @click="confirmBooking">
                        {{ appointment ? "Update" : "Confirm" }}
                    </button>
                    <button @click="closeModal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import NavBar from './NavBar.vue';
import { useAvailabilityStore } from '../stores/AvailabilityStore.js';
import { useAppointmentStore } from '../stores/AppointmentStore.js';
import api from '../axios.js';

// Props for edit mode
const props = defineProps({
    appointment: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['submit']);

// Reactive state
const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());

const availabilityStore = useAvailabilityStore();
const appointmentStore = useAppointmentStore();

const selectedDate = ref(null);
const bookingTime = ref('');
const doctorId = ref(null);
const showBookingForm = ref(false);

const weekdays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

// Generate all half-hour slots between 9:00–17:00
const allPossibleSlots = computed(() => {
    if (!selectedDate.value) return [];

    const slots = [];
    let start = new Date(selectedDate.value);
    start.setHours(9, 0, 0, 0);
    const end = new Date(selectedDate.value);
    end.setHours(17, 0, 0, 0);

    while (start < end) {
        const hours = start.getHours().toString().padStart(2, '0');
        const minutes = start.getMinutes().toString().padStart(2, '0');
        slots.push(`${hours}:${minutes}`);
        start = new Date(start.getTime() + 30 * 60 * 1000);
    }

    return slots;
});

// Calendar days for the current month
const daysInMonth = computed(() => {
    const days = [];
    const firstDay = new Date(currentYear.value, currentMonth.value, 1);
    const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0);

    for (let i = 0; i < firstDay.getDay(); i++) days.push(null);
    for (let d = 1; d <= lastDay.getDate(); d++) {
        days.push(new Date(currentYear.value, currentMonth.value, d));
    }

    return days;
});

const monthName = computed(() =>
    new Date(currentYear.value, currentMonth.value).toLocaleString('default', { month: 'long' })
);

const selectedDateString = computed(() =>
    selectedDate.value ? selectedDate.value.toLocaleDateString() : ''
);

// Month navigation
function prevMonth() {
    currentMonth.value--;
    if (currentMonth.value < 0) {
        currentMonth.value = 11;
        currentYear.value--;
    }
}
function nextMonth() {
    currentMonth.value++;
    if (currentMonth.value > 11) {
        currentMonth.value = 0;
        currentYear.value++;
    }
}

// Select a date from the calendar
function selectDate(date) {
    selectedDate.value = date;
    showBookingForm.value = true;

    if (doctorId.value) {
        const dateParam = selectedDate.value.toISOString().split('T')[0];
        availabilityStore.getAvailableSlots(doctorId.value, dateParam);
    }
}

// Select a time slot
function selectSlot(slot) {
    bookingTime.value = slot;
}

// Close the modal
function closeModal() {
    showBookingForm.value = false;
    bookingTime.value = '';
}

// Prefill for edit mode
onMounted(async () => {
    const res = await api.get('/patient/doctor');
    doctorId.value = res.data.doctorId;

    if (props.appointment) {
        const start = new Date(props.appointment.starts_at);
        selectedDate.value = start;
        bookingTime.value = start.toTimeString().slice(0, 5);
        showBookingForm.value = true;

        const dateParam = start.toISOString().split('T')[0];
        availabilityStore.getAvailableSlots(doctorId.value, dateParam);
    }
});

// Confirm or update appointment
async function confirmBooking() {
    if (!bookingTime.value) return alert('Select a slot');

    const startsAt = new Date(selectedDate.value);
    const [hours, minutes] = bookingTime.value.split(':').map(Number);
    startsAt.setHours(hours, minutes);

    const endsAt = new Date(startsAt.getTime() + 30 * 60 * 1000);

    try {
        if (props.appointment) {
            // UPDATE existing appointment
            await appointmentStore.updateAppointment(props.appointment.id, {
                doctor_id: doctorId.value,
                starts_at: startsAt.toISOString(),
                ends_at: endsAt.toISOString(),
                status: 'confirmed'
            });
            alert('Appointment updated!');
        } else {
            // CREATE new appointment
            await appointmentStore.createAppointment({
                doctor_id: doctorId.value,
                starts_at: startsAt.toISOString(),
                ends_at: endsAt.toISOString(),
                status: 'confirmed'
            });
            alert('Appointment booked!');
        }

        closeModal();
        emit('submit');
    } catch (err) {
        console.error(err);
        alert('Failed to book/update appointment');
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
    background-color: red;
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
    background: #FFFFFF;
    color: #C0392B;

}

.slot-list li.selected {

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
.unavailable-slot {

    color: #aaa;
    cursor: not-allowed;
    border-color: #ccc;
}

</style>

