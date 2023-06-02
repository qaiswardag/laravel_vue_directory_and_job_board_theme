import { vueFetch } from "use-lightweight-fetch";

// get images
const {
    handleData: handleGetDashboardData,
    fetchedData: fetchedMedia,
    isError: isErrorImages,
    error: errorImages,
    errors: errorsImages,
    isLoading: isLoadingImages,
    isSuccess: isSuccessImages,
} = vueFetch();

export default {
    namespaced: true,

    // state
    state: {
        latestMedia: null,
        // end state
    },

    // getters
    getters: {
        getLatestMedia(state) {
            return state.latestMedia;
        },
        // end getters
    },

    // mutations
    mutations: {
        setLatestMedia(state, payload) {
            state.latestMedia = payload;
        },
        // end mutations
    },

    // actions
    actions: {
        // get images
        loadDashboardData(context, data) {
            handleGetDashboardData(
                route("overview.media.index", {
                    team: data.team.id,
                    search_query: data.search_query,
                    page: data.page,
                })
            );

            // context & send to mutation
            context.commit("setCurrentMedia", {
                fetchedMedia: fetchedMedia,
                isError: isErrorImages,
                error: errorImages,
                errors: errorsImages,
                isLoading: isLoadingImages,
                isSuccess: isSuccessImages,
            });
        },
        // end action
    },
};
