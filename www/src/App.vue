<template>
  <div id="q-app">
    <router-view />
     <q-ajax-bar color="faded"/>
  </div>
</template>

<script>
import { Platform } from 'quasar'
console.log("v0.5 dev")

export default {
  name: 'App'
}
</script>

<style>
</style>
