<template>
  <div>
    <q-list highlight separator>

      <q-item to="/raporlar/servis">
        <q-item-main label="Servis Listesi" />
      </q-item>
       <q-item to="/raporlar/cari">
        <q-item-main label="Cari Listesi" />
      </q-item>
      <q-item to="/raporlar/cihaz">
        <q-item-main label="Cihaz Listesi" />
      </q-item>

      <q-item to="/raporlar/servisBekleyen">
        <q-item-main label="Bekleyen Servisler" />
      </q-item>

      <q-item to="/raporlar/servisCari">
        <q-item-main label="Cariye Göre Servisler" />
      </q-item>

      <q-item to="/raporlar/cihazCari">
        <q-item-main label="Cariye Göre Cihazlar" />
      </q-item>
     
     
    </q-list>
  </div>
</template>
<script>
export default {
  data () {
    return {
      title: 'Raporlar'
    }
  }
}  
</script>