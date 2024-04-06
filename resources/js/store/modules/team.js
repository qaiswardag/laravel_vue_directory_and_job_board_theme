export default {
    namespaced: true,

    // state
    state: {
        monday_opening_time: null,
        monday_closing_time: null,

        tuesday_opening_time: null,
        tuesday_closing_time: null,

        wednesday_opening_time: null,
        wednesday_closing_time: null,

        thursday_opening_time: null,
        thursday_closing_time: null,

        friday_opening_time: null,
        friday_closing_time: null,

        saturday_opening_time: null,
        saturday_closing_time: null,

        sunday_opening_time: null,
        sunday_closing_time: null,

        // end state
    },

    // getters
    getters: {
        getMondayOpeningTime(state) {
            return state.monday_opening_time;
        },
        getMondayClosingTime(state) {
            return state.monday_closing_time;
        },

        getTuesdayOpeningTime(state) {
            return state.tuesday_opening_time;
        },
        getTuesdayClosingTime(state) {
            return state.tuesday_closing_time;
        },

        getWednesdayOpeningTime(state) {
            return state.wednesday_opening_time;
        },
        getWednesdayClosingTime(state) {
            return state.wednesday_closing_time;
        },

        getThursdayOpeningTime(state) {
            return state.thursday_opening_time;
        },
        getThursdayClosingTime(state) {
            return state.thursday_closing_time;
        },

        getFridayOpeningTime(state) {
            return state.friday_opening_time;
        },
        getFridayClosingTime(state) {
            return state.friday_closing_time;
        },

        getSaturdayOpeningTime(state) {
            return state.saturday_opening_time;
        },
        getSaturdayClosingTime(state) {
            return state.saturday_closing_time;
        },

        getSundayOpeningTime(state) {
            return state.sunday_opening_time;
        },
        getSundayClosingTime(state) {
            return state.sunday_closing_time;
        },

        // end getters
    },

    // mutations
    mutations: {
        setMondayOpeningTime(state, payload) {
            state.monday_opening_time = payload;
        },
        setMondayClosingTime(state, payload) {
            state.monday_closing_time = payload;
        },

        setTuesdayOpeningTime(state, payload) {
            state.tuesday_opening_time = payload;
        },
        setTuesdayClosingTime(state, payload) {
            state.tuesday_closing_time = payload;
        },

        setWednesdayOpeningTime(state, payload) {
            state.wednesday_opening_time = payload;
        },
        setWednesdayClosingTime(state, payload) {
            state.wednesday_closing_time = payload;
        },

        setThursdayOpeningTime(state, payload) {
            state.thursday_opening_time = payload;
        },
        setThursdayClosingTime(state, payload) {
            state.thursday_closing_time = payload;
        },

        setFridayOpeningTime(state, payload) {
            state.friday_opening_time = payload;
        },
        setFridayClosingTime(state, payload) {
            state.friday_closing_time = payload;
        },

        setSaturdayOpeningTime(state, payload) {
            state.saturday_opening_time = payload;
        },
        setSaturdayClosingTime(state, payload) {
            state.saturday_closing_time = payload;
        },

        setSundayOpeningTime(state, payload) {
            state.sunday_opening_time = payload;
        },
        setSundayClosingTime(state, payload) {
            state.sunday_closing_time = payload;
        },

        // end mutations
    },

    // actions
    actions: {
        // end action
    },
};
