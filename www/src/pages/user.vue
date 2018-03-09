<template>
  <q-page v-if="guard.giris">
   <q-modal v-model="userModal" :content-css="{ minWidth: '60vw', minHeight:'600px'}">
     <q-modal-layout>
        
        <q-toolbar slot="header" color="secondary">
          <q-toolbar-title>Kullanıcı işlemleri</q-toolbar-title>
           <q-btn flat round dense @click="userModal = false" wait-for-ripple icon="close" />
        </q-toolbar>

        <div class="layout-padding">

              <q-field label="Adı Soyadı" :label-width="3" >
                  <q-input v-model="currentUser.name" :class="{'has-error': errors.name}" required/>
                   <span class="errMsg" v-if="errors.name">{{ errors.name }}</span>
              </q-field>

              <q-field label="Eposta Adresi" :label-width="3" class="fip" >
                   <q-input v-model="currentUser.email" :class="{'has-error': errors.email}" />
                    <span class="errMsg" v-if="errors.email">{{ errors.email }}</span>
              </q-field>

              <q-field label="Parola" :label-width="3" class="fip">
                  <q-input v-model="currentUser.password" type="password" :class="{'has-error': errors.password}" />
                   <span class="errMsg" v-if="errors.password">{{ errors.password }}</span>
              </q-field>

              <q-field label="Yetki" :label-width="3"  class="fip">
                <q-select
                    v-model="currentUser.role"
                    radio
                    :options="selectOptions"
                  />
              </q-field>
               <q-field label="Fotoğraf" :label-width="3" class="fip">
                  <q-uploader
                    :url="url"
                    auto-expand
                    @uploaded="postUpload"
                    @finish="finishUpload"
                    :headers="headers"
                    ref="uploader"
                  /> 
                 <!-- <img :src="`http://localhost:8000/files/${currentUser.photo}`" style="width:80px;" class="shadow-1"/>-->
                {{currentUser.photo}}
               </q-field>
             
             

              

              <q-field>
           
               
                 <table>
                   <tr width="98%">
                     <td width="40%"></td>
                     <td width="20%">Giriş</td>
                     <td width="18%">Yeni</td>
                     <td width="18%">Düzelt</td>
                     <td width="18%">Sil</td>
                  </tr>

                  <tr v-for="(item,key) of yetkiler" >
                     <td>{{item.bolumAdi}}</td>
                     <td><q-checkbox v-model="yetkiler[key]['giris']" :true-value="1" :false-value="0" val="0"/></td>
                     <td><q-checkbox v-model="yetkiler[key]['yeni']" :true-value="1" :false-value="0" /></td>
                     <td><q-checkbox v-model="yetkiler[key]['duzelt']" :true-value="1" :false-value="0" /></td>
                     <td><q-checkbox v-model="yetkiler[key]['sil']" :true-value="1" :false-value="0" /></td>
                   </tr>
                 </table>
             
              </q-field>
              <br /><br />
               <q-field class="fip">
                 <q-btn color="secondary" @click="submit" v-if="kaydetBtn">Kaydet</q-btn>
                 <q-btn align="right"  v-if="guard.sil" color="negative" @click="sil(currentUser.id)" icon="delete"></q-btn>
              </q-field>
        </div>
 </q-modal-layout>
     
    </q-modal>
  
    <div class="row q-pa-sm" >
       <div class="col-xs-12 col-md-12" >
        
          <q-toolbar slot="header" color="faded">
          <q-toolbar-title>Kullanıcı işlemleri</q-toolbar-title>
           <q-btn v-if="guard.yeni" flat round dense @click="yeniKullanici()" wait-for-ripple icon="add" />
            
          </q-toolbar>
        
        <q-search
          v-model="filterText"
          :debounce="600"
          placeholder="Adı veya eposta adresine göre ara"
          icon="search"
          float-label="Ara"
        />
        <q-list highlight inset-separator>
          <q-item  v-for="item of filteredData" :key="item.id">
           <q-item-side left icon="face" />
            <q-item-main
              :label=item.name
              label-lines="1"
              :sublabel=item.email
              sublabel-lines="2"
              dense
              @click.native="rowClick(item.id)"
            />
           <q-item-side right >{{item.role}}</q-item-side>
            

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
      users: [],

      //upload varsa submit etme, önce uploadları gönder
      uploadVar: false,

      //kullanıcı ara.
      filterText: "",

      //kullanıcı tıklanınca alınan index değer. users[index]
      id: "",

      errors: {},

      //detay kullanıcı
      currentUser: {},

      yetkiler: {},

      guard: {},

      selectOptions: [
        { label: "Yönetici", value: "admin" },
        { label: "Kullanıcı", value: "user" }
        
      ]
    };
  },

  created() {
    this.getUserList();
    this.getRole();
    
    
  },

  watch: {
    uploadVar(val) {
      if (val === false) {
        this.postData();
      }
    }
  },

  methods: {
    getRole() {
      axios
        .get(this.apiUrl + "yetkiler?bolum=kullanici")
        .then(response => {
          console.info("role")
          console.log(response.data);
          if (response.data.role == "super") {
            response.data.giris = "1";
            response.data.yeni = "1";
            response.data.duzelt = "1";
            response.data.sil = "1";
          }
          this.guard = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    sil(id) {
      this.$q
        .dialog({
          title: "Kullanıcı Sil",
          message: "Kullanıcı Silinsin mi?",
          ok: "Evet",
          cancel: "Hayır"
        })
        .then(() => {
          axios
            .get(this.apiUrl + "deleteUser?id=" + id)
            .then(response => {
              if (response.data.status === true) {
                let index = this.users.findIndex(x => x.id === id);

                this.users.splice(index, 1);
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
    getUserList() {
      axios
        .get(this.apiUrl + "userList")
        .then(response => {
          this.users = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },

    //yeni modal açar..
    yeniKullanici() {
      this.errors = {};
      this.userModal = true;
      this.currentUser = {};
      this.defaultYetkiler();
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

    //Dosya yükle. Gelen değeri currentuser'a at..
    postUpload(file, xhr) {
      let sonuc = JSON.parse(xhr.response);
      if (sonuc.status === true) {
        this.currentUser.photo = sonuc.file;
      }
    },

    //kullanıcı seçilince index den mevcut değerleri currentuser'a at..
    rowClick(id) {
      
      //id'den users'daki indexi bul..
      let index = this.users.findIndex(x => x.id === id);

      this.id = index;

      this.currentUser = Object.assign({}, this.users[index]);
      //yetki yoksa default yetkiden ver..
      if (this.users[index].yetkiler.length > 0)
        this.yetkiler = this.users[index].yetkiler;
      else this.yetkiler = this.defaultYetkiler();

      console.log(this.yetkiler);

      this.errors = {};

      this.userModal = true;
    },

    submit() {
      let uploadFiles = this.$refs.uploader.files;
      //upload files varsa yükle.. yoksa direk post et.
      if (uploadFiles.length > 0) {
        this.$refs.uploader.upload();
        this.uploadVar = true;
      } else {
        this.postData();
      }
    },

    postData() {
      //currentuser'a yetkileri ata..
      this.currentUser.yetkiler = this.yetkiler;
      axios
        .post(this.apiUrl+"register", this.currentUser)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            notify("Lütfen formu kontrol edin!", true);
          } else {
            this.errors = {};
            if (!this.currentUser.id) {
              this.getUserList();
            }
            //dosyaları boşalt..
            this.$refs.uploader.files = [];

            //yeni bilgileri users'a at..
            this.users[this.id] = this.currentUser;

            this.userModal = false;

            this.getRole();

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
      if (this.currentUser.id) {
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
      if (!this.filterText) return this.users;
      let searchText = this.filterText.toLocaleLowerCase('tr-TR');
      return this.users.filter(p => {
        return (
         p.name!==null ? p.name.toLocaleLowerCase('tr-TR').includes(searchText) :'' ||
         p.email!==null ? p.email.toLocaleLowerCase('tr-TR').includes(searchText) :''
        );
      });
    }
  }
};

export default module;
</script>
