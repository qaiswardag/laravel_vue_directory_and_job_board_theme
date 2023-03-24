import { createStore } from "vuex";
import user from "./modules/user";
import attachedUsers from "./modules/attached-users";
import mediaLibrary from "./modules/media-library";

const store = createStore({
    modules: {
        user: user,
        attachedUsers: attachedUsers,
        mediaLibrary: mediaLibrary,
    },
});

export default store;
