<template>
  <div class="q-pa-sm" v-if="guard.giris==='1'">
    <q-tabs color="faded" inverted no-pane-border>
      <q-tab alert default slot="title" name="tab-1" label="Servis" />
      <q-tab v-if="currentServis.id" slot="title" name="tab-2" label="İşlemler" />
      <q-tab v-if="currentServis.id" slot="title" name="tab-3" label="Özet" />

      <!-- tab servis -->
      <q-tab-pane name="tab-1">
        <q-toolbar slot="header" color="faded">
          <q-toolbar-title>Servis Bilgileri</q-toolbar-title>
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
                  <div v-if="currentServis.cari_id">
                    <q-field label=" " :label-width="3" class="fip">
                      <q-input stack-label="Cari Adı" :value="currentServis.get_cari.adi" disabled />
                    </q-field>

                    <q-field label=" " :label-width="3" class="fip">
                      <q-input stack-label="Yetkili" :value="currentServis.get_cari.yetkili" disabled />
                    </q-field>
                    <q-field label=" " :label-width="3" class="fip">
                      <q-input stack-label="Telefon" :value="currentServis.get_cari.telefon" disabled />
                    </q-field>
                  </div>
                </div>
                <div class="col-xs-1" style="text-align:center;margin-top:5px;">
                  <q-btn icon="add" size="sm" round @click="cariModal = !cariModal"></q-btn>
                </div>
              </div>
              <div class="row" v-if="currentServis.cari_id">
                <div class="col-xs-11">
                  <q-field label="Cihaz Seçin" :label-width="3" class="fip">
                    <q-search v-model="currentServis.cihazAdi" placeholder="Cihaz adı, Seri No" ref="cihazAdi">
                      <q-autocomplete max-results='999' @search="cihazAra" @selected="cihazAraSelected" />
                    </q-search>
                    <span class="errMsg" v-if="errors.cihaz_id">{{ errors.cihaz_id }}</span>
                  </q-field>
                  <div v-if="currentServis.cihaz_id">
                    <q-field label=" " :label-width="3" class="fip">
                      <q-input stack-label="Cihaz Adı" :value="currentServis.get_cihaz.adi" disabled />
                    </q-field>

                    <q-field label=" " :label-width="3" class="fip">
                      <q-input stack-label="Marka / Model " :value="currentServis.get_cihaz.marka + ' '+ currentServis.get_cihaz.model" disabled />
                    </q-field>
                    <q-field label=" " :label-width="3" class="fip">
                      <q-input stack-label="Seri no" :value="currentServis.get_cihaz.serino" disabled />
                    </q-field>
                  </div>
                </div>
                <div class="col-xs-1" style="text-align:center;margin-top:5px;">
                  <q-btn icon="add" size="sm" round @click="cihazModal = !cihazModal"></q-btn>
                </div>

                <div class="col-sm-11">
                  <q-field label="İşlem Durumu" :label-width="3" class="fip">
                    <q-select v-model="currentServis.islemDurumu" radio :options="islemDurumlari" />
                  </q-field>
                  <q-field label="Teknisyen" :label-width="3" class="fip">
                    <q-select v-model="currentServis.teknisyen" radio :options="teknisyen" />

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
                    <q-input v-model="currentServis.ekParca" />
                  </q-field>
                  <q-field label="Açıklama" :label-width="3" class="fip">
                    <q-input type="textarea" v-model="currentServis.aciklama" />
                  </q-field>
                  <q-field label="Tarih" :label-width="6" class="fip">
                    <q-datetime v-model="currentServis.tarih" type="date" />
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
      </q-tab-pane>
      <!-- tab islemler -->
      <q-tab-pane name="tab-2">
        <q-btn icon="add" size="md" v-if="kaydetBtn" label="Ekle" @click="islemModal = !islemModal" class="q-pa-sm" />
        <div style="clear:both">&nbsp;</div>
        <q-list highlight separator>
          <q-item class="desktop-only">

            <q-item-main label-lines="1" sublabel-lines="1" dense>
              <q-item-tile sublabel>
                <div class="row">

                  <div class="col-md-2 listCol">Tarih </div>
                  <div class="col-md-2 listCol">Kullanıcı </div>
                  <div class="col-md-7 listCol">Açıklama</div>

                </div>
              </q-item-tile>
            </q-item-main>

          </q-item>
          <q-item v-for="item of currentServis.get_islem" :key="item.id">
            <q-item-main label-lines="1" sublabel-lines="3" dense>
              <q-item-tile label> {{item.adi}}</q-item-tile>
              <q-item-tile sublabel>
                <div class="row">
                  <div class="col-md-2 listCol">{{formatDate(item.tarih)}} </div>
                  <div class="col-md-2 listCol">{{item.user}} </div>
                  <div class="col-md-7 listCol">{{item.aciklama}} </div>

                  <div v-if="item.photo" class="col-md-1 listCol">
                    <a :href="fileUrl+'/'+item.photo" target="_blank">
                      <q-icon name="assignment" size="20px" />
                    </a>
                  </div>
                </div>
              </q-item-tile>
            </q-item-main>
            <q-item-tile label>
              <q-icon v-if="guard.sil=='1'" @click.native="islemSil(item.id)" name="delete" size="20px" color="faded" /></q-item-tile>
          </q-item>

        </q-list>

      </q-tab-pane>
      <!-- tab ozet -->
      <q-tab-pane name="tab-3">
        <div id="yazdirAlani">
          <div class="rows12">
            <table class="greyGridTable">
              <tr>
                <td width="30%">Link</td>
                <td>
                  <a style="cursor:pointer" @click="$router.push('/servisid/'+currentServis.key)">{{currentServis.key}}</a>
                </td>
              </tr>
              <tr>
                <td width="30%">İşlem Durumu</td>
                <td> {{currentServis.get_durum.label}}</td>
              </tr>
              <tr>
                <td width="30%">Fiş No</td>
                <td>{{currentServis.id}}</td>
              </tr>
              <tr>
                <td width="30%">Tarih</td>
                <td>{{formatDate(currentServis.created_at)}}</td>
              </tr>
              <tr>
                <td width="30%">Fiyat</td>
                <td>{{currentServis.fiyat}} </td>
              </tr>
              <tr>
                <td width="30%">Teknisyen</td>
                <td>{{currentServis.get_teknisyen.name}}</td>
              </tr>
              <tr>
                <td width="30%">Açıklama</td>
                <td>{{currentServis.aciklama}}</td>
              </tr>
              <tr>
                <td width="30%">Ek Parça</td>
                <td>{{currentServis.ekParca}}</td>
              </tr>
            </table>
          </div>

          <div class="rows6" v-if="currentServis.get_cihaz.id">

            <table class="greyGridTable">

              <tr>
                <td width="30%">Adı</td>
                <td>{{currentServis.get_cihaz.adi}}</td>
              </tr>
              <tr>
                <td width="30%">Marka / Model</td>
                <td>{{currentServis.get_cihaz.marka}} {{currentServis.get_cihaz.model}}</td>
              </tr>
              <tr>
                <td width="30%">Seri no</td>
                <td>{{currentServis.get_cihaz.serino}}</td>
              </tr>
              <tr>
                <td width="30%">Barkod</td>
                <td>{{currentServis.get_cihaz.barkod}}</td>
              </tr>
              <tr>
                <td width="30%">Garanti Bitiş</td>
                <td>{{currentServis.get_cihaz.garantiTarih}} </td>
              </tr>
              <tr>
                <td width="30%">Açıklama</td>
                <td>{{currentServis.get_cihaz.aciklama}} </td>
              </tr>
            </table>

          </div>
          <div class="rows6">
            <table class="greyGridTable">

              <tr>
                <td width="30%">Cari</td>
                <td>{{currentServis.get_cari.adi}}</td>
              </tr>
              <tr>
                <td width="30%">Yetkili</td>
                <td>{{currentServis.get_cari.yetkili}}</td>
              </tr>
              <tr>
                <td width="30%">Telefon</td>
                <td>{{currentServis.get_cari.telefon}}</td>
              </tr>
              <tr>
                <td width="30%">Adres</td>
                <td>{{currentServis.get_cari.adres}}</td>
              </tr>
            </table>

          </div>
          <div style="clear:both">&nbsp;</div>
          <p class="text-bold">İşlemler</p>
          <div class="rows12">

            <table class="greyGridTable">
              <tr>
                <th width="15%">Tarih</th>
                <th width="15%">Adı</th>
                <th width="50%">Acıklama</th>
                <th width="30%">Kullanıcı</th>
              </tr>
              <tr v-for="item in currentServis.get_islem">
                <td width="15%">{{formatDate(item.tarih)}}</td>
                <td width="15%">{{item.adi}}</td>
                <td width="50%">{{item.aciklama}}</td>
                <td width="30%">{{item.user}}</td>
              </tr>
            </table>

          </div>

        </div>
        <q-btn label="yazdır" @click.native="yazdir()" />
        <!--<barcode :value="currentServis.id" :options="{ displayValue: true }"></barcode>-->

      </q-tab-pane>

    </q-tabs>

    <q-modal v-model="cariModal" :content-css="{ zIndex:'9999',minWidth: '50vw', minHeight:'410px'}">
      <q-modal-layout>
        <q-toolbar color="faded" inverted>
          <q-toolbar-title>Cari Kaydet</q-toolbar-title>
          <q-btn flat round dense @click="cariModal = false" wait-for-ripple icon="close" />
        </q-toolbar>
        <cari-kaydet @cariKaydetEmit="cariKaydetEmit" />
      </q-modal-layout>
    </q-modal>
    <q-modal v-model="cihazModal" :content-css="{ zIndex:'99999',minWidth: '50vw', minHeight:'430px'}">
      <q-modal-layout>
        <q-toolbar color="faded" inverted>
          <q-toolbar-title>Cihaz Kaydet</q-toolbar-title>
          <q-btn flat round dense @click="cihazModal = false" wait-for-ripple icon="close" />
        </q-toolbar>
        <cihaz-kaydet :cariId="currentServis.cari_id" @cihazKaydetEmit="cihazKaydetEmit" />
      </q-modal-layout>
    </q-modal>
    <q-modal v-model="cihazListModal" :content-css="{ zIndex:'99999',minWidth: '50vw', minHeight:'65vh'}">
      <q-modal-layout>
        <q-toolbar color="faded" inverted>
          <q-toolbar-title>Cihaz Seç</q-toolbar-title>
          <q-btn flat round dense @click="cihazListModal = false" wait-for-ripple icon="close" />
        </q-toolbar>
        <cihaz-list v-if="currentServis.cari_id" :cari_id="currentServis.cari_id" @cihazListEmit="cihazListEmit" />
      </q-modal-layout>
    </q-modal>

    <q-modal v-model="islemModal" :content-css="{ zIndex:'99999',minWidth: '50vw', minHeight:'360px'}">
      <q-modal-layout>
        <q-toolbar color="faded" inverted>
          <q-toolbar-title>İşlem Kaydet</q-toolbar-title>
          <q-btn flat round dense @click="islemModal = false" wait-for-ripple icon="close" />
        </q-toolbar>
        <islem-kaydet :servisId="currentServis.id" @islemKaydetEmit="islemKaydetEmit" />
      </q-modal-layout>
    </q-modal>

  </div>

