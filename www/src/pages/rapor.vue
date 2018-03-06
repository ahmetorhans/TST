<template>
<q-page>

    <div class="row q-pa-sm">
        <div class="col-md-3 q-pa-sm">
            <rapor-item />
        </div>
        <div class="col-md-9">
            rapor
        </div>
    </div>

</q-page>
</template>

<script>
    import raporItem from './raporlar/raporItem.vue'
     export default {   
        data () {
            return {
                message: 'Hello World'
            }
        },
        components: {
            raporItem
        }
    }
</script>>