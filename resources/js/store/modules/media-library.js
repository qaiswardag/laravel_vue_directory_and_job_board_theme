import { vueFetch } from "use-lightweight-fetch";

// get image
const {
    handleData: handleGetImage,
    fetchedData: fetchedImage,
    isError: isErrorImage,
    isLoading: isLoadingImage,
    isSuccess: isSuccessImage,
} = vueFetch();

// get images
const {
    handleData: handleGetImages,
    fetchedData: fetchedMedia,
    isError: isErrorImages,
    isLoading: isLoadingImages,
    isSuccess: isSuccessImages,
} = vueFetch();

export default {
    namespaced: true,

    // state
    state: {
        currentImage: {},
        currentMedia: null,
        // end state
    },

    // getters
    getters: {
        getCurrentImage(state) {
            return state.currentImage;
        },
        getCurrentMedia(state) {
            return state.currentMedia;
        },

        // end getters
    },

    // mutations
    mutations: {
        setCurrentImage(state, payload) {
            state.currentImage = payload;
        },
        setCurrentMedia(state, payload) {
            state.currentMedia = payload;
        },

        // end mutations
    },

    // actions
    actions: {
        // get images
        loadMedia(context, data) {
            // fetch media
            handleGetImages(
                `/overview/media/index/${data.teamId}/?page=${data.page}`
            );
            // context & send to mutation
            context.commit("setCurrentMedia", {
                fetchedMedia: fetchedMedia,
                isError: isErrorImages,
                isLoading: isLoadingImages,
                isSuccess: isSuccessImages,
            });
        },

        //
        // get image
        loadImage(context, data) {
            // fetch image
            handleGetImage(
                `/overview/media/edit/${data.mediaLibraryId}/${data.teamId}`
            );
            // // context & send to mutation
            context.commit("setCurrentImage", {
                currentImage: fetchedImage,
                isError: isErrorImage,
                isLoading: isLoadingImage,
                isSuccess: isSuccessImage,
            });
        },
        // end action
    },
};
