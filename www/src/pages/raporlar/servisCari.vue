<template>
    <q-page v-if="guard.giris==='1'">

        <div class="row q-pa-sm">

            <div class="col-md-12">
                <q-toolbar slot="header" color="faded">
                    <q-toolbar-title>Cariye Göre Servisler</q-toolbar-title>
                </q-toolbar>

                <q-table  :data="filteredData" :columns="columns" :filter="cariAdiFilter" :filter-method="dataFilter" row-key="id" :pagination.sync="pagination">

                    <template slot="top-left" slot-scope="props">
                        <q-field :label-width="3" class="fip">
                            <q-search v-model="cariAdi" placeholder="Cari Adı" ref="cariAdi" style="width:340px;">
                                <q-autocomplete @search="cariAra" @selected="cariAraSelected" />
                            </q-search>

                        </q-field>

                    </template>
                    <template slot="top-right" slot-scope="props">

                        <!--<q-select color="secondary" v-model="grupFilter" float-label="İşlem Durumları" style="width:180px" :options="islemDurumlari" />-->

                    </template>

                    <q-tr slot="body" slot-scope="props" :props="props" class="cursor-pointer dtHeight" @click.native="rowClick(props.row)">
                        <q-td auto-width v-for="col in props.cols" :key="col.name" :props="props">
                            <span v-if="col.date===true">{{formatDate(col.value)}}</span>
                            <span v-else>{{col.value}}</span>

                        </q-td>
                        
                    </q-tr>
                </q-table>

            </div>
        </div>

    </q-page>
</template>

<script>
import axios from "axios";
import raporItem from "./raporItem";
export default {
    data () {
        return {
            servisler: [],
            filter: "",
            guard: {},
            grupFilter: '',
            islemDurumlari: [],
            cariList: [],
            cariAdi: '',
            cariAdiFilter: '',
            pagination: {
                rowsPerPage: 50
            },
                visibleColumns: ['id','cariAdi','marka','model','aciklama','tarih','islemDurumLabel'],

            columns: [
                {
                    label: "No",
                    name: "id",
                    field: "id",
                    sortable: true,
                    filter: false,
                    align: "left"
                },
                {
                    label: "Cari",
                    name: "cariAdi",
                    field: "cariAdi",
                    sortable: true,
                    filter: true,
                    align: "left"
                },
                
                {
                    label: "Marka",
                    name: "marka",
                    field: "marka",
                    sortable: true,
                    filter: false,
                    align: "left",
                    func: "deneme"
                },
                {
                    label: "Model",
                    name: "model",
                    field: "model",
                    sortable: true,
                    filter: false,
                    align: "left"
                },
                {
                    label: "Açıklama",
                    name: "aciklama",
                    field: "aciklama",
                    sortable: true,
                    filter: false,
                    align: "left"
                },
                {
                    label: "Tarih",
                    name: "tarih",
                    field: "tarih",
                    sortable: true,
                    date: true,
                    filter: false,
                    align: "left"
                },
                {
                    label: "Durum",
                    name: "islemDurumLabel",
                    field: "islemDurumLabel",
                    sortable: true,
                    filter: false,
                    align: "left"
                }

            ]
        };
    },
    components: {
        raporItem
    },

    created () {
        this.getRole();
        this.getList();
        this.getIslemDurumlari();
    },
    methods: {

        cariAra (terms, done) {
            if (this.cariList.length == 0) {
                axios
                    .get(this.apiUrl + "listShortCari")
                    .then(response => {
                        this.cariList = response.data;
                        this.cariAraComplete(terms, done);
                    })
                    .catch(e => {
                        console.log(e);
                    });
            } else {
                this.cariAraComplete(terms, done);
            }
        },

        cariAraComplete (terms, done) {
            let searchText = this.cariAdi.toLocaleLowerCase("tr-TR");
            let sonuc = [];

            let ver = this.cariList.filter(p => {
                p.adi === null ? (p.adi = "") : p.adi;
                return (
                    p.adi.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1

                );
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
            this.cariAdi = item.label;
            this.cariAdiFilter = item.label.toString();
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
        formatDate (date) {
            if (!date)
                return;

            let nDate = new Date(date);
            // let fDate= new Date(nDate.toISOString().split('T')[0]);
            return nDate.toLocaleDateString();

        },

        getRole () {
            axios
                .get(this.apiUrl + "yetkiler?bolum=rapor")
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
                    this.errors.push(e);
                });
        },

        rowClick (row) {
            this.$router.push("/servisler/servis/" + row.id);
        },

        dataFilter (rows, terms, cols, cellValue) {
            cols = cols.filter(c => {
                return c.filter !== false;
            });

            const lowerTerms = terms ? terms.toLocaleLowerCase("tr-TR") : "";
            return rows.filter(row =>
                cols.some(
                    col => (cellValue(col, row) + "").toLocaleLowerCase("tr-TR") == lowerTerms
                )
            );
        },


        getList () {
            axios
                .get(this.apiUrl + "servisListele")
                .then(response => {
                    this.servisler = response.data;
                })
                .catch(e => {
                    this.errors.push(e);
                });
        }
    },
    computed: {
        filteredData () {
            if (!this.grupFilter)
                return this.servisler;
            return this.servisler.filter(p => {
                return (
                    p.islemDurumu == this.grupFilter
                );
            });
        }
    }
};
</script>>