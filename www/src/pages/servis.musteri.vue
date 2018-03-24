<template>
  <q-page>

    <div class="row q-pa-sm">
      <div class="col-xs-12 col-md-12">
        <q-toolbar slot="header" color="faded">
          <q-toolbar-title>Servis Bilgiler</q-toolbar-title>
          <q-btn  flat round dense @click="yeniServis()" wait-for-ripple icon="add" />
        </q-toolbar>

        <q-search v-model="filterText" :debounce="600" placeholder="Servis Kodu" icon="search" float-label="Ara" />

        <q-list highlight separator>
          <q-item class="desktop-only">

            <q-item-main label-lines="1" sublabel-lines="1" dense>
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-1 listCol">Servis No </div>
                  <div class="col-md-2 listCol">Marka / Model </div>
                  <div class="col-md-2 listCol">Serino </div>
                  <div class="col-md-3 listCol">Açıklama </div>
                  <div class="col-md-2 listCol">Tarih </div>
                  <div class="col-md-2 listCol">Durum </div>
                </div>
              </q-item-tile>
            </q-item-main>
            <q-item-side right />
          </q-item>
          <q-item v-for="item of filteredData.slice(0,300)" :key="item.id">

            <q-item-main label-lines="1" sublabel-lines="3" dense @click.native="rowClick(item.id)">
              <q-item-tile label> {{item.cariAdi}}</q-item-tile>
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-1 listCol">{{item.id}} </div>
                  <div class="col-md-2 listCol">{{item.marka}} {{item.model}} </div>
                  <div class="col-md-2 listCol">{{item.serino}} </div>
                  <div class="col-md-3 listCol">{{item.aciklama}} </div>
                  <div class="col-md-2 listCol">{{formatDate(item.tarih)}} </div>
                  <div class="col-md-2 listCol">{{item.islemDurumLabel}} </div>
                </div>
              </q-item-tile>
            </q-item-main>
            <q-item-side right :icon="item.icon" />
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


const module = {
  data () {
    return {
      //modal instance
      modal: false,

      //tüm kayıtlar
      servisler: [],

      //ara.
      filterText: "",

      //tıklanınca alınan index değer. users[index]
      id: "",

      index: "",

      errors: {},

      //detay
      currentServis: {},

      yetkiler: {},
      cihazListModal: false,

      // guard: {},


    };
  },

  created () {
    // this.getRole();

    this.getList();

  },

  methods: {

    formatDate (date) {
      if (!date)
        return
      let nDate = new Date(date);
      let fDate = new Date(nDate.toISOString().split('T')[0]);
      return fDate.toLocaleDateString();

    },
    //servisDuzelt component'den gelen datalar
    servisEmit (val) {
      //this.modal = false;
      this.getList();

    },
    servisSil (val) {
      //this.modal= false;

      //let index = this.servisler.findIndex(x => x.id === val);
      //this.servisler.splice(index, 1);
    },

    //islem durumu id den label a çevir..


    //this.guard
    getRole () {
      axios
        .get(this.apiUrl + "yetkiler?bolum=servis")
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

    //Tüm Liste..
    getList () {
      axios
        .get(this.apiUrl + "servisListele")
        .then(response => {

          this.servisler = response.data;

        })
        .catch(e => {
          this.errors.push(e);
        });
    },


    //yeni modal açar..
    yeniServis () {
      this.$router.push('/m/servisler/servis');
      //this.errors = {};
      //  this.modal = true;
      // this.currentServis = {};
    },

    //kullanıcı seçilince index den mevcut değerleri currentServis'a at..
    rowClick (id) {

      /* this.currentServis={};
 
       //id'den users'daki indexi bul..
       let index = this.servisler.findIndex(x => x.id === id);
       this.id = index;
 
       axios.get(this.apiUrl + "getServis/" + id).then(response => {
         this.currentServis = response.data.servis;
         this.currentServis.cariAdi = response.data.servis.get_cari.adi;
         this.currentServis.cihazAdi = response.data.servis.get_cihaz.adi;
       });
       this.modal = true;
       this.errors = {};
       /*
         this.currentServis = Object.assign({}, this.servisler[index]);
       */

      this.$router.push('/m/servisler/servis/' + id);


    },

    /*submit() {
      axios
        .post(this.apiUrl + "newServis", this.currentServis)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};

            //yeni kayıtsa listeyi güncelle
            if (!this.currentServis.id) {
              this.getList();
            }
            //update..
            this.servisler[this.id] = this.currentServis;

            this.modal = false;

            notify(res.data.msg);
          }
        })
        .catch(function(error) {
          notify(error.response.data.error, true);
        });
    },
*/

    getIcon (icon) {
      switch (icon) {
        case "1":
          return "assignment_returned";
          break;
        case "2":
          return "contact_mail";
          break;
        default:
          return "devices";
          break;
      }
    }
  },

  computed: {
    guard () {
 
      return this.$store.state.auth.guard.servis;
    },
    //filter list..
    filteredData () {
      if (!this.filterText) return this.servisler;

      let searchText = this.filterText.toLocaleLowerCase("tr-TR");

      return this.servisler.filter(p => {
        p.id = p.id.toString();
        p.cariAdi = p.cariAdi.toString();

        return (
          p.id.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.cariAdi.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1
        );
      });
    }
  }
};

export default module;
</script>
