export default {
    namespaced: true,

    // state
    state: {
        user: {
            id: 1,
            name: "Joe Doe",
            email: "joe-doe@meeshop.dk",
        },
        // end state
    },

    // getters
    getters: {
        getCurrentUser(state) {
            return state.user;
        },
        // end getters
    },

    // mutations
    mutations: {
        setCurrentUser(state, payload) {
            state.user = payload;
        },

        // end mutations
    },

    // actions
    actions: {
        // end action
    },
};
