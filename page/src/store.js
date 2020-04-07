import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    isLogin:false,
    userInfo:{},
    permission:[],
  },
  mutations: {
    isLogin(state,userInfo,permission){
      state.isLogin = true;
      state.userInfo = userInfo;
      state.permission = permission;
    }
  },
  actions: {

  }
})
