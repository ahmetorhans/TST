<template>
  <div id="q-app">
     <router-view />
      <q-ajax-bar color="faded"/>
      
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "App",
  created(){
    this.init();
  },
  methods:{
      init(){
        this.$store.dispatch('actionGuard');
      }
  }
};
</script>

<style>

</style>
