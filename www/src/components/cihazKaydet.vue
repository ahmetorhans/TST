<template>
  <div>
      <div class="q-pa-md" >
                <q-field label="Cihaz Adı" :label-width="3" class="fip">
                    <q-input v-model="currentCihaz.adi"  />
                </q-field>
                
                <q-field label="Marka" :label-width="3" class="fip">
                    <q-input v-model="currentCihaz.marka"  />
                </q-field>
                
                <q-field label="Model" :label-width="3" class="fip">
                    <q-input v-model="currentCihaz.model"  />
                </q-field>
                
                <q-field label="Serino" :label-width="3" class="fip">
                    <q-input v-model="currentCihaz.serino"  />
                </q-field>
                 <q-field label="Açıklama" :label-width="3" class="fip">
                    <q-input  type="textarea" v-model="currentCihaz.aciklama"  />
                </q-field>
                <q-field class="fip">
                    <q-btn color="secondary" @click="cihazKaydet">Kaydet</q-btn>
                    
                </q-field>
            </div>
    </div>
</template>

<script>
import axios from "axios";
import notify from "../pages/notify";

const module = {
  props: ["cariId"],
  data() {
    return {
      currentCihaz: {},
      errors: {}
    };
  },

  methods: {
    cihazKaydet() {
      this.currentCihaz.cari_id = this.cariId;

      axios
        .post(this.apiUrl + "cihazKaydet", this.currentCihaz)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            notify(res.data.msg);
            this.$emit("cihazKaydetEmit", true);
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
