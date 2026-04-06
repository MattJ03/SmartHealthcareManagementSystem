<template>
    <NavBar></NavBar>
    <div class="container">
        <div class="log-wrapper">
            <div class="pagination-container">
                <div class="filtering-container">
                    <select class="dropdown-value" v-if="role === 'doctor' || role === 'admin'" v-model="filter.patient_id" @change="filterThroughLogs">
                        <option class="dropdown-value" value="">--Select Patient--</option>
                        <option class="dropdown-value" v-for="patient in patients" :key="patient.id" :value="patient.id">
                            {{ patient.user.name }}
                        </option>
                    </select>
                    <select class="dropdown-value" v-if="role === 'patient' || role === 'admin" v-model="filter.doctor_id" @change="filterThroughLogs">
                        <option class="dropdown-value" value="">--Select Doctor--</option>
                    </select>
                </div>
                <div class="nav-area">
               <button class="next-prev" @click="prevLogs" v-if="pageNum > 1">Prev</button>
                Page {{ pageNum }}
                <button class=next-prev @click="nextLogs">Next</button>
                </div>
            </div>

        <div class="log-container">
        <div class="log-headings">
            <h3>Timestamp</h3>
            <h3>Action</h3>
            <h3>Description</h3>
        </div>
            <div class="horizontal-line">
                <hr>
            </div>
            <div class="log-results">
                <LogGrid
                    v-if="role === 'patient'"
                    v-for="log in patientLogs"
                    :id="log.id"
                    :logs="log"
                    />
                <LogGrid
                    v-if="role === 'doctor'"
                    v-for="log in doctorLogs"
                    :id="log.id"
                    :logs="log"
                    />
            </div>
        </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import { useActivityLogsStore } from "../stores/ActivityLogsStore.js";
import { storeToRefs } from 'pinia';
import { onMounted } from 'vue';
import api from "../axios.js";
import LogGrid from "../components/LogGrid.vue";
import NavBar from "../components/NavBar.vue";
import {useAuthStore} from "../stores/AuthStore.js";
import { useUserDirectoryStore } from "../stores/UserDirectoryStore.js";

const logsStore = useActivityLogsStore();
const authStore = useAuthStore();
const userStore = useUserDirectoryStore();

const { role } = storeToRefs(authStore);
const { patientLogs, doctorLogs, allLogs } = storeToRefs(logsStore);
const { patients } = storeToRefs(userStore);
const loading = ref(false);
const error = ref(null);
const pageNum = ref(1);

const filter = reactive({
    patient_id: '',
    doctor_id: '',
    action: '',
})

onMounted (() => {
    if(role.value === 'patient') {
        logsStore.getPatientLogs();
    }
    if(role.value === 'doctor') {
        logsStore.getDoctorLogs();
        userStore.fetchPatientsOfDoctor();
    }
    if(role.value === 'admin') {
        logsStore.getAllLogs();
    }


});

const nextLogs = async () => {
    loading.value = true;
    try {
        pageNum.value++;
        if(role.value === 'doctor') {
            console.log('fetching next logs' + pageNum.value);
            const res = await api.get(`getDoctorsLogList?page=${pageNum.value}`);
            doctorLogs.value = res.data.logs.data;
        }
        if(role.value === 'patient') {
            const res = await api.get(`getPatientsLogList?page=${pageNum.value}`);
            patientLogs.value = res.data.logs.data;
        }
        if(role.value === 'admin') {
            const res = await api.get(`getCompleteLogList?page=${pageNum.value}`);
            allLogs.value = res.data.logs.data;
        }
    } catch (err) {
        error.value = error.response?.data?.message;
    } finally {
        loading.value = false;
    }
}

const prevLogs = async () => {
    loading.value = true;
    if(pageNum.value <= 1) {
        return;
    }
    try {
        pageNum.value--;
        if(role.value === 'doctor') {
            const res = await api.get(`getDoctorsLogList?page=${pageNum.value}`);
            doctorLogs.value = res.data.logs.data;
        }
        if(role.value === 'patient') {
            const res = await api.get(`getPatientsLogList?page=${pageNum.value}`);
            patientLogs.value = res.data.logs.data;
        }
        if(role.value === 'admin') {
            const res = await api.get(`getCompleteLogList?page=${pageNum.value}`);
            allLogs.value = res.data.logs.data;
        }
    } catch(err) {
        error.value = error.response?.data?.message;
    } finally {
        loading.value = false;
    }
}

const filterThroughLogs = async () => {
  loading.value = true;
  try {
      const params = {};
      if(filter.patient_id !== '') {
          params.patient_id = filter.patient_id;
      }
      if(filter.doctor_id !== '') {
          params.doctor_id = filter.doctor_id;
      }
      if(filter.action !== '') {
          params.action = filter.action;
      }
      const res = await api.get('getFilteredLogList', { params });

      if(role.value === 'patient') {
          patientLogs.value = res.data.logs.data;
      }
      if(role.value === 'doctor') {
          doctorLogs.value = res.data.logs.data;
      }
      if(role.value === 'admin') {
          allLogs.value = res.data.logs.data;
      }
  } catch (err) {
      error.value = error.response?.data?.message;
  } finally {
      loading.value = false;
  }
}

</script>
<style scoped>
.container {
    display: flex;
    justify-content: left;
    width: 100%;
    padding-left: 30px;
    padding-top: 20px;

}
.log-container {
    background-color: #FFFFFF;
    border: #E9DCC9 solid 1px;
    border-radius: 14px;

}
.log-headings {
    display: grid;
    padding-left: 20px;
    grid-template-columns: 1fr 1fr 2fr;
    width: 100%;
}

.horizontal-line {
    width: 100%;
    border: none;

    margin: 10px 0;
    padding-left: 0;
}

.log-results {

}
.log-wrapper {
    width: 75%;
}

.pagination-container {
    display: flex;
    justify-content: space-between;
   margin-bottom: 5px;
}

.next-prev {

    padding-bottom: 15px;
    padding-top: 15px;
    padding-left: 25px;
    padding-right: 25px;
    border: 1px solid #FFFFFF;
    border-radius: 14px;
    color: #FFFFFF;
    background-color: #305cde;
    font-size: 16px;

}
.next-prev:hover {
    background-color: #0a53be;
    cursor: pointer;
}

.nav-area {

}
.filtering-container {
    display: flex;

     background-color: #305cde;
    margin-right: 40px;
    width: 600px;
    height: 68px;
    border: #FFFFFF solid 1px;
    border-radius: 14px;
}

.dropdown-value {
    justify-content: left;
    background-color: #FFFFFF;
    border: none;
    border-radius: 14px;
    font-size: 14px;
    margin-left: 20px;
    height: 45px;
    margin-top: 12px;

}
</style>
