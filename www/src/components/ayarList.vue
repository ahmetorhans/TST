<template>
    <div>
        <q-tabs color="faded">
            <q-route-tab slot="title" exact label="Profil" to="/ayarlar/profil" />
           
        </q-tabs>
    </div>
</template>
<script>
export default {
    data () {
        return {
            title: 'Ayarlar'
        }
    }
}  
</script>