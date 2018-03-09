import Vue from 'vue'
import VueAxios from 'vue-axios'
import VueAuthenticate from 'vue-authenticate'
import axios from 'axios';


export default ({ Vue }) => {
  Vue.use(VueAxios, axios)
  Vue.use(VueAuthenticate, {
    baseUrl: 'http://localhost:8000/api/', // Your API domain
    
    providers: {
     
    }
  })
     
}

