<template>
  <q-page v-if="guard.giris==='1'">
    <q-modal v-model="modal" :content-css="{ minWidth: '65vw', minHeight:'400px'}">
      <q-modal-layout>

        <q-toolbar slot="header" color="secondary">
          <q-toolbar-title>Randevu Bilgileri</q-toolbar-title>
          <q-btn flat round dense @click="modal = false" wait-for-ripple icon="close" />

        </q-toolbar>
        <div class="layout-padding">
          <div class="row">

            <div class="col-sm-10">
              <q-field label="Firma Adı" :label-width="3">
                <q-search v-model="currentRandevu.cariAdi" placeholder="Firma Seçin..">
                  <q-autocomplete @search="searched" @selected="selected" />
                </q-search>
                <span class="errMsg" v-if="errors.cari_id">{{ errors.cari_id }}</span>
              </q-field>

              <q-field label="Firma Yetkilisi" :label-width="3" class="fip">
                <q-input v-model="currentRandevu.yetkili" />
                <span class="errMsg" v-if="errors.yetkili">{{ errors.yetkili }}</span>
              </q-field>

              <q-field label="Görüşme Notları" :label-width="3" class="fip">
                <q-input type="textarea" v-model="currentRandevu.aciklama" />
                <span class="errMsg" v-if="errors.aciklama">{{ errors.aciklama }}</span>
              </q-field>

              <q-field label="Sonraki Randevu" :label-width="3" class="fip">
                <q-datetime v-model="currentRandevu.randevuTarihi" type="date" />
              </q-field>

               <q-field label="Tamamlandı" :label-width="3" class="fip">
                <q-toggle v-model="currentRandevu.bildirim"  true-value="1" false-value="0" />
              </q-field>

             
              <q-field class="fip">
                <q-btn color="secondary" @click="submit" v-if="kaydetBtn">Kaydet</q-btn>
                <span v-if="currentRandevu.id">
                  <q-btn align="right" icon="delete" v-if="guard.sil==='1'" color="negative" @click="sil(currentRandevu.id)"></q-btn>
                </span>
              </q-field>
            </div>
            <div class="col-sm-2">
            </div>
          </div>
        </div>

      </q-modal-layout>
    </q-modal>

    <div class="row q-pa-sm">
      <div class="col-xs-12 col-md-12">
        <q-toolbar slot="header" color="faded">
          <q-toolbar-title>Randevu Bilgileri</q-toolbar-title>
          <q-btn v-if="guard.yeni==='1'" flat round dense @click="yeniRandevu()" wait-for-ripple icon="add" />
        </q-toolbar>

        <q-search v-model="filterText" :debounce="600" placeholder="Pazarlamacı, Cari, Yetkili" icon="search" float-label="Ara" />

        <q-list highlight inset-separator>
          <q-item class="desktop-only">
            <q-item-side left />
            <q-item-main label-lines="1" sublabel-lines="1" dense>
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-2 listCol">Kullanıcı</div>
                  <div class="col-md-4 listCol">Yetkili </div>
                  <div class="col-md-3 listCol">Açıklama </div>
                  <div class="col-md-1 listCol">Tarih </div>
                  <div class="col-md-2 listCol" style="text-align:right">Randevu Tarihi </div>
                  

                </div>
              </q-item-tile>
            </q-item-main>
          </q-item>
          <q-item v-for="item of filteredData.slice(0,300)" :key="item.id">
            <q-item-side left icon="alarm_on" />
            <q-item-main :label=item.adi label-lines="1" sublabel-lines="3" dense @click.native="rowClick(item.id)" >
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-2 listCol">{{item.kullanici}} </div>
                  <div class="col-md-4 listCol">{{item.yetkili}} </div>
                  <div class="col-md-3 listCol">{{item.aciklama}} </div>
                  <div class="col-md-1 listCol">{{item.islemTarihi | tarih}} </div>
                  <div class="col-md-2 listCol" style="text-align:right"><q-icon v-if="item.bildirim=='1'" name='done' /><strong> {{item.randevuTarihi | tarih}}  </strong></div>
         
                </div>
              </q-item-tile>

            </q-item-main>

          </q-item>
        </q-list>

      </div>
    </div>

  </q-page> 

