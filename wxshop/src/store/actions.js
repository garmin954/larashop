export default {
    increment ({ commit }) {
        commit('increment');
    },
    decrement ({ commit }) {
        commit('decrement');
    },
    incrementAsync ({ commit }) {
        setTimeout(() => {
            commit('increment');
        }, 1000);
    }
}