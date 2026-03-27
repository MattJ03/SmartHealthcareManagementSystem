import { defineStore } from 'pinia';
import { ref, reactive, computed } from 'vue';
import api from "../axios.js";

export const useActivityLogsStore = defineStore('activity_logs', () => {
    const role = localStorage.getItem('role');
    const
})
