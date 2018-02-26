<template>
  <q-page>
  

    
      <div class="row">
          
        <div class="col-xs-12 col-sm-6 col-md-4 q-pa-sm">
          <q-card class="q-pa-sm">
          <q-input v-model="text" stack-label="Stack Label" />
              <q-btn label="New item" />
                 </q-card>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
         <q-card class="q-pa-sm">
          <q-input v-model="text" stack-label="Stack Label" />
              <q-btn label="New item" />
                 </q-card>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
         <q-card class="q-pa-sm">
          <q-input v-model="text" stack-label="Stack Label" />
              <q-btn label="New item" />
                 </q-card>
        </div>
      </div>
  

  </q-page>
</template>

<style>

</style>

<script>
export default {
  name: "PageIndex"
};
</script>
