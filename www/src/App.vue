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
    //cordova notificaitom varsa yönlendir.
    if (this.$q.platform.is.cordova) {
      var router = this.$router;

      window.FirebasePlugin.onNotificationOpen(function (notification) {
        if (notification.id) {
          router.push('servisler/servis/' + notification.id);
        }
      }, function (error) {
        //alert(JSON.stringify(error));
      });
    }
  },
  methods: {

    init () {
      //kullanıcı yetkilerini store'a gönder..
      this.$store.dispatch('actionGuard');

      var router = this.$router;

      //401 hatalarında logine git..
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
