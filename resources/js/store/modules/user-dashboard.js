import { vueFetch } from "@/composables/vueFetch";
// get images
const {
    handleData: handleGetDashboardStats,
    fetchedData: fetchedDashboardStats,
    isError: isErrorDashboardStats,
    error: errorDashboardStats,
    errors: errorsDashboardStats,
    isLoading: isLoadingDashboardStats,
    isSuccess: isSuccessDashboardStats,
} = vueFetch();

export default {
    namespaced: true,

    // state
    state: {
        dashboardStats: null,
        // end state
    },

    // getters
    getters: {
        getDashboardStats(state) {
            return state.dashboardStats;
        },
        // end getters
    },

    // mutations
    mutations: {
        setDashboardStats(state, payload) {
            state.dashboardStats = payload;
        },
        // end mutations
    },

    // actions
    actions: {
        // get
        loadDashboardStats(context, data) {
            handleGetDashboardStats(
                route("user.dashboard.stats", {
                    teamId: data.teamId,
                }),
                {},
                {}
            );

            // context & send to mutation
            context.commit("setDashboardStats", {
                fetchedData: fetchedDashboardStats,
                isError: isErrorDashboardStats,
                error: errorDashboardStats,
                errors: errorsDashboardStats,
                isLoading: isLoadingDashboardStats,
                isSuccess: isSuccessDashboardStats,
            });
        },
        // end action
    },
};
