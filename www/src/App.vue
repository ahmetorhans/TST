<template>
  <div id="q-app">
    <router-view />
    <q-ajax-bar color="faded" />

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "App",
  created () {
    this.init();
    var router = this.$router;
    window.FirebasePlugin.onNotificationOpen(function (notification) {
      if (notification.id) {
        router.push('servisler/servis/' + notification.id);
      }
    }, function (error) {
      //alert(JSON.stringify(error));
    });
  },
  methods: {

    init () {
      this.$store.dispatch('actionGuard');
      var router = this.$router;
      axios.interceptors.response.use((response) => {
        return response
      }, function (error) {
        if (error.response.status === 401) {
          router.push('/login')
        }
        return Promise.reject(error)
      })
    }
  }
};
</script>

<style>

</style>
