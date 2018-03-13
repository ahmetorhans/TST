<template>
<div>
<q-list highlight separator>
  
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
</div>
</template>
<script>
export default {
  data(){
    return{
      title:'Raporlar'
    }
  }
}  
</script>