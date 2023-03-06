import { vueFetch } from "use-lightweight-fetch";

const {
    handleData: handleGetImageData,
    fetchedData: fetchedImage,
    isError: isErrorImage,
    isLoading: isLoadingImage,
    isSuccess: isSuccessImage,
} = vueFetch();

export default {
    namespaced: true,

    // state
    state: {
        currentImage: {},
        // end state
    },

    // getters
    getters: {
        getCurrentImage(state) {
            return state.currentImage;
        },

        // end getters
    },

    // mutations
    mutations: {
        setCurrentImage(state, payload) {
            state.currentImage = payload;
        },

        // end mutations
    },

    // actions
    actions: {
        // get image
        getImage(context, data) {
            //
            handleGetImageData(
                `/overview/media/edit/${data.mediaLibraryId}/${data.teamId}`
            );
            // // context & send to mutation
            context.commit("setCurrentImage", { currentImage: fetchedImage });
        },
        // end action
    },
};
