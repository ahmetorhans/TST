<template>
    <q-page v-if="guard.giris">
   <q-modal  v-model="userModal" :content-css="{ minWidth: '75vw', minHeight:'99vh'}">
     <q-modal-layout>
        
        <q-toolbar slot="header" color="secondary">
          <q-toolbar-title>Cari Bilgileri</q-toolbar-title>
           <q-btn flat round dense @click="userModal = false" wait-for-ripple icon="close" />
        </q-toolbar>
        
              <div class="layout-padding">
         
                <div class="row"> 
                  <div class="col-sm-10">
                   <q-field label="Türü" :label-width="3" class="fip">
                      <q-btn-toggle
                          v-model="currentCari.turu"
                          toggle-color="secondary"
                          no-caps	
                          :options="[
                            {label: 'Kurum', value: 'kurum'},
                            {label: 'Kişi', value: 'kisi'},
                          ]"
                        />
                      </q-field>
                  
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
                        <div class="col-sm-6">
                              <q-field label="Vergi no" :label-width="3" class="fip">
                                  <q-input v-model="currentCari.vergiNo"  />
                              </q-field>
                        </div>
                        <div class="col-sm-6" v-if="currentCari.turu=='kurum'">
                              <q-field label="Vergi Dairesi" :label-width="3" class="fip">
                                  <q-input v-model="currentCari.vergiDairesi"  />
                              </q-field>
                        </div>
                        <q-field label="Açıklama" :label-width="3" class="fip">
                          <q-input  type="textarea" v-model="currentCari.aciklama"  />
                      </q-field>
                      <q-field label="Durum" :label-width="3"  class="fip">
                          <q-select
                              v-model="currentCari.durum"
                              radio
                              :options="selectOptions"
                            />
                      </q-field>
                      <br />
                      
                      <q-card flat >
                          <q-card-title>
                            Giriş bilgileri
                          </q-card-title> 
                          <q-card-main>
                          <q-field label="Eposta Adresi" :label-width="3" class="fip" >
                                <q-input v-model="currentCari.email" :class="{'has-error': errors.email}" />
                                  <span class="errMsg" v-if="errors.email">{{ errors.email }}</span>
                            </q-field>

                            <q-field label="Parola" :label-width="3" class="fip">
                                <q-input  v-model="currentCari.password" type="password" :class="{'has-error': errors.password}" />
                                <span class="errMsg" v-if="errors.password">{{ errors.password }}</span>
                            </q-field>
                          </q-card-main>
                      </q-card>   
              
                      <q-field class="fip">
                        <q-btn color="secondary" @click="submit" v-if="kaydetBtn">Kaydet</q-btn>
                        <span v-if="currentCari.id">
                            <q-btn align="right"  icon="delete" v-if="guard.sil" color="negative" @click="sil(currentCari.id)"></q-btn>
                        </span>
                      </q-field>
                </div>
                
              </div> 
              
            </div>

         
 </q-modal-layout>
     
    </q-modal>
  
    <div class="row q-pa-sm" >
       <div class="col-xs-12 col-md-12" >
       
          <q-toolbar slot="header" color="faded">
          <q-toolbar-title>Cari Bilgileri</q-toolbar-title>
         <q-btn v-if="guard.yeni" flat round dense @click="yeni()" wait-for-ripple icon="add" />
            
          </q-toolbar>
        
        <q-search
          v-model="filterText"
          :debounce="600"
          placeholder="Adı veya yetkiliye göre ara"
          icon="search"
          float-label="Ara"
        />
        
        
        <q-list highlight inset-separator>
          <q-item  v-for="item of filteredData.slice(0,100)" :key="item.id">
              <q-item-side left icon="person_pin" />

             
              <q-item-main
                :label=item.adi
                label-lines="1"
                sublabel-lines="3"
                dense
              @click.native="rowClick(item.id)"
              > 
          
              <q-item-tile sublabel>{{item.yetkili}}</q-item-tile>
            </q-item-main>
            <q-item-side right ></q-item-side>
          </q-item>
        </q-list>
              
            
        </div>
      </div>
  
  </q-page>

</template>

<style>

</style>