</template>

<script>
import axios from "axios";
import notify from "../pages/notify";
import cihazKaydet from "../components/cihazKaydet";
import cariKaydet from "../components/cariKaydet";
import cihazList from "../components/cihazList";
import islemKaydet from "../components/islemKaydet";

const module = {
  components: { cihazKaydet, cariKaydet, cihazList, islemKaydet },
  data () {
    return {
      errors: {},

      cariList: [],

      cihazList: [],

      teknisyen: [],
      cariModal: false,
      cihazModal: false,
      cihazListModal: false,
      islemModal: false,

      currentServis: {},


      islemDurumlari: [],

      imageSrc: ''
    };
  },
  mounted () {
    document.addEventListener('deviceready', this.onDeviceReady, false)

  },
  created () {
    /*this.getRole();
    this.getTeknisyen();
    this.getServis();
    this.getIslemDurumlari();
*/

    this.servisInit();
    this.getServis();
    this.currentServis.get_cari = [];
    this.currentServis.get_cihaz = [];
    this.currentServis.get_islem = [];
    this.currentServis.get_durum = [];
    this.currentServis.get_teknisyen = [];
    window.addEventListener("keydown", this.fnkey);
  },

  methods: {
    onDeviceReady () {

    },


    formatDate (date) {
      if (!date)
        return
      let nDate = new Date(date);
      let fDate = new Date(nDate.toISOString().split('T')[0]);
      return fDate.toLocaleDateString();

    },
    islemSil (id) {
      this.$q
        .dialog({
          title: "İşlem Sil",
          message: "İşlem Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + 'islemSil/' + id)
            .then((response) => {
              let index = this.currentServis.get_islem.findIndex(x => x.id === id);
              this.currentServis.get_islem.splice(index, 1);

            })
            .catch({})
        });
    },
    yazdir () {
      window.print();
    },
    servisInit () {
      axios
        .get(this.apiUrl + "servisInit?bolum=servis")
        .then(response => {
          this.teknisyen = response.data.teknisyenler;
          this.islemDurumlari = response.data.islemDurumlari;


          /* if (response.data.yetkiler.original.role == "super") {
             response.data.yetkiler.original.giris = "1";
             response.data.yetkiler.original.yeni = "1";
             response.data.yetkiler.original.duzelt = "1";
             response.data.yetkiler.original.sil = "1";
           }
           this.guard = response.data.yetkiler.original;
           */

        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    getTeknisyen () {
      axios
        .get(this.apiUrl + "teknisyenListele")
        .then(response => {
          this.teknisyen = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    getIslemDurumlari () {
      axios
        .get(this.apiUrl + "islemDurumlariListele")
        .then(response => {
          console.log(response.data);
          this.islemDurumlari = response.data;
        })
        .catch(e => {
          console.log(e);
          this.errors.push(e);
        });
    },
    //ilgili servisi getir
    getServis () {
      this.currentServis.get_cari = {};
      this.currentServis.get_cihaz = {};
      if (this.$route.params.id) {
        axios
          .get(this.apiUrl + "servisGetir/" + this.$route.params.id)
          .then(response => {
            console.log(response.data);
            this.currentServis = response.data.servis;
            if (!this.currentServis.get_teknisyen)
              this.currentServis.get_teknisyen = [];
            if (!this.currentServis.get_cihaz)
              this.currentServis.get_cihaz = [];

            this.currentServis.cariAdi = response.data.servis.get_cari.adi;
            this.currentServis.cihazAdi = response.data.servis.get_cihaz.adi;
          })
          .catch(e => {
            this.errors.push(e);
          });
      }
    },

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

    //cihaz kaydet comp. gelen
    cihazKaydetEmit (val) {
      this.cihazModal = false;
    },
    islemKaydetEmit (val) {
      this.currentServis.get_islem.unshift(val);
      this.islemModal = false;
    },
    cariKaydetEmit (val) {
      this.cariModal = false;
    },
    cihazListEmit (val) {
      console.log(val);
      this.currentServis.cihaz_id = val.id;
      this.currentServis.cihazAdi = val.adi;
      this.currentServis.get_cihaz.adi = val.adi;
      this.currentServis.get_cihaz.marka = val.marka;
      this.currentServis.get_cihaz.model = val.model;
      this.currentServis.get_cihaz.serino = val.serino;
      this.cihazListModal = false;
    },

    //f10 basınca liste aç.
    fnkey (event) {
      event.defaultPrevented;
      if (event.keyCode == "121") {
        this.cihazListModal = true;
      }
    },

    //autocompolete için..
    cariAra (terms, done) {
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
    cariAraComplete (terms, done) {
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
    cariAraSelected (item) {
      //seçilince diğer cari bilgileri de cariSelected'a at.
      let index = this.cariList.findIndex(x => x.id === item.value);
      this.currentServis.get_cari = this.cariList[index];

      this.currentServis.cariAdi = item.label;
      this.currentServis.cari_id = item.value;
    },

    cihazAra (terms, done) {
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

    cihazAraComplete (terms, done) {
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
          sublabel: 'Marka : ' + e.marka + "  " + e.model + " Lokasyon: " + e.lokasyon + ' SeriNo: ' + e.serino
        });
      });

      return done(arr);
    },

    //autocomplete seçilen..
    cihazAraSelected (item) {
      let index = this.cihazList.findIndex(x => x.id === item.value);
      this.currentServis.get_cihaz = this.cihazList[index];

      this.currentServis.cihazAdi = item.label;
      this.currentServis.cihaz_id = item.value;
    },

    sil (id) {
      this.$q
        .dialog({
          title: "Servis Sil",
          message: "Servis Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + "servisSil?id=" + id)
            .then(response => {
              if (response.data.status === true) {
                this.$emit("servisSilEmit", id);
                this.$router.push("/servisler");
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
    submit () {
      console.log(this.currentServis);
      axios
        .post(this.apiUrl + "servisKaydet", this.currentServis)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin! " + res.data.msg, true);
          } else {
            notify(res.data.msg);

            if (res.data.islemler.id)
              this.currentServis.get_islem.push(res.data.islemler);

            if (!this.currentServis.id) this.$router.push("/servisler");

            this.errors = {};
            // this.$emit("servisEmit", this.currentServis);
            // this.$router.push("/servisler");

            //yeni kayıtsa listeyi güncelle
            /* if (!this.currentServis.id) {
              this.getList();
            }*/
          }
        })
        .catch(function (error) {
          notify(error.response.data.error, true);
        });
    }
  },

  computed: {
    guard () {
      return this.$store.state.auth.guard.servis;
    },
    kaydetBtn () {
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
