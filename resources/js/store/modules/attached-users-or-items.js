import { vueFetch } from "use-lightweight-fetch";

// get users
const {
    handleData: handleGetItems,
    fetchedData: fetchedItems,
    isError: isErrorItems,
    error: errorItems,
    errors: errorsItems,
    isLoading: isLoadingItems,
    isSuccess: isSuccessItems,
} = vueFetch();

export default {
    namespaced: true,

    // state
    state: {
        users: [],
        attachedUsers: [],

        componentCategories: [],
        attachedComponentCategories: [],

        postCategories: [],
        attachedPostCategories: [],

        jobCategories: [],
        attachedJobCategories: [],

        jobTypes: [],
        attachedJobTypes: [],

        storeCategories: [],
        attachedStoreCategories: [],
        // end state
    },

    // getters
    getters: {
        getCurrentUsers(state) {
            return state.users;
        },
        getCurrentAttachedUsers(state) {
            return state.attachedUsers;
        },

        getCurrentComponentategories(state) {
            return state.componentCategories;
        },
        getCurrentAttachedComponentategories(state) {
            return state.attachedComponentCategories;
        },

        getCurrentPostCategories(state) {
            return state.postCategories;
        },
        getCurrentAttachedPostCategories(state) {
            return state.attachedPostCategories;
        },

        getCurrentJobCategories(state) {
            return state.jobCategories;
        },
        getCurrentAttachedJobCategories(state) {
            return state.attachedJobCategories;
        },

        getCurrentJobTypes(state) {
            return state.jobTypes;
        },
        getCurrentAttachedJobTypes(state) {
            return state.attachedJobTypes;
        },

        getCurrentStoreCategories(state) {
            return state.storeCategories;
        },
        getCurrentAttachedStoreCategories(state) {
            return state.attachedStoreCategories;
        },
        // end getters
    },

    // mutations
    mutations: {
        setCurrentUsers(state, payload) {
            state.users = payload;
        },
        setCurrentComponentCategories(state, payload) {
            state.componentCategories = payload;
        },
        setCurrentPostCategories(state, payload) {
            state.postCategories = payload;
        },
        setCurrentJobCategories(state, payload) {
            state.jobCategories = payload;
        },
        setCurrentJobTypes(state, payload) {
            state.jobTypes = payload;
        },
        setCurrentStoreCategories(state, payload) {
            state.storeCategories = payload;
        },

        // users
        // users
        // users
        // users
        setCurrentAttachedUsers(state, payload) {
            // check if user already exists in the array
            const userExists = state.attachedUsers.some(
                (user) => user.id === payload.id
            );

            // if user does not exist, push to array
            if (userExists === false) {
                state.attachedUsers.unshift(payload);
            }
        },
        setCurrentAttachedUsersToEmptyArray(state, payload) {
            state.attachedUsers = payload;
        },
        setRemoveAttachedUser(state, payload) {
            state.attachedUsers = payload;
        },
        // component categories
        // component categories
        // component categories
        // component categories
        setCurrentAttachedComponentCategories(state, payload) {
            // check if user already exists in the array
            const itemExists = state.attachedComponentCategories.some(
                (item) => item.id === payload.id
            );

            // if user does not exist, push to array
            if (itemExists === false) {
                state.attachedComponentCategories.unshift(payload);
            }
        },
        setCurrentAttachedComponentCategoriesToEmptyArray(state, payload) {
            state.attachedComponentCategories = payload;
        },
        setRemoveAttachedComponentCategories(state, payload) {
            state.attachedComponentCategories = payload;
        },
        // post categories
        // post categories
        // post categories
        // post categories
        setCurrentAttachedPostCategories(state, payload) {
            // check if user already exists in the array
            const itemExists = state.attachedPostCategories.some(
                (item) => item.id === payload.id
            );

            // if user does not exist, push to array
            if (itemExists === false) {
                state.attachedPostCategories.unshift(payload);
            }
        },
        setCurrentAttachedPostCategoriesToEmptyArray(state, payload) {
            state.attachedPostCategories = payload;
        },
        setRemoveAttachedPostCategories(state, payload) {
            state.attachedPostCategories = payload;
        },
        // job categories
        // job categories
        // job categories
        // job categories
        setCurrentAttachedJobCategories(state, payload) {
            // check if user already exists in the array
            const itemExists = state.attachedJobCategories.some(
                (item) => item.id === payload.id
            );

            // if user does not exist, push to array
            if (itemExists === false) {
                state.attachedJobCategories.unshift(payload);
            }
        },
        setCurrentAttachedJobCategoriesToEmptyArray(state, payload) {
            state.attachedJobCategories = payload;
        },
        setRemoveAttachedJobCategories(state, payload) {
            state.attachedJobCategories = payload;
        },
        // job types
        // job types
        // job types
        // job types
        setCurrentAttachedJobTypes(state, payload) {
            // check if user already exists in the array
            const itemExists = state.attachedJobTypes.some(
                (item) => item.id === payload.id
            );

            // if user does not exist, push to array
            if (itemExists === false) {
                state.attachedJobTypes.unshift(payload);
            }
        },
        setCurrentAttachedJobTypesToEmptyArray(state, payload) {
            state.attachedJobTypes = payload;
        },
        setRemoveAttachedJobTypes(state, payload) {
            state.attachedJobTypes = payload;
        },
        // store categories
        // store categories
        // store categories
        // store categories
        setCurrentAttachedStoreCategories(state, payload) {
            // check if user already exists in the array
            const itemExists = state.attachedStoreCategories.some(
                (item) => item.id === payload.id
            );

            // if user does not exist, push to array
            if (itemExists === false) {
                state.attachedStoreCategories.unshift(payload);
            }
        },

        setCurrentAttachedStoreCategoriesToEmptyArray(state, payload) {
            state.attachedStoreCategories = payload;
        },
        setRemoveAttachedStoreCategories(state, payload) {
            state.attachedStoreCategories = payload;
        },

        // end mutations
    },

    // actions
    actions: {
        // get users
        async fetchUsers(context, data) {
            if (data.search_query === undefined) {
                data.search_query = "";
            }
            if (data.page === undefined) {
                data.page = "";
            }

            handleGetItems(
                route(data.apiUrlRouteName, {
                    team: data.teamId,
                    search_query: data.search_query,
                    page: data.page,
                })
            );

            // context & send to mutation
            context.commit("setCurrentUsers", {
                fetchedData: fetchedItems,
                isError: isErrorItems,
                error: errorItems,
                errors: errorsItems,
                isLoading: isLoadingItems,
                isSuccess: isSuccessItems,
            });
        },
        // get component categories
        async fetchComponentCategories(context, data) {
            console.log("kom her. data er:", data);
            if (data.search_query === undefined) {
                data.search_query = "";
            }
            if (data.page === undefined) {
                data.page = "";
            }

            handleGetItems(
                route(data.apiUrlRouteName, {
                    team: data.teamId,
                    search_query: data.search_query,
                    page: data.page,
                })
            );

            // context & send to mutation
            context.commit("setCurrentComponentCategories", {
                fetchedData: fetchedItems,
                isError: isErrorItems,
                error: errorItems,
                errors: errorsItems,
                isLoading: isLoadingItems,
                isSuccess: isSuccessItems,
            });
        },
        // get post categories
        async fetchPostCategories(context, data) {
            if (data.search_query === undefined) {
                data.search_query = "";
            }
            if (data.page === undefined) {
                data.page = "";
            }

            handleGetItems(
                route(data.apiUrlRouteName, {
                    team: data.teamId,
                    search_query: data.search_query,
                    page: data.page,
                })
            );

            // context & send to mutation
            context.commit("setCurrentPostCategories", {
                fetchedData: fetchedItems,
                isError: isErrorItems,
                error: errorItems,
                errors: errorsItems,
                isLoading: isLoadingItems,
                isSuccess: isSuccessItems,
            });
        },
        // get job categories
        async fetchJobCategories(context, data) {
            if (data.search_query === undefined) {
                data.search_query = "";
            }
            if (data.page === undefined) {
                data.page = "";
            }

            handleGetItems(
                route(data.apiUrlRouteName, {
                    team: data.teamId,
                    search_query: data.search_query,
                    page: data.page,
                })
            );

            // context & send to mutation
            context.commit("setCurrentJobCategories", {
                fetchedData: fetchedItems,
                isError: isErrorItems,
                error: errorItems,
                errors: errorsItems,
                isLoading: isLoadingItems,
                isSuccess: isSuccessItems,
            });
        },
        // get job types
        async fetchJobTypes(context, data) {
            if (data.search_query === undefined) {
                data.search_query = "";
            }
            if (data.page === undefined) {
                data.page = "";
            }

            handleGetItems(
                route(data.apiUrlRouteName, {
                    team: data.teamId,
                    search_query: data.search_query,
                    page: data.page,
                })
            );

            // context & send to mutation
            context.commit("setCurrentJobTypes", {
                fetchedData: fetchedItems,
                isError: isErrorItems,
                error: errorItems,
                errors: errorsItems,
                isLoading: isLoadingItems,
                isSuccess: isSuccessItems,
            });
        },
        // get store categories
        async fetchStoreCategories(context, data) {
            if (data.search_query === undefined) {
                data.search_query = "";
            }
            if (data.page === undefined) {
                data.page = "";
            }

            handleGetItems(
                route(data.apiUrlRouteName, {
                    team: data.teamId,
                    search_query: data.search_query,
                    page: data.page,
                })
            );

            // context & send to mutation
            context.commit("setCurrentStoreCategories", {
                fetchedData: fetchedItems,
                isError: isErrorItems,
                error: errorItems,
                errors: errorsItems,
                isLoading: isLoadingItems,
                isSuccess: isSuccessItems,
            });
        },
        // end action
    },
};
