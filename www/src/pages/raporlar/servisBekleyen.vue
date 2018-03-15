<template>
  <q-page v-if="guard.giris==='1'">

    <div class="row q-pa-sm">

      <div class="col-md-12">
        <q-toolbar slot="header" color="faded">
          <q-toolbar-title>Servis Listesi</q-toolbar-title>
        </q-toolbar>

        <q-table :data="servisler" :columns="columns" :filter="filter" :filter-method="dataFilter" row-key="id" :pagination.sync="pagination">

          <template slot="top-left" slot-scope="props">
            <q-search  color="secondary" v-model="filter" class="col-12" placeholder="Ara" />

          </template>
         

          <q-tr slot="body" slot-scope="props" :props="props" class="cursor-pointer dtHeight">
            <q-td auto-width v-for="col in props.cols" :key="col.name" :props="props">
              <span v-if="col.date===true">{{formatDate(col.value)}}</span>
              <span v-else>{{col.value}}</span>

            </q-td>
            <q-td>
              <q-btn size="sm" round icon="edit" @click.native="rowClick(props.row)"></q-btn>
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
 
     
      pagination: {
        rowsPerPage: 50
      },
      columns: [
        {
          label: "No",
          name: "id",
          field: "id",
          sortable: true,
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
          label: "Marka",
          name: "marka",
          field: "marka",
          sortable: true,
          align: "left",
          func: "deneme"
        },
        {
          label: "Model",
          name: "model",
          field: "model",
          sortable: true,
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

  },
  methods: {
    
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
          col => (cellValue(col, row) + "").toLocaleLowerCase("tr-TR").indexOf(lowerTerms) !== -1
        )
      );
    },


    getList () {
      axios
        .get(this.apiUrl + "servisListeleBekleyen")
        .then(response => {
            console.info("dsss")
            console.log(response.data)
          this.servisler = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    }
  },
  
};
</script>>