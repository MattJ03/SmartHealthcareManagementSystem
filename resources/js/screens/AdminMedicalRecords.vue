<template>
    <NavBar></NavBar>
<div class="container">
   <div class="restricted-information">
      <div class="lock-img-wrapper">
          <img :src="lock" alt="lock" class="lock-img">
      </div>
       <span class="access-restricted">Access Restricted</span>
       <span class="med-records-confidential">Medical records are confidential and can only be accessed by the patient and their assigned doctor.</span>
       <div class="false-blocking-wrapper">
           <img :src="info" alt="info" class="info-img">
           <span class="false-blocking-text">jjjjjjjjjjjjjjjjjjjjjjjjjjjjj</span>
       </div>
   </div>

</div>

</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import lock from '../assets/lock.svg';
import info from '../assets/info.svg';
</script>
<style scoped>
.container {
    display: flex;

}
.restricted-information {
    display: flex;
     flex-direction: column;

    align-items: center;
     margin: auto;
    margin-top: 80px;
}
.lock-img-wrapper {
      height: fit-content;
    width: fit-content;
    background-color: #e6f1fb;
    border-radius: 100px;
    padding: 14px 14px 18px 18px;
}
.lock-img {
    height: 70px;
}
.access-restricted {

     margin-top: 30px;
    font-size: 22px;

}
.med-records-confidential {
    color: #808080;
    margin-top: 10px;
    font-size: 18px;
}
.false-blocking-wrapper {
     height: fit-content;
    width: fit-content;
    margin-top: 25px;
    background-color: #e6f1fb;
}
.false-blocking-text {
    font-size: 16px;
}
</style>
