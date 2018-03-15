<template>
  <div>
      <div class="q-pa-md" >
                <q-field label="Adı" :label-width="3" >
                    <q-input v-model="currentCari.adi" :class="{'has-error': errors.adi}" required/>
                    <span class="errMsg" v-if="errors.adi">{{ errors.adi }}</span>
                </q-field>
                <q-field label="Yetkili" :label-width="3" class="fip">
                    <q-input v-model="currentCari.yetkili"  />
                </q-field>
                <q-field label="Eposta" :label-width="3" class="fip">
                    <q-input v-model="currentCari.eposta"  />
                </q-field>
                
                <q-field label="Adres" :label-width="3" class="fip">
                    <q-input v-model="currentCari.adres"  />
                </q-field>
                <q-field label="Telefon" :label-width="3" class="fip">
                    <q-input v-model="currentCari.telefon"  />
                </q-field>
                    <br />
        
                <q-field class="fip">
                    <q-btn color="secondary" @click="cariKaydet" >Kaydet</q-btn>
                    
                </q-field>
            </div>
    </div>
</template>

<script>
import axios from "axios";
import notify from "../pages/notify";

const module = {

  data() {
    return {
      currentCari: {},
      errors: {}
    };
  },

  methods: {
     cariKaydet() {
      axios
        .post(this.apiUrl + "cariKaydet", this.currentCari)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            this.currentCari = {};
            this.$emit('cariKaydetEmit',true)
            notify(res.data.msg);
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
