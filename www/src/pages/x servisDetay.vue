<template>
  <q-page >
   

    <servis-duzelt :currentServis="currentServis" :guard="guard" @servisEmit="servisEmit" @servisSilEmit="servisSilEmit"/>

    

    <q-modal v-model="cihazListModal" :content-css="{ zIndex:'99999',minWidth: '50vw', minHeight:'65vh'}">
        <q-modal-layout>
            <q-toolbar color="faded" inverted>
                <q-toolbar-title>Cihaz Seç</q-toolbar-title>
                <q-btn flat round dense @click="cihazListModal = false" wait-for-ripple icon="close" />
            </q-toolbar>
            
        </q-modal-layout>
    </q-modal>
   
    
  </q-page>
</template>

<script>
import axios from "axios";
import store from "../store";
import notify from "./notify";
import servisDuzelt from "../components/servisDuzelt";

import { uid, filter } from "quasar";

const module = {
  components: {
    servisDuzelt
  },

  data() {
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

      guard: {}

      
      
    };
  },

  created() {
    this.getRole();

    this.getServis();
  },

  methods: {
    //servisDuzelt component'den gelen datalar
    servisEmit(val) {
      this.modal = false;
      this.$router.push('/servisler')

      
    },
    servisSilEmit(val) {
      this.modal = false;
      this.$router.push('/servisler')
      console.log(val);
      let index = this.servisler.findIndex(x => x.id === val);
      this.servisler.splice(index, 1);
    },

    

    //this.guard
    getRole() {
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
    getServis() {
      if (this.$route.params.id) {
        axios
          .get(this.apiUrl + "getServis/" + this.$route.params.id)
          .then(response => {
            console.log(response.data)
            this.currentServis = response.data.servis;
            this.currentServis.cariAdi = response.data.servis.get_cari.adi;
            this.currentServis.cihazAdi = response.data.servis.get_cihaz.adi;

            this.currentServis.islemDurumlari = response.data.islemDurumlari;
          })
          .catch(e => {
            this.errors.push(e);
          });
      }else{
        this.currentServis.get_cari={};
        this.currentServis.get_cihaz={};
      }
    },

    //yeni modal açar..
    yeniServis() {
      this.errors = {};
      this.modal = true;
      this.currentServis = {};
    },

    //kullanıcı seçilince index den mevcut değerleri currentServis'a at..
    rowClick(id) {
      this.currentServis = {};

      //id'den users'daki indexi bul..
      let index = this.servisler.findIndex(x => x.id === id);
      this.id = index;

      axios.get(this.apiUrl + "getServis/" + id).then(response => {
        this.currentServis = response.data.servis;
        this.currentServis.cariAdi = response.data.servis.get_cari.adi;
        this.currentServis.cihazAdi = response.data.servis.get_cihaz.adi;
      });

      /*
        this.currentServis = Object.assign({}, this.servisler[index]);
      */
      this.errors = {};

      this.modal = true;
    },

    

    getIcon(icon) {
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
    //filter list..
    filteredData() {
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
