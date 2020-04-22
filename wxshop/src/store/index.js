import Vuex from '@wepy/x';
import states from "./states";
import mutations from "./mutations";
import actions from "./actions";

export default new Vuex.Store({
  state: states,
  mutations:mutations,
  actions: actions
});
