import { defineStore } from "pinia";
import { ref, reactive } from 'vue';
import { computed } from 'vue';
import api from "../axios.js";


export const useAuthStore = defineStore('auth', () => {
   const token = ref(localStorage.getItem('token'));
   const user = ref(null);
   const name = ref(localStorage.getItem('name'));
   const role = ref(localStorage.getItem('role'));
   const loading = ref(false);
   const error = ref('');
   const isAdmin = computed(() => role.value === 'admin');

   async function login(email, password) {
       loading.value = true;
       try {
           const res = await api.post('login', {email, password});
           token.value = res.data.token;
           role.value = res.data.role;
           name.value = res.data.name;
           localStorage.setItem('token', token.value);
           localStorage.setItem('role', role.value);
           localStorage.setItem('name', name.value);
           await fetchUser();
       } catch (error) {
           console.log(error.response?.data || error.message);
           token.value = null;
           role.value = null;
           localStorage.removeItem('token');
           localStorage.removeItem('role');
           localStorage.removeItem('name');
           throw error;
       } finally {
           loading.value = false;
       }
   }

    async function fetchUser() {
        loading.value = true;
        try {
            const res = await api.get('/me');
            user.value = res.data;

            user.value.doctor_id = user.value.profile?.doctor_id ?? null;
        } catch (err) {
            user.value = null;
        } finally {
            loading.value = false;
        }
    }

   async function patientRegister(payload) {
       return register('/registerPatient', payload);
   }

   async function doctorRegister(payload) {
       return register('/registerDoctor', payload)
   }

   async function adminRegister(payload) {
       return register('/registerAdmin', payload);
   }

   async function register(endpoint, payload) {
       loading.value = true;
       try {
           const res = await api.post(endpoint, payload);
           return res.data;
       } catch (error) {
           error.value = error.response?.data?.message || 'Registration failed';
           throw error;
       } finally {
           loading.value = false;
       }
    }




    return {
        token,
        user,
        role,
        name,
        loading,
        fetchUser,
        error,
        isAdmin,
        login,
        register,
        patientRegister,
        doctorRegister,
        adminRegister,

    };

});
