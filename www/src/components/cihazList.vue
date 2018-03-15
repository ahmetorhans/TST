<template>
  <q-page>
    <div class="row q-pa-sm" >
       <div class="col-xs-12 col-md-12" >
        <q-search
          v-model="filterText"
          :debounce="600"
          placeholder="Seri no veya barkod"
          icon="search"
          float-label="Ara"
        />  
       <q-list highlight inset-separator>
            <q-item  v-for="item of filteredDataChz.slice(0,300)" :key="item.id">
              <q-item-side left icon="devices" />
                <q-item-main
                  :label=item.adi
                  label-lines="1"
                  sublabel-lines="3"
                  dense
                  @click.native="rowClick(item)"
                > 
                <q-item-tile sublabel>{{item.marka}}  {{item.model}} </q-item-tile>
                <q-item-tile sublabel>{{item.aciklama}}</q-item-tile>
                <q-item-tile sublabel class="mobile-only"> {{item.cariAdi}}</q-item-tile>
              </q-item-main>
              <q-item-side class="desktop-only" right  @click="rowClick(item)" >{{item.cariAdi}}</q-item-side>
            </q-item>
        </q-list>
    
        </div>
      </div>
  </q-page>
</template>
<script>
import axios from "axios";
import notify from "../pages/notify";


const module = {
  data() {
    return {
      //tüm kayıtlar
      cihazList: [],
      //ara.
      filterText: "",
   };
  },

  created() {
    //this.getList();
  },

  methods: {
   
    getList() {
      axios
        .get(this.apiUrl + "cihazListele")
        .then(response => {
          this.cihazList = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },

  
    //kullanıcı seçilince index den mevcut değerleri currentCihaz'a at..
    rowClick(item) {
     this.$emit('cihazListEmit', item);
    },

    
   
  },

  computed: {
   
    filteredDataChz() {
      if (!this.filterText) return this.cihazList;
    

      let searchText = this.filterText.toLocaleLowerCase("tr-TR");

      return this.cihazList.filter(p => {
          console.log(p)
        p.serino === null ? (p.serino = "") : p.serino;
        p.adi === null ? (p.adi = "") : p.adi;
        p.cariAdi === null ? (p.cariAdi = "") : p.cariAdi;

        return (
          p.serino.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.adi.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1 ||
          p.cariAdi.toLocaleLowerCase("tr-TR").indexOf(searchText) > -1
        );
      });
    }
  }
};

export default module;
</script>
