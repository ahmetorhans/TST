<template>
  <q-layout view="hhr lpr lFf">
    <q-layout-header>
      <q-toolbar color="secondary">
        <q-btn flat dense round @click="leftDrawerOpen = !leftDrawerOpen">
          <q-icon name="menu" />
        </q-btn>

        <q-toolbar-title>
          Canon Denizli 
          <div slot="subtitle">Servis Takip v0.1</div>
        </q-toolbar-title>
        <q-btn 
        v-if="guard=='kendi'"
          flat
          dense
          round
          @click="rightDrawerOpen = !rightDrawerOpen"
        >
          <q-icon name="timeline" />
        </q-btn>
      </q-toolbar>
    </q-layout-header>

    <q-layout-drawer v-model="leftDrawerOpen" content-class="bg-grey-2" :content-style="{width: '240px'}">
      <q-list no-border link inset-delimiter v-if="yuklendi">
        <q-list-header>{{$store.state.auth.guard.name}}</q-list-header>
        <musteri v-if="guard=='musteri'"/>
        <user v-if="guard=='kendi'"/>
      </q-list>
    </q-layout-drawer>
    <q-layout-drawer
    v-if="guard=='kendi'"
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
import store from '../store'
import raporItem from '../pages/raporlar/raporItem';
import user from './user';
import musteri from './musteri';
export default {
  name: 'LayoutDefault',
  components: { raporItem,user,musteri },

  data () {

    return {
      leftDrawerOpen: true,
      yuklendi : false,
      rightDrawerOpen:false,
    }
  },

 computed: {
      guard () {

        if (this.$store.state.auth.authLoaded)
          this.yuklendi=true;

        let role = this.$store.state.auth.guard.userGroup;
        if (role==='musteri')
          return 'musteri'
        else
          return 'kendi'
      },

    }
}
</script>

<style>
.qitemH {
  height: 60px;
}
</style>
