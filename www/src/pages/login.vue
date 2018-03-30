<template>

  <q-layout view="hhr lpr lFf" style="background-color:#efefef">

    <div id="container">

      <div class="box shadow-8" style="background-color:#fff;padding:20px;">
        <q-field>
          <div class="q-headline" style="padding:15px 0px">Oturum Açın</div>

        </q-field>
        <q-field label="Eposta Adresi" :label-width="4" class="fip">
          <q-input v-model="email" required />
        </q-field>

        <q-field label="Parola" :label-width="4" class="fip">
          <q-input v-model="password" type="password" />
        </q-field>

        <q-field class="fip">
          <q-btn color="primary" @click="login">Giriş</q-btn>
        </q-field>
      </div>

    </div>

  </q-layout>
</template>
<script>
import axios from "axios";
import notify from './notify';
export default {
  data () {
    return {
      email: "",
      password: ""
    };
  },
  methods: {
    getToken () {
      var apiUrl = this.apiUrl             
      if (this.$q.platform.is.cordova) {
        window.FirebasePlugin.getToken(function (token) {
          localStorage.setItem('bToken',token);
           axios
            .get(apiUrl + "bildirimToken/"+token)
            .then(response => {
            })
            .catch(e => {
              alert("Bildirim token hatası.."+ e);
            });
        }, function (error) {
          alert('Bildirim Token Hatası :' + JSON.stringify(error));
        });
      }
    },
    login: function () {
      let user = {
        email: this.email,
        password: this.password
      }
      if (!this.email || !this.password) {
        notify("Eposta ve Parola gerekli", true);
        return
      }

      this.$store.dispatch('login', { user }).then((res) => {
        if (res.status === false) {
          notify(res.msg, true);
        } else if (res.token) {
          this.getToken();
          this.$store.dispatch('actionGuard');
          this.$router.push('/');

        }
      });


    },
  }
};
</script>