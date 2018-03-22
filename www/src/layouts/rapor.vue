<template>
  <q-layout view="hhr lpr lFf">
    <q-layout-header>
      <q-toolbar color="secondary"  >
        <q-btn
          flat
          dense
          round
          @click="leftDrawerOpen = !leftDrawerOpen"
        >
          <q-icon name="menu" />
        </q-btn>

       <q-toolbar-title>
          Canon Denizli
          <div slot="subtitle">Servis Takip v0.1</div>
        </q-toolbar-title>

        <q-btn 
          flat
          dense
          round
          @click="rightDrawerOpen = !rightDrawerOpen"
        >
          <q-icon name="timeline" />
        </q-btn>

      </q-toolbar>
    </q-layout-header>

    <q-layout-drawer
      v-model="leftDrawerOpen"
      content-class="bg-grey-2"
      :content-style="{width: '240px'}"
    >
      <q-list
        no-border
        link
        inset-delimiter
      >
        <q-list-header>Nazmi Kor</q-list-header>
         <q-item to="/servisler" class="qitemH">
            <q-item-side icon="store" /> 
            <q-item-main label="Servis"  />
        </q-item>
        <q-item to="/randevular" class="qitemH">
          <q-item-side icon="timeline" />
          <q-item-main label="Randevular" />
        </q-item>
        <q-item to="/cariler" class="qitemH">
            <q-item-side icon="recent actors" /> 
            <q-item-main label="Cari"  />
        </q-item>
        <q-item to="/raporlar" class="qitemH">
            <q-item-side icon="timeline" /> 
            <q-item-main label="Rapor"  />
        </q-item>
       
            
          <q-item to="/cihazlar" class="qitemH">
            <q-item-side icon="devices" /> 
            <q-item-main label="Cihazlar"  />
        </q-item>
        <q-item to="/kullanicilar" class="qitemH">
            <q-item-side icon="supervisor_account" /> 
            <q-item-main label="Kullanıcılar"  />
        </q-item>
        
      </q-list>
    </q-layout-drawer>


    <q-layout-drawer
      side="right"
      v-model="rightDrawerOpen"
      content-class="bg-grey-2"
     
    >
      <rapor-item />
    </q-layout-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { openURL } from 'quasar'
import raporItem from '../pages/raporlar/raporItem'
export default {
  name: 'LayoutDefault',
  data () {
    return {
      leftDrawerOpen: true,
      rightDrawerOpen: false,
    }
  },
  methods: {
    openURL
  },
  components:{
    raporItem
  }
}
</script>

<style>
.qitemH{
  height:60px;
}
</style>
