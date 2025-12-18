import { defineStore } from "pinia";
import { ref, reactive } from 'vue';
import { computed } from 'vue';
import api from "../axios.js";


const useAuthStore = defineStore('auth', () => {
   const token = ref('');
   const user = ref(null);
   const loading = ref(false);
   const error = ref('');

   async function login(email, password) {
       loading.value = true;
       try {


       }
   }

});