</template>

<script>

import axios from "axios";
import store from "../store";
import notify from "./notify";
import { uid, filter } from "quasar";

const module = {
  data () {
    return {


      //modal instance
      modal: false,

      //tüm kayıtlar
      randevular: [],

      cariAdi: "",


      //ara.
      filterText: "",

      //tıklanınca alınan index değer. users[index]
      id: "",

      index: "",

      errors: {},

      //detay
      currentRandevu: {},

      yetkiler: {},

      //   guard: {},

      cariList: [],

      terms: "",


    };
  },

  created () {
    this.getList();
  },


  methods: {
    formatDate (date) {
      if (!date)
        return;

      let nDate = new Date(date);
      // let fDate= new Date(nDate.toISOString().split('T')[0]);
      return nDate.toLocaleDateString();

    },
    //autocompolete için..
    searched (terms, done) {
      if (this.cariList.length == 0) {
        axios
          .get(this.apiUrl + "listShortCari")
          .then(response => {
            this.cariList = response.data;
            this.autocomplete(terms, done);
          })
          .catch(e => {
            console.log(e);
          });
      } else {
        this.autocomplete(terms, done);
      }
    },

    //from searched..
    autocomplete (terms, done) {
      let searchText = this.currentRandevu.cariAdi.toLocaleLowerCase("tr-TR");

      let sonuc = [];
      let ver = this.cariList.filter(p => {
        return p.adi !== null
          ? p.adi.toLocaleLowerCase("tr-TR").includes(searchText)
          : "";
      });
      let arr = [];

      ver.forEach(e => {
        arr.push({ label: e.adi, value: e.id });
      });

      return done(arr);
    },

    //autocomplete seçilen..
    selected (item) {
      this.currentRandevu.cariAdi = item.label;
      this.currentRandevu.cari_id = item.value;
    },



    sil (id) {
      this.$q
        .dialog({
          title: "Randevu Sil",
          message: "Randevu Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + "randevuSil?id=" + id)
            .then(response => {
              if (response.data.status === true) {
                let index = this.randevular.findIndex(x => x.id === id);
                this.randevular.splice(index, 1);
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
        .get(this.apiUrl + "randevuListele")
        .then(response => {
          this.randevular = response.data;
          if (this.$route.params.randevu) {
            this.rowClick(parseInt(this.$route.params.randevu));
          }
        })
        .catch(e => {
          this.errors.push(e);
        });
    },

    //yeni modal açar..
    yeniRandevu () {
      this.errors = {};
      this.modal = true;
      this.currentRandevu = {};
    },

    //eğer kullanıcı yeniyse veya yetki tablosu boşsa default değerleri getirir. (table: yetkiDefault)
    defaultYetkiler () {
      axios.get(this.apiUrl + "yetkiDefault").then(response => {
        this.yetkiler = response.data;
      });
    },




    rowClick (id) {

      this.currentRandevu = {};

      let index = this.randevular.findIndex(x => x.id === id);
      this.id = index;

      axios.get(this.apiUrl + "randevuGetir/" + id).then(response => {

        this.currentRandevu = response.data;
        this.currentRandevu.cariAdi = response.data.cari.adi;
      });


      this.errors = {};

      this.modal = true;
    },



    submit () {

      axios
        .post(this.apiUrl + "randevuKaydet", this.currentRandevu)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            this.getList();
            this.modal = false;
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

      return this.$store.state.auth.guard.randevu;
    },
    kaydetBtn () {
      if (this.currentRandevu.id) {
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
      if (!this.filterText) return this.randevular;

      let searchText = this.filterText.toLocaleLowerCase("tr-TR");

      return this.randevular.filter(p => {
        p.kullanici === null ? (p.kullanici = "") : p.kullanici;
        p.adi === null ? (p.adi = "") : p.adi;
         p.yetkili === null ? (p.yetkili = "") : p.yetkili;


        return (
          p.kullanici.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.adi.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.yetkili.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1

        );
      });
    }
  }
};

export default module;
</script>
