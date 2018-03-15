<template>
  <div>
      <div class="q-pa-md" >
                <q-field label="İşlem Adı" :label-width="3" class="fip">
                    <q-input v-model="currentIslem.adi"  />
                    <span class="errMsg" v-if="errors.adi">{{ errors.adi }}</span>
                </q-field>
                
                
                <q-field label="Açıklama" :label-width="3" class="fip">
                    <q-input type="textarea" v-model="currentIslem.aciklama"  />
                </q-field>
                
                
                 
                <q-field class="fip">
                    <q-btn color="secondary" @click="islemKaydet">Kaydet</q-btn>
                </q-field>
            </div>
    </div>
</template>

<script>
import axios from "axios";
import notify from "../pages/notify";

const module = {
  props: ["servisId"],
  data() {
    return {
      currentIslem: {},
      errors: {}
    };
  },

  methods: {
    islemKaydet() {
      this.currentIslem.servis_id = this.servisId;

      axios
        .post(this.apiUrl + "islemKaydet", this.currentIslem)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            notify(res.data.msg);
            console.info("asdasda")
            console.log(res.data)
            this.$emit("islemKaydetEmit", res.data.islemSonuc);
          }
        })
        .catch(function(error) {
          notify(error.response.data.error, true);
        });
    }
  }
};

export default module;
</script>
