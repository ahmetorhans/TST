<template>
<q-page>

    <div class="row q-pa-sm">
       
        <div class="col-md-12">
            <q-toolbar slot="header" color="faded">
              <q-toolbar-title>Servis Listesi</q-toolbar-title>
            </q-toolbar>

            <q-table
                :data="cariler"
                :columns="columns"
                :filter="filter"
                :filter-method="dataFilter"
                row-key="id"
                :pagination.sync="pagination"
            >
                <template slot="top-left" slot-scope="props">
                    <q-search
                    hide-underline
                    color="secondary"
                    v-model="filter"
                    class="col-6"
                    placeholder="Ara"
                    />
                </template>

                 <q-tr slot="body" slot-scope="props" :props="props" @click.native="rowClick(props.row)" class="cursor-pointer dtHeight" >
                    <q-td v-for="(col,index) in props.cols" :key="col.name" :props="props">
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
  data() {
    return {
      cariler: [],
      filter: "",
      pagination: {
     
        rowsPerPage: 50
      },
      columns: [
        {
          label: "Adı",
          name: "adi",
          field: "adi",
          sortable: true,
          align: "left"
        },
        {
          label: "Yetkili",
          name: "yetkili",
          field: "yetkili",
          sortable: true,
          align: "left",
          func: "deneme"
        },
        {
          label: "Adres",
          name: "adres",
          field: "adres",
          sortable: true,
          filter: false,
          align: "left"
        },
        {
          label: "Telefon",
          name: "telefon",
          field: "telefon",
          sortable: true,
          align: "left"
        }
      ]
    };
  },
  components: {
    raporItem
  },

  created() {
    this.getList();
  },
  methods: {
    

    rowClick(row) {
      this.$router.push("/cariler/" + row.id);
    },
    dataFilter(rows, terms, cols, cellValue) {
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
    getList() {
      axios
        .get(this.apiUrl + "listCari")
        .then(response => {
          this.cariler = response.data;
          
        })
        .catch(e => {
          
          this.errors.push(e);
        });
    }
  }
};
</script>>