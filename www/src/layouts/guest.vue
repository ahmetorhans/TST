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
       
       
      </q-toolbar>
    </q-layout-header>
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>

export default {
  name: 'LayoutDefault',
  

  data () {

    return {
     
    }
  },

 computed: {
     

    }
}
</script>

<style>
.qitemH {
  height: 60px;
}
</style>
