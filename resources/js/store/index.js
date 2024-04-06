import { createStore } from "vuex";
import user from "./modules/user";
import fashionStore from "./modules/fashion-store";
import team from "./modules/team";
import attachedUsersOrItems from "./modules/attached-users-or-items";
import mediaLibrary from "./modules/media-library";
import userDashboard from "./modules/user-dashboard";
import pageBuilderState from "./modules/page-builder-state";

const store = createStore({
    modules: {
        user: user,
        team: team,
        fashionStore: fashionStore,
        attachedUsersOrItems: attachedUsersOrItems,
        mediaLibrary: mediaLibrary,
        userDashboard: userDashboard,
        pageBuilderState: pageBuilderState,
    },
});

export default store;
