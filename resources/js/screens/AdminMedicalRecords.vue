<template>
    <NavBar></NavBar>
<div class="container">
   <div class="restricted-information">
      <div class="lock-img-wrapper">
          <img :src="lock" alt="lock" class="lock-img">
      </div>
       <span class="access-restricted">Access restricted</span>
       <span class="med-records-confidential">Medical records are strictly confidential and private, and can <br> only be accessed by the patient and their assigned doctor.</span>
       <div class="false-blocking-wrapper">
           <img :src="info" alt="info" class="info-img">
           <span class="false-blocking-text">If you should have access, please contact support. </span>
       </div>
       <button class="go-home-btn">Go Home</button>
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
    margin-top: 200px;
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
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
     height: fit-content;
    width: fit-content;
    margin-top: 35px;
    background-color: #e6f1fb;
    padding-left: 16px;
    padding-top: 12px;
    padding-bottom: 12px;
    padding-right: 16px;
    border-radius: 16px;
    border: 1px solid #0a58ca;
}
.false-blocking-text {
    font-size: 16px;
    color: #4a5568;

}
.info-img {
    height: 20px;
    margin-right: 10px;
}
.go-home-btn {
    margin-top: 30px;
    border-radius: 14px;
    padding-right: 16px;
    padding-left: 16px;
    padding-bottom: 12px;
    padding-top: 12px;
    cursor: pointer;
    font-size: 16px;
    color: #FFFFFF;
    background-color: #305cde;
    border: none;
}
.go-home-btn:hover {
    background-color: #0a58ca;
    transition: 0.5ms;
}
</style>
