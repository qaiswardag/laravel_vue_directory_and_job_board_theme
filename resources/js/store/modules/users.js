import { vueFetch } from "use-lightweight-fetch";

// get users
const {
    handleData: handleGetUsers,
    fetchedData: fetchedUsers,
    isError: isErrorUsers,
    isLoading: isLoadingUsers,
    isSuccess: isSuccessUsers,
} = vueFetch();

export default {
    namespaced: true,

    // state
    state: {
        users: [],
        // end state
    },

    // getters
    getters: {
        getCurrentUsers(state) {
            return state.users;
        },
        // end getters
    },

    // mutations
    mutations: {
        setCurrentUsers(state, payload) {
            state.users = payload;
        },

        // end mutations
    },

    // actions
    actions: {
        // get images
        loadUsers(context, data) {
            if (data.search_query === undefined) {
                data.search_query = "";
            }
            if (data.page === undefined) {
                data.page = "";
            }
            console.log("came here:", data);

            // fetch media
            handleGetUsers(
                `/overview/attach/users/index/${data.teamId}/?search_query=${data.search_query}&page=${data.page}`
            );
            console.log("and here");
            // context & send to mutation
            context.commit("setCurrentUsers", {
                fetchedData: fetchedUsers,
                isError: isErrorUsers,
                isLoading: isLoadingUsers,
                isSuccess: isSuccessUsers,
            });
        },
        // end action
    },
};
