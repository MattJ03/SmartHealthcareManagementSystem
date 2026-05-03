<template>
    <NavBar></NavBar>
<div class="container">
   <div class="restricted-information">
      <div class="lock-img-wrapper">
          <img :src="lock" alt="lock" class="lock-img">
      </div>
   </div>

</div>

</template>
<script setup>
import NavBar from "../components/NavBar.vue";
import lock from '../assets/lock.svg';
</script>
<style scoped>
.container {
    display: flex;

}
.restricted-information {
    display: flex;
     flex-direction: column;
     margin: auto;
    margin-top: 60px;
}
.lock-img-wrapper {
      height: fit-content;
    background-color: #e6f1fb;
    border-radius: 100px;
    padding: 14px 14px 18px 18px;
}
.lock-img {
    height: 70px;

}
</style>
