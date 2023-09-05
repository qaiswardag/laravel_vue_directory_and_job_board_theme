import { createStore } from "vuex";
import user from "./modules/user";
import attachedUsers from "./modules/attached-users";
import mediaLibrary from "./modules/media-library";
import userDashboard from "./modules/user-dashboard";
import designer from "./modules/designer";

const store = createStore({
    modules: {
        user: user,
        attachedUsers: attachedUsers,
        mediaLibrary: mediaLibrary,
        userDashboard: userDashboard,
        designer: designer,
    },
});

export default store;
