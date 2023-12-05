import { vueFetch } from "@/composables/vueFetch";
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

        postStores: [],
        attachedPostStores: [],

        jobStates: [],
        attachedJobStates: [],

        jobCountries: [],
        attachedJobCountries: [],

        jobCategories: [],
        attachedJobCategories: [],

        jobTypes: [],
        attachedJobTypes: [],

        storeStates: [],
        attachedStoreStates: [],

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

        getCurrentComponentCategories(state) {
            return state.componentCategories;
        },
        getCurrentAttachedComponentCategories(state) {
            return state.attachedComponentCategories;
        },

        getCurrentPostCategories(state) {
            return state.postCategories;
        },
        getCurrentAttachedPostCategories(state) {
            return state.attachedPostCategories;
        },

        getCurrentPostStores(state) {
            return state.postStores;
        },
        getCurrentAttachedPostStores(state) {
            return state.attachedPostStores;
        },

        getCurrentJobCountries(state) {
            return state.jobCountries;
        },
        getCurrentAttachedJobCountries(state) {
            return state.attachedJobCountries;
        },

        getCurrentJobStates(state) {
            return state.jobStates;
        },
        getCurrentAttachedJobStates(state) {
            return state.attachedJobStates;
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

        getCurrentStoreStates(state) {
            return state.storeStates;
        },
        getCurrentAttachedStoreStates(state) {
            return state.attachedStoreStates;
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
        setCurrentPostStores(state, payload) {
            state.postStores = payload;
        },
        setCurrentJobCountries(state, payload) {
            state.jobCountries = payload;
        },
        setCurrentJobStates(state, payload) {
            state.jobStates = payload;
        },
        setCurrentJobCategories(state, payload) {
            state.jobCategories = payload;
        },
        setCurrentJobTypes(state, payload) {
            state.jobTypes = payload;
        },
        setCurrentStoreStates(state, payload) {
            state.storeStates = payload;
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
        // post store
        // post store
        // post store
        // post store
        setCurrentAttachedPostStores(state, payload) {
            // check if user already exists in the array
            const itemExists = state.attachedPostStores.some(
                (item) => item.id === payload.id
            );

            // if user does not exist, push to array
            if (itemExists === false) {
                state.attachedPostStores.unshift(payload);
            }
        },
        setCurrentAttachedPostStoresToEmptyArray(state, payload) {
            state.attachedPostStores = payload;
        },
        setRemoveAttachedPostStores(state, payload) {
            state.attachedPostStores = payload;
        },
        // job countries
        // job countries
        // job countries
        // job countries
        setCurrentAttachedJobCountries(state, payload) {
            // check if user already exists in the array
            const itemExists = state.attachedJobCountries.some(
                (item) => item.id === payload.id
            );

            // if user does not exist, push to array
            if (itemExists === false) {
                state.attachedJobCountries.unshift(payload);
            }
        },
        setCurrentAttachedJobCountriesToEmptyArray(state, payload) {
            state.attachedJobCountries = payload;
        },
        setRemoveAttachedJobCountries(state, payload) {
            state.attachedJobCountries = payload;
        },
        // job states
        // job states
        // job states
        // job states
        setCurrentAttachedJobStates(state, payload) {
            // check if state already exists in the array
            const itemExists = state.attachedJobStates.some(
                (item) => item.id === payload.id
            );

            // if state does not exist, push to array
            if (itemExists === false) {
                state.attachedJobStates.unshift(payload);
            }
        },
        setCurrentAttachedJobStatesToEmptyArray(state, payload) {
            state.attachedJobStates = payload;
        },
        setRemoveAttachedJobStates(state, payload) {
            state.attachedJobStates = payload;
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
        // store states
        // store states
        // store states
        // store states
        setCurrentAttachedStoreStates(state, payload) {
            // check if user already exists in the array
            const itemExists = state.attachedStoreStates.some(
                (item) => item.id === payload.id
            );

            // if user does not exist, push to array
            if (itemExists === false) {
                state.attachedStoreStates.unshift(payload);
            }
        },

        setCurrentAttachedStoreStatesToEmptyArray(state, payload) {
            state.attachedStoreStates = payload;
        },
        setRemoveAttachedStoreStates(state, payload) {
            state.attachedStoreStates = payload;
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

        // get post stores
        async fetchPostStores(context, data) {
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
            context.commit("setCurrentPostStores", {
                fetchedData: fetchedItems,
                isError: isErrorItems,
                error: errorItems,
                errors: errorsItems,
                isLoading: isLoadingItems,
                isSuccess: isSuccessItems,
            });
        },
        // get job countries
        async fetchJobCountries(context, data) {
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
            context.commit("setCurrentJobCountries", {
                fetchedData: fetchedItems,
                isError: isErrorItems,
                error: errorItems,
                errors: errorsItems,
                isLoading: isLoadingItems,
                isSuccess: isSuccessItems,
            });
        },
        // get job states
        async fetchJobStates(context, data) {
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
            context.commit("setCurrentJobStates", {
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
        // get store states
        async fetchStoreStates(context, data) {
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
            context.commit("setCurrentStoreStates", {
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
