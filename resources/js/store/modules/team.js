export default {
    namespaced: true,

    // state
    state: {
        openingHours: null,

        // end state
    },

    // getters
    getters: {
        getOpeningHoursTeam(state) {
            return state.openingHours;
        },
        // end getters
    },

    // mutations
    mutations: {
        setOpeningHoursStore(state, payload) {
            state.openingHours = payload;
        },
        // end mutations
    },

    // actions
    actions: {
        // end action
    },
};
