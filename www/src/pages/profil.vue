<template>
    <q-page class="q-pa-sm">
        <div class="row">
            <div class="col-md-12">
                <ayar-list />
            </div>
            <div class="col-md-9">
                <div class="layout-padding">
                    <vue-xlsx-table @on-select-file="handleSelectedFile">ad</vue-xlsx-table>
                    {{sutunlar}}
                    <q-field label="Adı Soyadı" :label-width="3">
                        <q-input v-model="currentUser.name" :class="{'has-error': errors.name}" required/>
                        <span class="errMsg" v-if="errors.name">{{ errors.name }}</span>
                    </q-field>

                    <q-field label="Eposta Adresi" :label-width="3" class="fip">
                        <q-input v-model="currentUser.email" :class="{'has-error': errors.email}" />
                        <span class="errMsg" v-if="errors.email">{{ errors.email }}</span>
                    </q-field>

                    <q-field label="Parola" :label-width="3" class="fip">
                        <q-input v-model="currentUser.password" type="password" :class="{'has-error': errors.password}" />
                        <span class="errMsg" v-if="errors.password">{{ errors.password }}</span>
                    </q-field>

                    <q-field label="Fotoğraf" :label-width="3" class="fip">
                        <q-uploader :url="url" auto-expand @uploaded="postUpload" @finish="finishUpload" :headers="headers" ref="uploader" />
                        <!-- <img :src="`http://localhost:8000/files/${currentUser.photo}`" style="width:80px;" class="shadow-1"/>-->

                    </q-field>

                    <br />
                    <q-field class="fip">
                        <q-btn color="secondary" @click="submit">Kaydet</q-btn>

                    </q-field>
                </div>
            </div>
            <div class="col-md-3">
                <span v-if="currentUser.photo"><img :src="fileUrl+'/thumb/'+currentUser.photo" class="responsive q-pa-sm" /></span>
            </div>

        </div>
    </q-page>

</template>
<script>
import axios from "axios";
import ayarList from '../components/ayarList';
import store from "../store";
import notify from "./notify";

const module = {
    components: { ayarList },
    data () {
        return {
            //file upload
            url: this.apiUrl + "upload",
            headers: {
                Authorization:
                    "Bearer " + localStorage.getItem("vue-authenticate.vueauth_token")
            },

            //upload varsa submit etme, önce uploadları gönder
            uploadVar: false,

            errors: {},

            //detay kullanıcı
            currentUser: {},

            yetkiler: {},

            guard: {},

            sutunlar: {}


        };
    },

    created () {
        this.getUser();
    },
    watch: {
        uploadVar (val) {
            if (val === false) {
                this.postData();
            }
        }
    },

    methods: {
        handleSelectedFile (convertedData) {
            console.log(convertedData)
            this.sutunlar = convertedData.body;
        },
        getUser () {
            axios
                .get(this.apiUrl + "profilGetir", this.currentUser)
                .then(response => {

                    console.log(response.data)

                    this.currentUser = response.data;


                })

        },

        //dosya yükleme bitince watch ile takip et.. Sonra submit et..
        finishUpload () {
            this.uploadVar = false;
        },

        //Dosya yükle. Gelen değeri currentuser'a at..
        postUpload (file, xhr) {
            let sonuc = JSON.parse(xhr.response);
            if (sonuc.status === true) {
                this.currentUser.photo = sonuc.file;
            }
        },

        submit () {
            let uploadFiles = this.$refs.uploader.files;
            //upload files varsa yükle.. yoksa direk post et.
            if (uploadFiles.length > 0) {
                this.$refs.uploader.upload();
                this.uploadVar = true;
            } else {
                this.postData();
            }
        },

        postData () {
            //currentuser'a yetkileri ata..
            this.currentUser.xls= this.sutunlar;
            axios
                .post(this.apiUrl + "profilKaydet", this.currentUser)
                .then(res => {
                    if (res.data.status === false) {
                        this.errors = res.data.msg;
                        notify("Lütfen formu kontrol edin!", true);
                    } else {
                        this.errors = {};
                        // localStorage.setItem('adi',this.currentUser.name);

                        //dosyaları boşalt..
                        this.$refs.uploader.files = [];

                        notify(res.data.msg);
                    }
                })
                .catch(function (error) {
                    notify(error.response.data.error, true);
                });
        }
    },

    computed: {
        kaydetBtn () {
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
        isAuthenticated () {
            return this.$store.getters.isAuthenticated;
        },
        //kullanıcı listesindan ara..
        filteredData () {
            if (!this.filterText) return this.users;
            let searchText = this.filterText.toLocaleLowerCase('tr-TR');
            return this.users.filter(p => {
                p.name === null ? (p.name = "") : p.name;
                p.email === null ? (p.email = "") : p.email;
                return (
                    p.name.toLocaleLowerCase('tr-TR').includes(searchText) ||
                    p.email.toLocaleLowerCase('tr-TR').includes(searchText)
                );
            });
        }
    }
};

export default module;
</script>
