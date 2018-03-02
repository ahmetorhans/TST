import Vue from 'vue'
import Vuex from 'vuex'
import VueAxios from 'vue-axios'
import { VueAuthenticate } from 'vue-authenticate'
import axios from 'axios';
Vue.use(Vuex)
Vue.use(VueAxios, axios)
const vueAuth = new VueAuthenticate(Vue.prototype.$http, {
  baseUrl: 'http://localhost:8000/api/'
})
export default ({
    state: {
      isAuthenticated: false
    },
  
   
    getters: {
      isAuthenticated (state) {
          if (state.isAuthenticated===true){
            return true;
          }
        return vueAuth.isAuthenticated()
      }
    },
  
   
    mutations: {
      isAuthenticated (state, payload) {
        console.log("mutatio");
        state.isAuthenticated = payload.isAuthenticated
      }
    },
  
    actions: {
  
     
      login (context, payload) {
      
 
        
        return new Promise((resolve, reject) => {
          vueAuth.login(payload.user, payload.requestOptions).then((response) => {
         
            context.commit('isAuthenticated', {
              isAuthenticated: vueAuth.isAuthenticated()
              
            })
            resolve(response.data); 
          })
        })
        
        
  
      }
    }
  })