<template>
    <NavBar></NavBar>
    <div class="container">
        <div class="top-row-squares">
            <div class="container-square">
                <p class="number-patients-text">Number of patients</p>
                <p class="patients-num-result">{{ userStore.numberPatients }}</p>

            </div>
        </div>

    </div>
</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import { ref, reactive, computed, onMounted } from "vue";
import { useUserDirectoryStore } from "../stores/UserDirectoryStore.js";

const userStore = useUserDirectoryStore();

onMounted(() => {
    userStore.fetchAllPatients()
})

</script>
<style scoped>
.container {
    display: flex;
    border: 1px solid #305cde;
    border-radius: 14px;
    margin: auto;
    margin: 40px;
    background-color: #FFFFFF;
    min-height: 800px;
}
.top-row-squares {
    display: flex;
    flex-direction: row;
    margin-left: 40px;
    margin-right: 40px;
    margin-top: 20px;
}
.container-square {
    display: flex;
    flex-direction: column;
    background-color: #FFFFFF;
    height: 200px;
    width: 220px;
    border: 1px solid #305cde;
    border-radius: 16px;
}
.number-patients-text {
    font-size: 16px;
    padding-left: 15px;
    color: #4a5568;

}
.patients-num-result {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 70px;
}
</style>
