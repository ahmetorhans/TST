<template>
  <div class="q-pa-sm">
    
        <div id="yazdirAlani">
          <div class="rows12">
            <table class="greyGridTable">
             
              <tr>
                <td width="30%">İşlem Durumu</td>
                <td> {{currentServis.get_durum.label}}</td>
              </tr>
              <tr>
                <td width="30%">Fiş No</td>
                <td>{{currentServis.id}}</td>
              </tr>
              <tr>
                <td width="30%">Tarih</td>
                <td>{{formatDate(currentServis.created_at)}}</td>
              </tr>
              <tr>
                <td width="30%">Fiyat</td>
                <td>{{currentServis.fiyat}} </td>
              </tr>
              <tr>
                <td width="30%">Teknisyen</td>
                <td>{{currentServis.get_teknisyen.name}}</td>
              </tr>
              <tr>
                <td width="30%">Açıklama</td>
                <td>{{currentServis.aciklama}}</td>
              </tr>
              <tr>
                <td width="30%">Ek Parça</td>
                <td>{{currentServis.ekParca}}</td>
              </tr>
            </table>
          </div>

          <div class="rows6" v-if="currentServis.get_cihaz.id">

            <table class="greyGridTable">

              <tr>
                <td width="30%">Adı</td>
                <td>{{currentServis.get_cihaz.adi}}</td>
              </tr>
              <tr>
                <td width="30%">Marka / Model</td>
                <td>{{currentServis.get_cihaz.marka}} {{currentServis.get_cihaz.model}}</td>
              </tr>
              <tr>
                <td width="30%">Seri no</td>
                <td>{{currentServis.get_cihaz.serino}}</td>
              </tr>
              <tr>
                <td width="30%">Barkod</td>
                <td>{{currentServis.get_cihaz.barkod}}</td>
              </tr>
              <tr>
                <td width="30%">Garanti Bitiş</td>
                <td>{{currentServis.get_cihaz.garantiTarih}} </td>
              </tr>
              <tr>
                <td width="30%">Açıklama</td>
                <td>{{currentServis.get_cihaz.aciklama}} </td>
              </tr>
            </table>

          </div>
         
          <div style="clear:both">&nbsp;</div>
          <p class="text-bold">İşlemler</p>
          <div class="rows12">

            <table class="greyGridTable">
              <tr>
                <th width="15%">Tarih</th>
                <th width="15%">Adı</th>
                <th width="50%">Acıklama</th>
                <th width="30%">Kullanıcı</th>
              </tr>
              <tr v-for="item in currentServis.get_islem">
                <td width="15%">{{formatDate(item.tarih)}}</td>
                <td width="15%">{{item.adi}}</td>
                <td width="50%">{{item.aciklama}}</td>
                <td width="30%">{{item.user}}</td>
              </tr>
            </table>

          </div>

        </div>
        <q-btn label="yazdır" @click.native="yazdir()" />
        <!--<barcode :value="currentServis.id" :options="{ displayValue: true }"></barcode>-->



  </div>

</template>

<script>
import axios from "axios";
import notify from "../pages/notify";
import cihazKaydet from "../components/cihazKaydet";
import cariKaydet from "../components/cariKaydet";
import cihazList from "../components/cihazList";
import islemKaydet from "../components/islemKaydet";

const module = {
  components: { cihazKaydet, cariKaydet, cihazList, islemKaydet },
  data () {
    return {
      errors: {},

      cariList: [],

      cihazList: [],

      teknisyen: [],
      cariModal: false,
      cihazModal: false,
      cihazListModal: false,
      islemModal: false,

      currentServis: {},
   

      islemDurumlari: []
    };
  },

  created () {
    /*this.getRole();
    this.getTeknisyen();
    this.getServis();
    this.getIslemDurumlari();
*/
   // this.servisInit();
    this.getServis();
    this.currentServis.get_cari = [];
    this.currentServis.get_cihaz = [];
    this.currentServis.get_islem = [];
    this.currentServis.get_durum = [];
    this.currentServis.get_teknisyen = [];
   
  },

  methods: {
    formatDate (date) {
      if (!date)
        return
      let nDate = new Date(date);
      let fDate = new Date(nDate.toISOString().split('T')[0]);
      return fDate.toLocaleDateString();

    },
   
    yazdir () {
      window.print();
    },
    
    
    
    //ilgili servisi getir
    getServis () {
      this.currentServis.get_cari = {};
      this.currentServis.get_cihaz = {};
      if (this.$route.params.id) {
        axios
          .get(this.apiUrl + "servisGetirGuest/" + this.$route.params.id)
          .then(response => {
           
            this.currentServis = response.data.servis;
            if (!this.currentServis.get_teknisyen)
              this.currentServis.get_teknisyen = [];
            if (!this.currentServis.get_cihaz)
              this.currentServis.get_cihaz = [];

            this.currentServis.cariAdi = response.data.servis.get_cari.adi;
            this.currentServis.cihazAdi = response.data.servis.get_cihaz.adi;
          })
          .catch(e => {
            this.errors.push(e);
          });
      }
    },
},
  computed: {
  }
};

export default module;
</script>
