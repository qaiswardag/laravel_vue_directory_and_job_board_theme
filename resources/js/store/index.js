import { createStore } from "vuex";
import user from "./modules/user";
import attachedUsersOrItems from "./modules/attached-users-or-items";
import mediaLibrary from "./modules/media-library";
import userDashboard from "./modules/user-dashboard";
import pageBuilderState from "./modules/page-builder-state";

const store = createStore({
    modules: {
        user: user,
        attachedUsersOrItems: attachedUsersOrItems,
        mediaLibrary: mediaLibrary,
        userDashboard: userDashboard,
        pageBuilderState: pageBuilderState,
    },
});

export default store;
