import { createStore } from "vuex";
import user from "./modules/user";
import mediaLibrary from "./modules/media-library";

const store = createStore({
    modules: {
        user: user,
        mediaLibrary: mediaLibrary,
    },
});

export default store;
