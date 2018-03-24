<template>
  <q-page v-if="guard.giris==='1'">

    <div class="row q-pa-sm">

      <div class="col-md-12">
        <q-toolbar slot="header" color="faded">
          <q-toolbar-title>Bekleyen Randevular</q-toolbar-title>
        </q-toolbar>

        <q-table :data="randevular" :columns="columns" :filter="filter" :filter-method="dataFilter" row-key="id" :pagination.sync="pagination">
          <template slot="top-left" slot-scope="props">
            <q-search hide-underline color="secondary" v-model="filter" class="col-6" placeholder="Ara" />
          </template>

          <q-tr slot="body" slot-scope="props" :props="props" @click.native="rowClick(props.row)" class="cursor-pointer dtHeight">
            <q-td v-for="col in props.cols" :key="col.name" :props="props">
              <span v-if="col.date===true"><strong>{{col.value | tarih}}</strong></span>
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
      randevular: [],
      filter: "",

      pagination: {
        rowsPerPage: 50
      },
      columns: [
        {
          label: "Cari",
          name: "adi",
          field: "adi",
          sortable: true,
          align: "left"
        },
        {
          label: "Pazarlamacı",
          name: "kullanici",
          field: "kullanici",
          sortable: true,
          align: "left"
        },
        {
          label: "Firma Yetkilisi",
          name: "yetkili",
          field: "yetkili",
          sortable: true,
          align: "left",
          func: "deneme"
        },
        {
          label: "Açıklama",
          name: "aciklama",
          field: "aciklama",
          sortable: true,
       
          align: "left"
        },
        {
          label: "Tarih",
          name: "randevuTarihi",
          field: "randevuTarihi",
          sortable: true,
          filter: false,
          date:true,
          align: "left"
        },

      ]
    };
  },
  components: {
    raporItem
  },

  created () {

    this.getList();
  },
  computed: {
    guard () {
      return this.$store.state.auth.guard.servis;
    }
  },
  methods: {


    rowClick (row) {
      this.$router.push("/randevular/" + row.id);
    },

    dataFilter (rows, terms, cols, cellValue) {
      cols = cols.filter(c => {
        return c.filter !== false;
      });

      const lowerTerms = terms ? terms.toLocaleLowerCase("tr-TR") : "";

      return rows.filter(row =>
        cols.some(
          col =>
            (cellValue(col, row) + "")
              .toLocaleLowerCase()
              .indexOf(lowerTerms) !== -1
        )
      );
    },
    getList () {
      axios
        .get(this.apiUrl + "randevuListeleRapor")
        .then(response => {
          this.randevular = response.data;

        })
        .catch(e => {
          this.errors.push(e);
        });
    },
  }
};
</script>>