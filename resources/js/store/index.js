import { createStore } from "vuex";
import user from "./modules/user";
import users from "./modules/users";
import mediaLibrary from "./modules/media-library";

const store = createStore({
    modules: {
        user: user,
        users: users,
        mediaLibrary: mediaLibrary,
    },
});

export default store;
