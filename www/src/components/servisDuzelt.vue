<template>
  <div>
    <q-modal v-model="cariModal" :content-css="{ zIndex:'9999',minWidth: '50vw', minHeight:'360px'}">
        <q-modal-layout>
          <cari-kaydet @cariKaydetEmit="cariKaydetEmit"/>
        </q-modal-layout>
    </q-modal>
    <q-modal v-model="cihazModal" :content-css="{ zIndex:'99999',minWidth: '50vw', minHeight:'380px'}">
        <q-modal-layout>
            <cihaz-kaydet :cariId="currentServis.cari_id" @cihazKaydetEmit="cihazKaydetEmit" />
        </q-modal-layout>
    </q-modal>
    <q-modal v-model="cihazListModal" :content-css="{ zIndex:'99999',minWidth: '50vw', minHeight:'65vh'}">
        <q-modal-layout>
            <q-toolbar color="faded" inverted>
                <q-toolbar-title>Cihaz Seç</q-toolbar-title>
                <q-btn flat round dense @click="cihazListModal = false" wait-for-ripple icon="close" />
            </q-toolbar>
            <cihaz-list @cihazListEmit="cihazListEmit"/>
        </q-modal-layout>
    </q-modal>
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
                                                <q-checkbox v-model="currentServis.fatura" true-value="1" false-value="0" />
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
                                        <q-btn align="right" v-if="guard.sil=='1'" color="negative" @click="sil(currentServis.id)" icon="delete"></q-btn>
                                       
                                    </q-field>
                                </div>
                        </div>
                    </div>
                  
                </div>
            </div> 
    </div>
</template>

<script>
import axios from "axios";
import notify from "../pages/notify";
import cihazKaydet from "./cihazKaydet";
import cariKaydet from "./cariKaydet";
import cihazList from "./cihazList";

const module = {
  props: ["currentServis", "guard"],
  components: { cihazKaydet, cariKaydet, cihazList },
  data() {
    return {
      errors: {},
      cariList: [],
      cihazList: [],
      teknisyen: [],
      cariModal: false,
      cihazModal: false,
      cihazListModal:false,
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
    this.getTeknisyen();
    window.addEventListener("keydown", this.fnkey);
  },

  methods: {
    //cihaz kaydet comp. gelen
    cihazKaydetEmit(val) {
      this.cihazModal = false;
    },
    cariKaydetEmit(val) {
      this.cariModal = false;
    },
    cihazListEmit(val) {
      console.log(val);
      this.currentServis.cihaz_id=val.id;
      this.currentServis.cihazAdi=val.adi;
      this.cihazListModal=false;
    },

    //f10 basınca liste aç.
    fnkey(event) {
      event.defaultPrevented;
      if (event.keyCode == "121") {
        this.cihazListModal = true;
      }
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

    cihazAra(terms, done) {
      if (this.cihazList.length == 0) {
        axios
          .get(this.apiUrl + "listShortCihazId/" + this.currentServis.cari_id)
          .then(response => {
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
                this.$emit('servisSil',id);
                notify(response.data.msg);
              } else {
                notify(response.data.msg, true);
              }
            })
            .catch(e => {
              this.errors.push(e);
            });
        });
    },

    getTeknisyen() {
      axios
        .get(this.apiUrl + "listeleTeknisyen")
        .then(response => {
          this.teknisyen = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },

    submit() {
      axios
        .post(this.apiUrl + "newServis", this.currentServis)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            this.$emit("servisEmit", this.currentServis);

            //yeni kayıtsa listeyi güncelle
            /* if (!this.currentServis.id) {
              this.getList();
            }*/

            notify(res.data.msg);
          }
        })
        .catch(function(error) {
          notify(error.response.data.error, true);
        });
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
    }
  }
};

export default module;
</script>
