import Vue from 'vue'
import VueRouter from 'vue-router'
//import VueBarcode from '@xkeshi/vue-barcode'
import routes from './routes'
import store from '../store'
import VueFilter from 'vue-filter';
import config from '../config';

//Vue.component('barcode', VueBarcode);

Vue.use(VueFilter); 
Vue.use(VueRouter);

Vue.prototype.apiUrl = config.apiUrl;
Vue.prototype.fileUrl = config.fileUrl;


const Router = new VueRouter({

  mode: 'history',
  base: process.env.VUE_ROUTER_BASE,
  scrollBehavior: () => ({ y: 0 }),
  routes
})


Router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth) && !store.getters.isAuthenticated) {
    next({ path: '/login'});
    
  } else {
    next();
  }
});


export default Router
