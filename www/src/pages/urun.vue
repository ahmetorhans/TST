<template>

  <q-page v-if="guard.giris==='1'">
  
    <q-modal v-model="modal" :content-css="{ minWidth: '65vw', minHeight:'90vh'}">
      <q-modal-layout>

        <q-toolbar slot="header" color="secondary">
          <q-toolbar-title>Ürünler</q-toolbar-title>
          <q-btn flat round dense @click="modal = false" wait-for-ripple icon="close" />

        </q-toolbar>
        <div class="layout-padding">
          <div class="row">

            <div class="col-sm-10">
              <q-field label="Stok Kodu" :label-width="3" class="fip">
                <q-input v-model="currentUrun.stok_kodu" />
              </q-field>

              <q-field label="Marka" :label-width="3" class="fip">
                <q-input v-model="currentUrun.marka" />
              </q-field>

              <q-field label="Model" :label-width="3" class="fip">
                <q-input v-model="currentUrun.model" />
              </q-field>

              <q-field label="fiyat" :label-width="3" class="fip">
                <q-input v-model="currentUrun.fiyat" />
              </q-field>
              <q-field label="Bayi Fiyatı" :label-width="3" class="fip">
                <q-input v-model="currentUrun.bayiFiyat" />
              </q-field>

              <q-field label="Ek bilgi" :label-width="3" class="fip">
                <q-input type="number" v-model="currentUrun.ekBilgi" />
              </q-field>

              <q-field label="Açıklama" :label-width="3" class="fip">
                <q-input type="textarea" v-model="currentUrun.aciklama" />
              </q-field>
              <q-field class="fip">
                <q-btn color="secondary" @click="submit" v-if="kaydetBtn">Kaydet</q-btn>
                <span v-if="currentUrun.id">
                  <q-btn align="right" icon="delete" v-if="guard.sil==='1'" color="negative" @click="sil(currentUrun.id)"></q-btn>
                </span>
              </q-field>
            </div>
            <div class="col-sm-2">
            </div>
          </div>
        </div>
      </q-modal-layout>
    </q-modal>
    <!-- ayar modal -->
    <q-modal v-model="ayarModal" :content-css="{ minWidth: '65vw', minHeight:'90vh'}">
      <q-modal-layout>

        <q-toolbar slot="header" color="secondary">
          <q-toolbar-title>Ayarlar</q-toolbar-title>
          
          <q-btn  flat round dense @click="ayarModal = false" wait-for-ripple icon="close" />

        </q-toolbar>
        <div class="layout-padding">
          <vue-xlsx-table @on-select-file="handleSelectedFile">Dosya Yükle</vue-xlsx-table>
          <br /><br />

          <q-btn color="secondary" @click="xlsKaydet" v-if="xlsYuklendi">Kaydet</q-btn>
          <br /><br />
          <table v-if="xlsYuklendi">
            <tr>
              <td>Stok kodu</td>
              <td>Açıklama</td>
              <td>Bayifiyat</td>
              <td>Fiyat</td>
              <td>Marka</td>
              <td>Model</td>
              <td>Bilgi</td>
            </tr>
            <tr v-for="item of xlsTable">
              <td> {{item[0]}}</td>
              <td> {{item[1]}}</td>
              <td> {{item[2]}}</td>
              <td> {{item[3]}}</td>
              <td> {{item[4]}}</td>
              <td> {{item[5]}}</td>
              <td> {{item[6]}}</td>
            </tr>
          </table>
          <q-btn color="negative" @click="xlsSifirla">Otomatik yüklenen kayıtları sil</q-btn>
        </div>
      </q-modal-layout>
    </q-modal>
    <!-- ayar modal / -->
    <div class="row q-pa-sm">
      <div class="col-xs-12 col-md-12">

        <q-toolbar slot="header" color="faded">
          <q-toolbar-title>Ürünler</q-toolbar-title>
          <q-btn v-if="guard.role==='super' || guard.role==='admin'" flat round dense @click="ayarModal = !ayarModal" wait-for-ripple icon="settings" />
          <q-btn v-if="guard.yeni==='1'" flat round dense @click="yeniUrun()" wait-for-ripple icon="add" />

        </q-toolbar>

        <q-search v-model="filterText" :debounce="600" placeholder="Stok Kodu, Açıklama, Marka, Model" icon="search" float-label="Ara" />

        <q-list highlight inset-separator>
          <q-item class="desktop-only">
            <q-item-side left />
            <q-item-main label-lines="1" sublabel-lines="1" dense>
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-2 listCol">Stok Kodu</div>
                  <div class="col-md-4 listCol">Açıklama </div>
                  <div class="col-md-2 listCol">Marka / Model </div>
                  <div class="col-md-2 listCol">Fiyat </div>
                  <div class="col-md-1 listCol">Bilgi </div>

                </div>
              </q-item-tile>
            </q-item-main>
          </q-item>
          <q-item v-for="item of filteredData.slice(0,300)" :key="item.id">
            <q-item-side left icon="shop" />
            <q-item-main :label=item.adi label-lines="1" sublabel-lines="3" dense @click.native="rowClick(item.id)">
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-2 listCol desktop-only">{{item.stok_kodu}} </div>
                  <div class="col-md-4 listCol">{{item.aciklama}} </div>
                  <div class="col-md-2 listCol">{{item.marka}} <br/>{{item.model}} </div>
                  <div class="col-md-2 listCol">{{item.fiyat}} </div>
                  <div class="col-md-1 listCol">{{item.ekBilgi}} </div>
                </div>
              </q-item-tile>

            </q-item-main>

          </q-item>
        </q-list>

      </div>
    </div>

  </q-page>

