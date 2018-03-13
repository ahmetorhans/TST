<template>
  <q-page v-if="guard.giris">

    <q-modal v-model="modal" :content-css="{ minWidth: '65vw', minHeight:'90vh'}">
        <q-modal-layout>
            <q-toolbar slot="header" color="secondary">
                <q-toolbar-title>Servis Bilgileri</q-toolbar-title>
                <q-btn flat round dense @click="modal = false" wait-for-ripple icon="close" />
            </q-toolbar>
            <div class="layout-padding">
                <div class="row"> 
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-xs-11">
                                <q-field label="Cari Seçin" :label-width="3" class="fip">
                                    <q-search v-model="currentServis.cariAdi" placeholder="Cari Adı">
                                    <q-autocomplete @search="cariAra" @selected="cariAraSelected" />
                                    </q-search>
                                <span class="errMsg" v-if="errors.cari_id">{{ errors.cari_id }}</span>
                                </q-field>
                            </div>
                            <div class="col-xs-1" style="text-align:center;margin-top:5px;">
                                <q-btn icon="add" size="sm" round @click="cariModal = !cariModal"></q-btn>
                            </div>
                        </div>
                        <div class="row" v-if="currentServis.cari_id"> 
                                <div class="col-xs-11">
                                    <q-field label="Cihaz Seçin" :label-width="3" class="fip">
                                        <q-search v-model="currentServis.cihazAdi" placeholder="Cihaz adı, Seri No" ref="cihazAdi">
                                            <q-autocomplete @search="cihazAra" @selected="cihazAraSelected" />
                                        </q-search>
                                        <span class="errMsg" v-if="errors.cihaz_id">{{ errors.cihaz_id }}</span>
                                    </q-field>
                                </div>
                                <div class="col-xs-1" style="text-align:center;margin-top:5px;">
                                    <q-btn icon="add" size="sm" round @click="cihazModal = !cihazModal"></q-btn>
                                </div>

                                <div class="col-sm-11">
                                    <q-field label="İşlem Durumu" :label-width="3" class="fip">
                                        <q-select
                                            v-model="currentServis.islemDurumu"
                                            radio
                                            :options="islemDurumu"
                                            
                                        />
                                    </q-field>
                                    <q-field label="Teknisyen" :label-width="3" class="fip">
                                        <q-select
                                            v-model="currentServis.teknisyen"
                                            radio
                                            :display-value="id"
                                            :options="teknisyen"
                                        />
                                    </q-field>
                                    
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <q-field label="Fiyat" :label-width="6" class="fip">
                                                <q-input v-model="currentServis.fiyat" suffix="TL" />
                                            </q-field>
                                        </div>
                                        <div class="col-sm-6 q-pl-md">
                                            <q-field label="Fatura Kesildi mi" :label-width="6" class="fip">
                                                <q-checkbox v-model="currentServis.fatura" :true-value="1" :false-value="0" />
                                            </q-field>  
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <q-field label="Ek Parça" :label-width="3" class="fip">
                                        <q-input v-model="currentServis.ekParca"  />
                                    </q-field>
                                    <q-field label="Açıklama" :label-width="3" class="fip">
                                        <q-input  type="textarea" v-model="currentServis.aciklama"  />
                                    </q-field>
                                    <q-field class="fip">
                                        <q-btn color="secondary" @click="submit" v-if="kaydetBtn">Kaydet</q-btn>
                                        <q-btn align="right"  v-if="guard.sil" color="negative" @click="sil(currentUser.id)" icon="delete"></q-btn>
                                    </q-field>
                                </div>
                        </div>
                    </div>
                  
                </div>
            </div> 
        </q-modal-layout>
    </q-modal>

    <q-modal v-model="cariModal" :content-css="{ zIndex:'9999',minWidth: '50vw', minHeight:'360px'}">
        <q-modal-layout>
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
                    <q-btn color="secondary" @click="cariKaydet" v-if="kaydetBtn">Kaydet</q-btn>
                    <q-btn color="faded" @click="cariModal = false" label="Kapat"/>
                </q-field>
            </div>
        </q-modal-layout>
    </q-modal>

    <q-modal v-model="cihazModal" :content-css="{ zIndex:'99999',minWidth: '50vw', minHeight:'380px'}">
        <q-modal-layout>
            
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
                    <q-btn @click="cihazModal = false" label="Kapat" />
                </q-field>
            </div>
        </q-modal-layout>
    </q-modal>

    <q-modal v-model="cihazListModal" :content-css="{ zIndex:'99999',minWidth: '50vw', minHeight:'65vh'}">
        <q-modal-layout>
            <q-toolbar color="faded" inverted>
                <q-toolbar-title>Cihaz Seç</q-toolbar-title>
                <q-btn flat round dense @click="cihazListModal = false" wait-for-ripple icon="close" />
            </q-toolbar>
            <cihaz-list @clicked="onClickChild"/>
        </q-modal-layout>
    </q-modal>
   
    <div class="row q-pa-sm" >
       <div class="col-xs-12 col-md-12" >
            <q-toolbar slot="header" color="faded">
                <q-toolbar-title>Servis Bilgileri</q-toolbar-title>
                <q-btn v-if="guard.yeni" flat round dense @click="yeniServis()" wait-for-ripple icon="add" />
            </q-toolbar>
        
            <q-search
            v-model="filterText"
            :debounce="600"
            placeholder="Servis Kodu"
            icon="search"
            float-label="Ara"
            />  

            <q-list highlight >
                <q-item class="desktop-only">
                   <q-item-side left />
                    <q-item-main
                    label-lines="1"
                    sublabel-lines="1"
                    dense
                  
                    > 
                    <q-item-tile sublabel>
                        <div class="row">
                            <div class="col-md-1 listCol">Servis No </div>
                            <div class="col-md-2 listCol">Marka / Model </div>
                            <div class="col-md-2 listCol">Serino </div>
                            <div class="col-md-5 listCol">Açıklama </div>
                            <div class="col-md-2 listCol">Durum </div>
                        </div>
                        </q-item-tile>
                </q-item-main>
                </q-item>
                    <q-item  v-for="item of filteredData.slice(0,300)" :key="item.id">
                        <q-item-side left :icon="getIcon(item.islemDurumu)" />
                        <q-item-main
                        label-lines="1"
                        sublabel-lines="3"
                        dense
                        @click.native="rowClick(item.id)"
                        > 
                        <q-item-tile label > {{item.cariAdi}}</q-item-tile>
                        <q-item-tile sublabel>
                            <div class="row">
                                <div class="col-md-1 listCol">{{item.id}} </div>
                                <div class="col-md-2 listCol">{{item.marka}} {{item.model}} </div>
                                <div class="col-md-2 listCol">{{item.serino}} </div>
                                <div class="col-md-5 listCol">{{item.aciklama}} </div>
                                <div class="col-md-2 listCol">{{getIslemDurumu(item.islemDurumu)}} </div>
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
import cihazList from "./cihazList";

