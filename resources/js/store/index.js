import { createStore } from "vuex";
import user from "./modules/user";
import attachedUsers from "./modules/attached-users";
import mediaLibrary from "./modules/media-library";
import userTeamDashboard from "./modules/user-team-dashboard";

const store = createStore({
    modules: {
        user: user,
        attachedUsers: attachedUsers,
        mediaLibrary: mediaLibrary,
        userTeamDashboard: userTeamDashboard,
    },
});

export default store;