</template>
<style>
table,
th,
td {
  border: 1px dotted #ddd;
  border-collapse: collapse;
}
</style>
<script>
function parseCountries () {
  return countries.map(country => {
    return {
      label: country.label,
      value: country.label
    };
  });
}

import axios from "axios";
import store from "../store";
import notify from "./notify";
import { uid, filter } from "quasar";

const module = {
  data () {
    return {


      //modal instance
      modal: false,
      ayarModal: false,

      //tüm kayıtlar
      urunler: [],

      //upload varsa submit etme, önce uploadları gönder
      uploadVar: false,

      //ara.
      filterText: "",

      //tıklanınca alınan index değer. users[index]
      id: "",

      index: "",

      errors: {},

      //detay
      currentUrun: {},

      xlsTable: {},

      terms: "",

      xlsYuklendi: false,


      selectOptions: [
        { label: "Aktif", value: "1" },
        { label: "Pasif", value: "0" }
      ]
    };
  },

  created () {
    // this.getRole();

    this.getList();
  },


  methods: {
    handleSelectedFile (convertedData) {

      var xls = {};
      var i = 0;
      convertedData.body.forEach(element => {
        xls[i] = [];
        for (let z = 0; z <= convertedData.header.length; z++) {
          xls[i].push(element[Object.keys(element)[z]]);
        }
        i++;

      });

      this.xlsTable = xls;
      this.xlsYuklendi = true;


    },

    sil (id) {
      this.$q
        .dialog({
          title: "Ürün Sil",
          message: "Ürün Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + "urunSil?id=" + id)
            .then(response => {
              if (response.data.status === true) {
                let index = this.urunler.findIndex(x => x.id === id);
                this.urunler.splice(index, 1);
                this.modal = false;
                notify(response.data.msg);
              } else notify(response.data.msg, true);
            })
            .catch(e => {
              this.errors.push(e);
            });
        });
    },
    //Tüm Liste..
    getList () {
      axios
        .get(this.apiUrl + "urunListele")
        .then(response => {
          this.urunler = response.data;
          if (this.$route.params.urun) {
            this.rowClick(parseInt(this.$route.params.urun));
          }
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    xlsSifirla () {

       this.$q
        .dialog({
          title: "Sil",
          message: "Ürünler Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + "xlsSifirla")
            .then(response => {
              if (response.data.status === true) {
                this.ayarModal = false;
                this.getList();
                notify(response.data.msg);
              } else notify(response.data.msg, true);
            })
            .catch(e => {
              this.errors.push(e);
            });
        });

      
    },
    //yeni modal açar..
    yeniUrun () {
      this.errors = {};
      this.modal = true;
      this.currentUrun = {};
    },





    //kullanıcı seçilince index den mevcut değerleri currentUrun'a at..
    rowClick (id) {

      this.currentUrun = {};
      //id'den users'daki indexi bul..
      let index = this.urunler.findIndex(x => x.id === id);
      this.id = index;

      axios.get(this.apiUrl + "urunGetir/" + id).then(response => {
        console.log(response.data)
        this.currentUrun = response.data;

      });

      this.errors = {};

      this.modal = true;
    },



    submit () {
      //currentUrun'a yetkileri ata..
      //this.currentUrun.yetkiler = this.yetkiler;
      axios
        .post(this.apiUrl + "urunKaydet", this.currentUrun)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            if (!this.currentUrun.id) {
              this.getList();
            }

            this.urunler[this.id] = this.currentUrun;

            this.modal = false;

            notify(res.data.msg);
          }
        })
        .catch(function (error) {
          notify(error.response.data.error, true);
        });
    },

    xlsKaydet () {

      axios
        .post(this.apiUrl + "xlsKaydet", this.xlsTable)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};

            this.getList();

            this.ayarModal = false;

            notify(res.data.msg);
          }
        })
        .catch(function (error) {
          notify(error.response.data.error, true);
        });
    },

  },

  computed: {
    guard () {
      return this.$store.state.auth.guard.urun;
    },
    kaydetBtn () {
      if (this.currentUrun.id) {
        if (this.guard.duzelt == "1") {
          return true;
        }
      } else {
        if (this.guard.yeni == "1") {
          return true;
        }
      }
      return false;
    },



    //kullanıcı listesindan ara..
    filteredData () {
      if (!this.filterText) return this.urunler;

      let searchText = this.filterText.toLocaleLowerCase("tr-TR");
      return this.urunler.filter(p => {
        p.stok_kodu === null ? (p.stok_kodu = "") : p.stok_kodu;
        p.aciklama === null ? (p.aciklama = "") : p.aciklama;
        p.marka === null ? (p.marka = "") : p.marka;
        p.model === null ? (p.model = "") : p.model;

        return (
          p.stok_kodu.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.aciklama.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.marka.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.model.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1
        );
      });
    }
  }
};

export default module;
</script>


