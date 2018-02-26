<template>
  <q-page>
    <div class="row q-pa-sm" >
        <div class="col-md-12">
        <h5>Kullanıcılar</h5>
        </div>
        <div class="col-xs-12 col-md-6">
          
                 <q-list highlight inset-separator>
  <q-item>
    <q-item-side avatar="statics/boy-avatar.png" />
    <q-item-main label="Brunch this weekend? Brunch this weekend? Brunch this weekend?" label-lines="1" />
    <q-item-side right stamp="1 min" />
  </q-item>
  <q-item multiline>
    <q-item-side avatar="statics/boy-avatar.png" />
    <q-item-main
      label="Brunch this weekend? Brunch this weekend? Brunch this weekend?"
      label-lines="1"
      sublabel="John Doe John Doe John Doe John Doe John Doe John Doe John Doe John Doe John Doe"
      sublabel-lines="2"
    />
    <q-item-side right>
      <q-item-tile stamp>1 week ago</q-item-tile>
    </q-item-side>
  </q-item>
  <q-item multiline>
    <q-item-side avatar="statics/boy-avatar.png" />
    <q-item-main
      label="Brunch this weekend? Brunch this weekend? Brunch this weekend?"
      label-lines="2"
      sublabel="John Doe John Doe John Doe John Doe John Doe John Doe John Doe John Doe John Doe"
    />
    <q-item-side right stamp="10 min" />
  </q-item>
  <q-item multiline>
    <q-item-side>
      <q-item-tile avatar>
        <img src="statics/boy-avatar.png">
      </q-item-tile>
    </q-item-side>
    <q-item-main>
      <q-item-tile label>Brunch <span>5</span></q-item-tile>
      <q-item-tile sublabel lines="2">
        John Doe John Doe John Doe John Doe John Doe John Doe
        John Doe John Doe John Doe John Doe John Doe John Doe
        John Doe John Doe John Doe John Doe
      </q-item-tile>
    </q-item-main>
    <q-item-side right>
      <q-item-tile stamp>10 min</q-item-tile>
      <q-item-tile icon="star" color="yellow" />
    </q-item-side>
  </q-item>
  <q-item multiline>
    <q-item-side avatar="statics/boy-avatar.png" />
    <q-item-main>
      <q-item-tile label lines="1">Brunch this weekend? Yeah, this weekend. Really. This one.</q-item-tile>
      <q-item-tile sublabel lines="2">
        <span>John Doe</span>
        -- I'll be in your neighborhood doing errands this
        weekend. Do you want to grab brunch?
      </q-item-tile>
    </q-item-main>
    <q-item-side right>
      <q-item-tile stamp>2 years</q-item-tile>
      <q-item-tile icon="mail" color="primary" />
    </q-item-side>
  </q-item>
</q-list>

<q-list inset-separator class="q-mt-md">
  <q-item>
    <q-item-side avatar="statics/boy-avatar.png" />
    <q-item-main label="Brunch this weekend?" />
    <q-item-side right>
      <q-btn flat round dense icon="more_vert">
        <q-popover>
          <q-list link>
            <q-item v-close-overlay>
              <q-item-main label="Reply" />
            </q-item>
            <q-item v-close-overlay>
              <q-item-main label="Forward" />
            </q-item>
            <q-item v-close-overlay>
              <q-item-main label="Delete" />
            </q-item>
          </q-list>
        </q-popover>
      </q-btn>
    </q-item-side>
  </q-item>
  <q-item>
    <q-item-side avatar="statics/boy-avatar.png" />
    <q-item-main label="Brunch this weekend?" />
    <q-item-side right>
      <q-chip square color="primary" class="shadow-2">10k</q-chip>
    </q-item-side>
  </q-item>
  <q-item>
    <q-item-side avatar="statics/boy-avatar.png" />
    <q-item-main label="Brunch this weekend?" />
    <q-item-side right>
      <q-item-tile stamp>10 min ago</q-item-tile>
      <span class="text-amber">
        <q-item-tile icon="star" v-for="n in 2" :key="n" />
      </span>
    </q-item-side>
  </q-item>
</q-list>
               
            
        </div>
      </div>
  

  </q-page>
</template>

<style>

</style>

<script>
export default {
  name: "PageIndex",
  data: () => ({
    columns: [
        { name: 'desc',          required: true,          label: 'Dessert (100g serving)',          align: 'left',          field: 'name',         sortable: true        },
        { name: 'calories', label: 'Calories', field: 'calories', sortable: true },
        { name: 'fat', label: 'Fat (g)', field: 'fat', sortable: true },
        { name: 'carbs', label: 'Carbs (g)', field: 'carbs' },
        { name: 'protein', label: 'Protein (g)', field: 'protein' },
        { name: 'sodium', label: 'Sodium (mg)', field: 'sodium' },
        { name: 'calcium', label: 'Calcium (%)', field: 'calcium', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10) },
        { name: 'iron', label: 'Iron (%)', field: 'iron', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10) }


    ],
    tableData: [
      {
        name: 'Frozen Yogurt',
        calories: 159,
        fat: 6.0,
        carbs: 24,
        protein: 4.0,
        sodium: 87,
        calcium: '14%',
        iron: '1%'
      },{
        name: 'ahmet orhan',
        calories: 159,
        fat: 6.0,
        carbs: 24,
        protein: 4.0,
        sodium: 87,
        calcium: '14%',
        iron: '1%'
      }
      ]
  })
  
};
</script>
