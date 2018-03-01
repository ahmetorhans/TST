<template>
 <q-layout view="hhr lpr lFf">
   <q-page>
        <div id="container">

            <div class="box shadow-1 " style="background-color:#fff;padding:10px;">
               
             <q-field label="Eposta Adresi" class="fip" >
                   <q-input v-model="email"  />
              </q-field>

              <q-field label="Parola" class="fip">
                  <q-input v-model="password" type="password"  />

              </q-field>
              <q-field class="fip">
                <q-btn color="primary" @click="submit">Giriş</q-btn>
              </q-field>
            </div>

          

        </div>

    </q-page>
  </q-layout>   
</template>
<script>
export default {
  data() {
    return {
      email: "",
      password: ""
    };
  }
};
</script>