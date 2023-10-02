export default {
    namespaced: true,

    // state
    state: {
        paymentMethod: null,
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
        getPaymentMethod(state) {
            return state.paymentMethod;
        },

        // end getters
    },

    // mutations
    mutations: {
        setPaymentMethod(state, payload) {
            state.paymentMethod = payload;
        },
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
