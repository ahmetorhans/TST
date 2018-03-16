<template>
    <q-page v-if="guard.giris==='1'">

        <div class="row q-pa-sm">

            <div class="col-md-12">
                <q-toolbar slot="header" color="faded">
                    <q-toolbar-title>Cariye Göre Cihazlar</q-toolbar-title>
                </q-toolbar>

                <q-table :data="filteredData" :columns="columns" :filter="cariAdiFilter" :filter-method="dataFilter" row-key="id" :pagination.sync="pagination">
                    <template slot="top-left" slot-scope="props">
                        <q-field :label-width="3" class="fip">
                            <q-search v-model="cariAdi" placeholder="Cari Adı" ref="cariAdi" style="width:340px;">
                                <q-autocomplete @search="cariAra" @selected="cariAraSelected" />
                            </q-search>

                        </q-field>

                    </template>

                    <q-tr slot="body" slot-scope="props" :props="props" @click.native="rowClick(props.row)" class="cursor-pointer dtHeight">
                        <q-td v-for="col in props.cols" :key="col.name" :props="props">
                            {{col.value}}
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
            cariler: [],
            filter: "",
            guard: {},
            cariAdi: '',
            cariAdiFilter: '',
            cariList: [],
            grupFilter: '',
            pagination: {
                rowsPerPage: 50
            },
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
                  
                    align: "left"
                },
                {
                    label: "Adı",
                    name: "adi",
                    field: "adi",
                    sortable: true,
                    filter: false,
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
                    label: "Seri No",
                    name: "serino",
                    field: "serino",
                    sortable: true,
                    filter: false,
                    align: "left"
                },
               
            ]
        };
    },
    components: {
        raporItem
    },

    created () {
        this.getRole();
        this.getList();
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
            this.$router.push("/cihazlar/" + row.id);
        },

        dataFilter (rows, terms, cols, cellValue) {
            cols = cols.filter(c => {
                return c.filter !== false;
            });

            const lowerTerms = terms ? terms.toLocaleLowerCase("tr-TR") : "";

            return rows.filter(row =>
                cols.some(
                    col =>
                        (cellValue(col, row) + "").toLocaleLowerCase() === lowerTerms
                )

            );
        },
        getList () {
            axios
                .get(this.apiUrl + "cihazListele")
                .then(response => {

                    console.log(response.data)
                    this.cariler = response.data;

                })
                .catch(e => {

                    this.errors.push(e);
                });
        }
    },
    computed: {
        filteredData () {
            if (!this.grupFilter)
                return this.cariler;
            return this.cariler.filter(p => {
                return (
                    p.islemDurumu == this.grupFilter
                );
            });
        }
    }
};
</script>>