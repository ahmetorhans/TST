<template>
  <q-page v-if="guard.giris==='1'">
    <q-modal v-model="modal" :content-css="{ minWidth: '65vw', minHeight:'90vh'}">
      <q-modal-layout>

        <q-toolbar slot="header" color="secondary">
          <q-toolbar-title>Cihaz Bilgileri</q-toolbar-title>
          <q-btn flat round dense @click="modal = false" wait-for-ripple icon="close" />

        </q-toolbar>
        <div class="layout-padding">
          <div class="row">

            <div class="col-sm-10">
              <q-field label="Firma Adı" :label-width="3">
                <q-search v-model="currentCihaz.cariAdi" placeholder="Firma Seçin..">
                  <q-autocomplete @search="searched" @selected="selected" />
                </q-search>
                <span class="errMsg" v-if="errors.cari_id">{{ errors.cari_id }}</span>
              </q-field>

              <q-field label="Cihaz Adı" :label-width="3" class="fip">
                <q-input v-model="currentCihaz.adi" />
              </q-field>

              <q-field label="Marka" :label-width="3" class="fip">
                <q-input v-model="currentCihaz.marka" />
              </q-field>

              <q-field label="Model" :label-width="3" class="fip">
                <q-input v-model="currentCihaz.model" />
              </q-field>

              <q-field label="Serino" :label-width="3" class="fip">
                <q-input v-model="currentCihaz.serino" />
              </q-field>
              <q-field label="Barkod" :label-width="3" class="fip">
                <q-input v-model="currentCihaz.barkod" />
              </q-field>

              <q-field label="Sayaç" :label-width="3" class="fip">
                <q-input v-model="currentCihaz.sayac" />
              </q-field>

              <q-field label="Açıklama" :label-width="3" class="fip">
                <q-input type="textarea" v-model="currentCihaz.aciklama" />
              </q-field>

              <q-field label="Cihaz Fatura No" :label-width="3" class="fip">
                <q-input v-model="currentCihaz.garantiFatura" />
              </q-field>

              <div class="row">
                <div class="col-sm-6 q-pr-sm">
                  <q-field label="Garanti Bitiş Tarihi" :label-width="6" class="fip">
                    <q-datetime v-model="currentCihaz.garantiTarih" type="date" />
                  </q-field>
                </div>
                <div class="col-sm-6 q-pr-sm">
                  <q-field label="Garanti Süresi" :label-width="6" class="fip">
                    <q-input v-model="currentCihaz.garantiSure" type="number" suffix="Yıl" />
                  </q-field>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6 q-pr-sm">
                  <q-field label="Alış Fiyatı" :label-width="6" class="fip">
                    <q-input v-model="currentCihaz.fiyatAlis" type="number" suffix="TL" />
                  </q-field>
                </div>
                <div class="col-sm-6 q-pr-sm">
                  <q-field label="Satış Fiyatı" :label-width="6" class="fip">
                    <q-input v-model="currentCihaz.fiyatSatis" type="number" suffix="TL" />
                  </q-field>
                </div>
              </div>

              <br />

              <q-field class="fip">
                <q-btn color="secondary" @click="submit" v-if="kaydetBtn">Kaydet</q-btn>
                <span v-if="currentCihaz.id">
                  <q-btn align="right" icon="delete" v-if="guard.sil==='1'" color="negative" @click="sil(currentCihaz.id)"></q-btn>
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
          <q-toolbar-title>Cihaz Bilgileri</q-toolbar-title>
          <q-btn v-if="guard.yeni==='1'" flat round dense @click="yeniCihaz()" wait-for-ripple icon="add" />
        </q-toolbar>

        <q-search v-model="filterText" :debounce="600" placeholder="Seri No, Barkod, Cari Adı" icon="search" float-label="Ara" />

        <q-list highlight inset-separator>
          <q-item class="desktop-only">
            <q-item-side left />
            <q-item-main label-lines="1" sublabel-lines="1" dense>
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-1 listCol desktop-only">Cihaz No </div>
                  <div class="col-md-2 listCol">Marka / Model </div>
                  <div class="col-md-2 listCol">Serino </div>
                  <div class="col-md-5 listCol">Açıklama </div>
                  <div class="col-md-2 listCol">Firma </div>

                </div>
              </q-item-tile>
            </q-item-main>
          </q-item>
          <q-item v-for="item of filteredData.slice(0,300)" :key="item.id">
            <q-item-side left icon="devices" />
            <q-item-main :label=item.adi label-lines="1" sublabel-lines="3" dense @click.native="rowClick(item.id)">
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-1 listCol desktop-only">{{item.id}} </div>
                  <div class="col-md-2 listCol">{{item.marka}} {{item.model}} </div>
                  <div class="col-md-2 listCol">{{item.serino}} </div>
                  <div class="col-md-5 listCol">{{item.aciklama}} </div>
                  <div class="col-md-2 listCol">{{item.cariAdi}} </div>
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

      //tüm kayıtlar
      cihazlar: [],

      cariAdi: "",

      //upload varsa submit etme, önce uploadları gönder
      uploadVar: false,

      //ara.
      filterText: "",

      //tıklanınca alınan index değer. users[index]
      id: "",

      index: "",

      errors: {},

      //detay
      currentCihaz: {},

      yetkiler: {},

      //   guard: {},

      cariList: [],

      terms: "",

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

  watch: {
    uploadVar (val) {
      if (val === false) {
        this.postData();
      }
    }
  },

  methods: {
    //autocompolete için..
    searched (terms, done) {
      if (this.cariList.length == 0) {
        axios
          .get(this.apiUrl + "listShortCari")
          .then(response => {
            console.log(response.data);
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
      let searchText = this.currentCihaz.cariAdi.toLocaleLowerCase("tr-TR");

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
      this.currentCihaz.cariAdi = item.label;
      this.currentCihaz.cari_id = item.value;
    },

    getRole () {
      axios
        .get(this.apiUrl + "yetkiler?bolum=cihaz")
        .then(response => {
          if (response.data.role == "super") {
            response.data.giris = "1";
            response.data.yeni = "1";
            response.data.duzelt = "1";
            response.data.sil = "1";
          }
          this.guard = response.data;
        })
        .catch(e => {
          console.log(e);
        });
    },

    sil (id) {
      this.$q
        .dialog({
          title: "Cihaz Sil",
          message: "Cihaz Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + "cihazSil?id=" + id)
            .then(response => {
              if (response.data.status === true) {
                let index = this.cihazlar.findIndex(x => x.id === id);

                this.cihazlar.splice(index, 1);
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
        .get(this.apiUrl + "cihazListele")
        .then(response => {
          this.cihazlar = response.data;
          if (this.$route.params.cihaz) {
            this.rowClick(parseInt(this.$route.params.cihaz));
          }
        })
        .catch(e => {
          this.errors.push(e);
        });
    },

    //yeni modal açar..
    yeniCihaz () {
      this.errors = {};
      this.modal = true;
      this.currentCihaz = {};
    },

    //eğer kullanıcı yeniyse veya yetki tablosu boşsa default değerleri getirir. (table: yetkiDefault)
    defaultYetkiler () {
      axios.get(this.apiUrl + "yetkiDefault").then(response => {
        this.yetkiler = response.data;
      });
    },

    //dosya yükleme bitince watch ile takip et.. Sonra submit et..
    finishUpload () {
      this.uploadVar = false;
    },

    //Dosya yükle. Gelen değeri currentCihaz'a at..
    postUpload (file, xhr) {
      let sonuc = JSON.parse(xhr.response);
      if (sonuc.status === true) {
        this.currentCihaz.photo = sonuc.file;
      }
    },

    //kullanıcı seçilince index den mevcut değerleri currentCihaz'a at..
    rowClick (id) {

      this.currentCihaz = {};
      //id'den users'daki indexi bul..
      let index = this.cihazlar.findIndex(x => x.id === id);
      this.id = index;

      axios.get(this.apiUrl + "cihazGetir/" + id).then(response => {
        console.log(response.data)
        this.currentCihaz = response.data;
        this.currentCihaz.cariAdi = response.data.cari.adi;
      });

      /*
   

      this.currentCihaz = Object.assign({}, this.cihazlar[index]);
      */
      this.errors = {};

      this.modal = true;
    },

    submit () {
      this.postData();
    },

    postData () {
      //currentCihaz'a yetkileri ata..
      //this.currentCihaz.yetkiler = this.yetkiler;
      axios
        .post(this.apiUrl + "cihazKaydet", this.currentCihaz)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            if (!this.currentCihaz.id) {
              this.getList();
            }
            //dosyaları boşalt..
            //  this.$refs.uploader.files = [];

            //yeni bilgileri users'a at..
            this.cihazlar[this.id] = this.currentCihaz;

            this.modal = false;

            notify(res.data.msg);
          }
        })
        .catch(function (error) {
          notify(error.response.data.error, true);
        });
    },
    search (user) {
      return Object.keys(this).every(key => user[key] === this[key]);
    }
  },

  computed: {
    guard () {
      return this.$store.state.auth.guard.cihaz;
    },
    kaydetBtn () {
      if (this.currentCihaz.id) {
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
      if (!this.filterText) return this.cihazlar;

      let searchText = this.filterText.toLocaleLowerCase("tr-TR");

      console.log(this.cihazlar)

      return this.cihazlar.filter(p => {
        p.serino === null ? (p.serino = "") : p.serino;
        p.barkod === null ? (p.barkod = "") : p.barkod;
        p.cariAdi === null ? (p.cariAdi = "") : p.cariAdi;

        return (
          p.serino.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.barkod.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.cariAdi.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1
        );
      });
    }
  }
};

export default module;
</script>
