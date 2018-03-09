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
      <q-list
        no-border
        link
        inset-delimiter
      >
        <q-list-header>Raporlar</q-list-header>
          <q-item to="/raporlar/servis">
          <q-item-main label="Servis Listesi" />
          </q-item>
          <q-item to="/raporlar/cari">
            <q-item-main label="Cariye Göre liste" />
          </q-item>
        <q-item>
          <q-item-main label="Bekleyen Servisler" />
          </q-item>
          <q-item>
          <q-item-main label="Tamamlanan Servisler" />
          </q-item>
          <q-item>
          <q-item-main label="Cihazlar" />
          </q-item>
        
      </q-list>
    </q-layout-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { openURL } from 'quasar'

export default {
  name: 'LayoutDefault',
  data () {
    return {
      leftDrawerOpen: false,
      rightDrawerOpen: true,
    }
  },
  methods: {
    openURL
  }
}
</script>

<style>
.qitemH{
  height:60px;
}
</style>
