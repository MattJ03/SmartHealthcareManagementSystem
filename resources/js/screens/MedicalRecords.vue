<template>
    <NavBar></NavBar>
    <div class="container">
      <MedicalHistoryGrid>

      </MedicalHistoryGrid>
    </div>

</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import NavBar from "../components/NavBar.vue";
import MedicalHistoryGrid from "../components/MedicalHistoryGrid.vue";

</script>
<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #FFFFFF;
    width: 85%;
    height: 400px;
    margin: auto;
    border-radius: 14px;
    border: 1px solid #E9DCC9;
    margin-top: 30px;
}
</style>
