import Vue from 'vue'
import Vuex from 'vuex'
import VueAxios from 'vue-axios'
import { VueAuthenticate } from 'vue-authenticate'
import axios from 'axios';


export default ({ Vue }) => {
    Vue.use(Vuex)
    Vue.use(VueAxios, axios)
    
    const vueAuth = new VueAuthenticate(Vue.prototype.$http, {
      baseUrl: 'http://localhost:8000/api/'
    })
     
}

