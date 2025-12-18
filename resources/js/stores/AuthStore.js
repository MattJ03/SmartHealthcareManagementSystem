import { defineStore } from "pinia";
import { ref, reactive } from 'vue';
import { computed } from 'vue';
import api from "../axios.js";


export const useAuthStore = defineStore('auth', () => {
   const token = ref(localStorage.getItem('token'));
   const user = ref(null);
   const loading = ref(false);
   const error = ref('');

   async function login(email, password) {
       loading.value = true;
       try {
           const res = await api.post('login', {email, password});
           token.value = res.data.token;
           localStorage.setItem('token', token.value);
       } catch (error) {
           console.log(error.response?.data || error.message);
       } finally {
           loading.value = false;
       }
   }


});
