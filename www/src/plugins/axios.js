import axios from 'axios'
axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest',
  'Content-type': 'application/x-www-form-urlencoded',
 
};

export default ({ Vue }) => {
  Vue.prototype.$axios = axios;

  //401 hatasında direk redirect yap
  Vue.axios.interceptors.response.use((response) => { 
    return response
  }, function (error) {
    let originalRequest = error.config
    if (error.response.status === 401 ) { 
      window.location.href = '/login'
        
      return
    }
    
    return Promise.reject(error)
  })
}
