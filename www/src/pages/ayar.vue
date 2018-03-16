<template>
    <q-page class="q-pa-sm">
        <div class="row">
            <div class="col-md-12">
                <ayar-list />

            </div>
            <div class="col-md-9">
                <div class="layout-padding">
                    <q-field label="Smtp Server Adı" :label-width="3">
                        <q-input v-model="currentAyar.name" :class="{'has-error': errors.name}" required/>
                        <span class="errMsg" v-if="errors.name">{{ errors.name }}</span>
                    </q-field>

                    <q-field label="Eposta Adresi" :label-width="3" class="fip">
                        <q-input v-model="currentAyar.email" :class="{'has-error': errors.email}" />
                        <span class="errMsg" v-if="errors.email">{{ errors.email }}</span>
                    </q-field>

                    <q-field label="Parola" :label-width="3" class="fip">
                        <q-input v-model="currentAyar.password" type="password" :class="{'has-error': errors.password}" />
                        <span class="errMsg" v-if="errors.password">{{ errors.password }}</span>
                    </q-field>
                    <br />
                    <q-field class="fip">
                        <q-btn color="secondary" @click="submit">Kaydet</q-btn>

                    </q-field>
                </div>
            </div>

        </div>
    </q-page>

</template>
<script>
import axios from "axios";
import ayarList from '../components/ayarList';
import notify from "./notify";

const module = {
    components: { ayarList },
    data () {
        return {
            errors: {},
            //detay kullanıcı
            currentAyar: {},
            guard: {},

        };
    },

    created () {
        this.getSetting();
    },
   

    methods: {

        getSetting () {
            axios
                .get(this.apiUrl + "ayarGetir/smtp")
                .then(res => {
                    console.log(response.data)
                    this.currentAyar = response.data;
                })
        },
        

        submit () {
            //currentAyar'a yetkileri ata..
            console.log(this.currentAyar)
            axios
                .post(this.apiUrl + "ayarKaydet", this.currentAyar)
                .then(res => {
                    if (res.data.status === false) {
                        this.errors = res.data.msg;
                        notify("Lütfen formu kontrol edin!", true);
                    } else {
                        this.errors = {};
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
            if (this.currentAyar.id) {
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
