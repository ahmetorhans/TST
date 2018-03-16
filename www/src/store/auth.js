import Vue from "vue";
import Vuex from "vuex";
import VueAxios from "vue-axios";
import { VueAuthenticate } from "vue-authenticate";
import axios from "axios";
import config from "../config";

Vue.use(Vuex);
Vue.use(VueAxios, axios);

const vueAuth = new VueAuthenticate(Vue.prototype.$http, {
  baseUrl: config.apiUrl
});
export default {
  state: {
    isAuthenticated: false,
    guard: []
  },

  getters: {
    isAuthenticated(state) {
      if (state.isAuthenticated === true) {
        return true;
      }
      return vueAuth.isAuthenticated();
    },
    getGuard(state) {
      return state.guard;
    }
  },

  mutations: {
    isAuthenticated(state, payload) {
      state.isAuthenticated = payload.isAuthenticated;
    },
    mutationGuard(state, data) {
      state.guard = data.sonuc;
    }
  },

  actions: {
    actionGuard(context, payload) {
      axios.get(config.apiUrl + "guard").then(response => {
        context.commit("mutationGuard", { sonuc: response.data });
      });
    },
    login(context, payload) {
      return new Promise((resolve, reject) => {
        vueAuth.login(payload.user, payload.requestOptions).then(response => {
          context.commit("isAuthenticated", {
            isAuthenticated: vueAuth.isAuthenticated()
          });
          resolve(response.data);
        });
      });
    }
  }
};
