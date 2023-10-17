export default {
    namespaced: true,

    // state
    state: {
        isLoading: false,

        // end state
    },

    // getters
    getters: {
        getIsLoading(state) {
            return state.isLoading;
        },
        // end getters
    },

    // mutations
    mutations: {
        setIsLoading(state, payload) {
            state.isLoading = payload;
        },
        // end mutations
    },

    // actions
    actions: {
        // end action
    },
};