import { uid, filter } from "quasar";

const module = {
  data() {
    return {
      //file upload
      url: this.apiUrl + "upload",
      headers: {
        Authorization:
          "Bearer " + localStorage.getItem("vue-authenticate.vueauth_token")
      },

      //modal instance
      modal: false,

      //tüm kayıtlar
      servisler: [],

      //upload varsa submit etme, önce uploadları gönder
      uploadVar: false,

      //ara.
      filterText: "",

      //tıklanınca alınan index değer. users[index]
      id: "",

      index: "",

      errors: {},

      //detay
      currentServis: {},

      currentCari: {},
      currentCihaz: {},

      cariToggle: false,
      cihazToggle: false,

      yetkiler: {},

      cariModal: false,
      cihazModal: false,
      cihazListModal: false,

      guard: {},

      cariList: [],

      cihazList: [],

      teknisyen: [],

      islemDurumu: [
        { label: "Servis Kabul", value: "1" },
        { label: "Müşteri Talebi", value: "2" },
        { label: "İşlem Yapılıyor", value: "3" },
        { label: "Parça Bekliyor", value: "4" },
        { label: "Dış Serviste", value: "5" },
        { label: "İptal Edildi", value: "6" },
        { label: "Onay Bekliyor", value: "7" },
        { label: "Tamamlandı", value: "8" },
        { label: "Teslim Edildi", value: "9" }
      ]
    };
  },

  created() {
    this.getRole();

    this.getList();

    window.addEventListener("keydown", this.fnkey);
  },

  watch: {
    uploadVar(val) {
      if (val === false) {
        this.postData();
      }
    }
  },
  components: {
    cihazList
  },
  methods: {

    getIslemDurumu(id){
        let index = this.islemDurumu.findIndex(x => x.value === id);
        return this.islemDurumu[index].label;
    },
    //f10 basınca liste aç.
    fnkey(event) {
      event.defaultPrevented;

      if (event.keyCode == "121") {
        this.cihazListModal = true;
      }
    },

    onClickChild(obj) {
      this.currentServis.cihaz_id = obj.id;
      this.currentServis.cihazAdi = obj.adi;
      this.cihazListModal = false;
    },

    cariKaydet() {
      axios
        .post(this.apiUrl + "newCari", this.currentCari)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            this.cariModal = false;
            this.currentCari = {};
            notify(res.data.msg);
          }
        })
        .catch(function(error) {
          notify(error.response.data.error, true);
        });
    },

    //autocompolete için..
    cariAra(terms, done) {
      axios
        .get(this.apiUrl + "listShortCari")
        .then(response => {
          this.cariList = response.data;
          this.cariAraComplete(terms, done);
        })
        .catch(e => {
          console.log(e);
        });
    },

    //from searched..
    cariAraComplete(terms, done) {
      let searchText = this.currentServis.cariAdi.toLocaleLowerCase("tr-TR");

      let sonuc = [];
      let ver = this.cariList.filter(p => {
        return p.adi !== null
          ? p.adi.toLocaleLowerCase("tr-TR").includes(searchText)
          : "";
      });

      let arr = [];

      ver.forEach(e => {
        arr.push({
          label: e.adi,
          value: e.id,
          sublabel: e.yetkili + " " + e.telefon
        });
      });

      return done(arr);
    },

    //autocomplete seçilen..
    cariAraSelected(item) {
      this.currentServis.cariAdi = item.label;
      this.currentServis.cari_id = item.value;
    },

    cihazKaydet() {
      this.currentCihaz.cari_id = this.currentServis.cari_id;
      console.log(this.currentCihaz);
      axios
        .post(this.apiUrl + "newCihaz", this.currentCihaz)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            this.cihazModal = false;
            this.currentCihaz = {};
            notify(res.data.msg);
          }
        })
        .catch(function(error) {
          notify(error.response.data.error, true);
        });
    },

    cihazAra(terms, done) {
      if (this.cihazList.length == 0) {
        axios
          .get(this.apiUrl + "listShortCihazId/" + this.currentServis.cari_id)
          .then(response => {
            console.log(response.data);
            this.cihazList = response.data;
            this.cihazAraComplete(terms, done);
          })
          .catch(e => {
            console.log(e);
          });
      } else {
        this.cihazAraComplete(terms, done);
      }
    },

    cihazAraComplete(terms, done) {
      let searchText = this.currentServis.cihazAdi.toLocaleLowerCase("tr-TR");
      let sonuc = [];

      console.log(this.cihazList);

      let ver = this.cihazList.filter(p => {
        p.adi === null ? (p.adi = "") : p.adi;
        p.serino === null ? (p.serino = "") : p.serino;
        return (
          p.adi.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.serino.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1
        );
      });
      let arr = [];

      ver.forEach(e => {
        arr.push({
          label: e.adi,
          value: e.id,
          sublabel: e.marka + " " + e.model + " " + e.aciklama
        });
      });

      return done(arr);
    },

    //autocomplete seçilen..
    cihazAraSelected(item) {
      this.currentServis.cihazAdi = item.label;
      this.currentServis.cihaz_id = item.value;
    },

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

    sil(id) {
      this.$q
        .dialog({
          title: "Servis Sil",
          message: "Servis Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + "deleteServis?id=" + id)
            .then(response => {
              if (response.data.status === true) {
                let index = this.servisler.findIndex(x => x.id === id);
                this.servisler.splice(index, 1);
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
    getList() {
      axios
        .get(this.apiUrl + "listServis")
        .then(response => {
          this.servisler = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    //Tüm Liste..
    getTeknisyen() {
      axios
        .get(this.apiUrl + "listeleTeknisyen")
        .then(response => {
          console.log(response.data);
          this.teknisyen = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },

    //yeni modal açar..
    yeniServis() {
      this.errors = {};
      this.modal = true;
      this.currentServis = {};
      this.getTeknisyen();
    },

    //eğer kullanıcı yeniyse veya yetki tablosu boşsa default değerleri getirir. (table: yetkiDefault)
    defaultYetkiler() {
      axios.get(this.apiUrl + "yetkiDefault").then(response => {
        this.yetkiler = response.data;
      });
    },

    //dosya yükleme bitince watch ile takip et.. Sonra submit et..
    finishUpload() {
      this.uploadVar = false;
    },

    //Dosya yükle. Gelen değeri currentServis'a at..
    postUpload(file, xhr) {
      let sonuc = JSON.parse(xhr.response);
      if (sonuc.status === true) {
        this.currentServis.photo = sonuc.file;
      }
    },

    //kullanıcı seçilince index den mevcut değerleri currentServis'a at..
    rowClick(id) {
      //id'den users'daki indexi bul..
      let index = this.servisler.findIndex(x => x.id === id);
      this.id = index;

      axios.get(this.apiUrl + "getServis/" + id).then(response => {
        console.log(response.data);
        this.currentServis = response.data;
      });

      /*
   

      this.currentServis = Object.assign({}, this.servisler[index]);
      */
      this.errors = {};

      this.modal = true;
    },

    submit() {
      this.postData();
    },

    postData() {
      //currentServis'a yetkileri ata..
      //this.currentServis.yetkiler = this.yetkiler;
      axios
        .post(this.apiUrl + "newServis", this.currentServis)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            if (!this.currentServis.id) {
              this.getList();
            }

            this.servisler[this.id] = this.currentServis;

            this.modal = false;

            notify(res.data.msg);
          }
        })
        .catch(function(error) {
          notify(error.response.data.error, true);
        });
    },
    search(user) {
      return Object.keys(this).every(key => user[key] === this[key]);
    },
    getIcon(icon) {
      switch (icon) {
        case "1":
          return "assignment_returned";
          break;

        default:
          return "devices";
          break;
      }
    }
  },

  computed: {
    kaydetBtn() {
      if (this.currentServis.id) {
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
