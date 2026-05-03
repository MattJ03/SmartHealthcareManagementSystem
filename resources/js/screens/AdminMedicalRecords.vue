<template>
    <NavBar></NavBar>
<div class="container">
   <div class="restricted-information">
      <div class="lock-img-wrapper">
          <img :src=""
      </div>
   </div>

</div>

</template>
<script setup>
import NavBar from "../components/NavBar.vue";
</script>
<style scoped>
.container {
    display: flex;

}
.restricted-information {
    display: flex;
     flex-direction: column;
     margin: auto;
}
.lock-img-wrapper {

}
</style>
