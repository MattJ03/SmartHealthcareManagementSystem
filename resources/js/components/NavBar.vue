<template>
   <nav class="nav-bar">
       <div class="logo">
           <img :src="pill" alt="logo" />
       </div>
       <div class="nav-links">
           <button class="btn-create-account" type="button"> Create New User</button>
       </div>
   </nav>
</template>
<script setup>
import pill from '../assets/pill.PNG';
</script>
<style scoped>
.nav-bar {

}
.btn-create-account {

}
</style>


