<template>
  <q-page>
   <q-modal v-model="userModal" :content-css="{ minWidth: '60vw', minHeight:'400px'}">
     <q-modal-layout>
        
        <q-toolbar slot="header">
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
                <q-btn color="primary" @click="submit">Kaydet</q-btn>
              </q-field>
        </div>

          

      </q-modal-layout>
     
    </q-modal>
  
    <div class="row q-pa-sm" >
       <div class="col-xs-12 col-md-12" >
        
          <q-toolbar slot="header" color="dark">
          <q-toolbar-title>Kullanıcı işlemleri</q-toolbar-title>
           <q-btn flat round dense @click="yeniKullanici()" wait-for-ripple icon="add" />
            
          </q-toolbar>
        
        <q-search
          v-model="filterText"
          :debounce="600"
          placeholder="Kullanıcı Ara"
          icon="search"
          float-label="Ara"
        />
        <q-list highlight inset-separator>
          <q-item  v-for="item of filteredData" :key="item.id">
           <q-item-side left icon="person_pin" />
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
  <button @click="login()">Login</button>
 {{isAuthenticated}}
  </q-page>

</template>

<style>

</style>

<script>

import axios from "axios";
import store from '../store'
import notify from "./notify";


export default {
  data() {
    return {
      url: "http://127.0.0.1:8000/api/upload",
      headers :{
        'Authorization': 'Bearer ' + localStorage.getItem('vue-authenticate.vueauth_token')
      },
      userModal: false,
      users: [],
      uploadVar: false,
      filterText: "",
      id: "",
      errors: {},
      currentUser: [],
      selectOptions: [
        { label: "Yönetici", value: "admin" },
        { label: "Servis Kullanıcısı", value: "user" },
        { label: "Operator", value: "opr" }
      ]
    };
  },

  created() {
    this.getUserList();


  },
  watch: {
    uploadVar(val) {
      if (val === false) {
        this.postData();
      }
    }
  },
  methods: {

    login: function () {
     let user = {
       email : 'y@y.com',
       password : '123456'
     }
      //this.$store.dispatch('login', { user })
      this.$store.dispatch('login', { user });
      console.log(this.$store.getters.isAuthenticated)
      console.log(this.$store.state.auth.isAuthenticated)
   
    },
    getUserList() {
      alert(this.deger); 
      axios
        .get(this.apiUrl + "userList")
        .then(response => {
          this.users = response.data;
          console.log(response.data);
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    yeniKullanici() {
      this.errors = {};
      this.userModal = true;
      this.currentUser = {};
    },
    finishUpload() {
      this.uploadVar = false;
    },
    postUpload(file, xhr) {
      let sonuc = JSON.parse(xhr.response);
      if (sonuc.status === true) {
        this.currentUser.photo = sonuc.file;
      }
    },
    rowClick(id) {
      let index = this.users.findIndex(x => x.id === id);
  
    
      this.id = index;

      this.currentUser = Object.assign({}, this.users[index]);
      this.errors = {};
      this.userModal = true;
    },
    submit() {
      //console.log(this.currentUser)

      let uploadFiles = this.$refs.uploader.files;
      if (uploadFiles.length > 0) {
        this.$refs.uploader.upload();
        this.uploadVar = true;
      } else {
        this.postData();
      }
    },

    postData() {
      axios
        .post("http://localhost:8000/api/register", this.currentUser)
        .then(res => {
          if (res.data.status === false) {
            this.errors = res.data.msg;
            
            console.log(this.errors);
            notify("Lütfen form kontrol edin!", true);
          } else {
            this.errors = {};
            if (!this.currentUser.id) {
              this.getUserList();
            }
            //dosyaları boşalt..
            this.$refs.uploader.files = [];
            //yeni bilgileri state at
            this.users[this.id] = this.currentUser;
            
            this.userModal=false;

            notify(res.data.msg);
          }
        })
        .catch(function(error) {
      this.$router.push('/login');
         this.$router.push('/login');
       
          notify(error.response.data.error,true);
            
     
        });
    }
  },

  computed: {
    isAuthenticated(){
      return this.$store.getters.isAuthenticated;
    },
   
    filteredData() {
      if (!this.filterText) return this.users;
      let searchText = this.filterText.toLowerCase();
      return this.users.filter(p => {
        return (
          p.name.toLowerCase().includes(searchText) ||
          p.email.toLowerCase().includes(searchText)
        );
      });
    }
  }
};
</script>
