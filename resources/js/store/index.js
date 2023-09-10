import { createStore } from "vuex";
import user from "./modules/user";
import attachedUsersOrItems from "./modules/attached-users-or-items";
import mediaLibrary from "./modules/media-library";
import userDashboard from "./modules/user-dashboard";
import designer from "./modules/designer";

const store = createStore({
    modules: {
        user: user,
        attachedUsersOrItems: attachedUsersOrItems,
        mediaLibrary: mediaLibrary,
        userDashboard: userDashboard,
        designer: designer,
    },
});

export default store;