<script>
import axios from "axios";
import store from "../store";
import notify from "./notify";

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
      userModal: false,

      //tüm kullanıcılar
      cariler: [],

      //upload varsa submit etme, önce uploadları gönder
      uploadVar: false,

      //kullanıcı ara.
      filterText: "",

      //kullanıcı tıklanınca alınan index değer. users[index]
      id: "",

      errors: {},

      //detay kullanıcı
      currentCari: [],

      yetkiler: {},

      guard: {},

      userVarmi: false,

      selectedTab: "tab-1",

      selectOptions: [
        { label: "Aktif", value: "1" },
        { label: "Pasif", value: "0" }
      ]
    };
  },

  created() {
    //cari liste..
    this.getRole();
    this.getList();
    
  },

  watch: {
    uploadVar(val) {
      if (val === false) {
        this.postData();
      }
    }
  },

  methods: {
    success(pos) {},
    getRole() {
      axios
        .get(this.apiUrl + "yetkiler?bolum=cari")
        .then(response => {
          console.log(response.status)
          
          if (response.data.role == "super") {
            response.data.giris = "1";
            response.data.yeni = "1";
            response.data.duzelt = "1";
            response.data.sil = "1";
          }
          this.guard = response.data;
        })
        .catch(e => {
         
            console.log(e)
        });
    },
    sil(id) {
      this.$q
        .dialog({
          title: "Cari Sil",
          message: "Cari Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + "deleteCari?id=" + id)
            .then(response => {
              if (response.data.status === true) {
                let index = this.cariler.findIndex(x => x.id === id);

                this.cariler.splice(index, 1);
                this.userModal = false;
                notify(response.data.msg);
              } else notify(response.data.msg, true);
            })
            .catch(e => {
              this.errors.push(e);
            });
        });
    },

    //kullanıcıları getir..
    getList() {
      axios
        .get(this.apiUrl + "listCari")
        .then(response => {
          this.cariler = response.data;
          
          if (this.$route.params.cari) {
            this.rowClick(parseInt(this.$route.params.cari));
          }
            console.log(response.data);
        })
        .catch(e => {
          this.errors.push(e);
        });
    },

    //yeni modal açar..
    yeni() {
      this.errors = {};
      this.userModal = true;
      this.currentCari = {};
      this.selectedTab = "tab-1";
      this.currentCari.turu="kurum";
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

    //Dosya yükle. Gelen değeri currentCari'a at..
    postUpload(file, xhr) {
      let sonuc = JSON.parse(xhr.response);
      if (sonuc.status === true) {
        this.currentCari.photo = sonuc.file;
      }
    },

    //kullanıcı seçilince index den mevcut değerleri currentCari'a at..
    rowClick(id) {
     
      this.selectedTab = "tab-1";
      
      //id'den users'daki indexi bul..
      let index = this.cariler.findIndex(x => x.id === id);

      this.id = index;
    
     // this.currentCari = Object.assign({}, this.cariler[index]);
      axios.get(this.apiUrl + "getCari/" + id).then(response => {
        this.currentCari = response.data;
      
      });

      this.errors = {};

      this.userModal = true;
    },

    submit() {
      this.postData();
    },

    postData() {
      //currentCari'a yetkileri ata..
      this.currentCari.yetkiler = this.yetkiler;
      axios
        .post(this.apiUrl+"newCari", this.currentCari)
        .then(res => {
          if (res.data.status === false) {
            console.log(res.data);
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            if (!this.currentCari.id) {
              this.getList();
            }
            //dosyaları boşalt..
            //  this.$refs.uploader.files = [];

            //yeni bilgileri users'a at..
            this.cariler[this.id] = this.currentCari;

            this.userModal = false;

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
      if (this.currentCari.id) {
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
    isAuthenticated() {
      return this.$store.getters.isAuthenticated;
    },
    //kullanıcı listesindan ara..
    filteredData() {
      if (!this.filterText) return this.cariler;

      let searchText = this.filterText.toLocaleLowerCase("tr-TR");

      return this.cariler.filter(p => {
        p.adi === null ? (p.adi = "") : p.adi;
        p.yetkili === null ? (p.yetkili = "") : p.yetkili;
        return (
          p.adi.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.yetkili.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1
        );
      });
    }
  }
};

export default module;
</script>
