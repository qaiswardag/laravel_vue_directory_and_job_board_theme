<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { ref, computed, onBeforeMount, watch, onMounted, nextTick } from "vue";
import { Switch } from "@headlessui/vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import Tags from "@/Components/Forms/Tags.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import { useStore } from "vuex";
import slugify from "slugify";
import config from "@/utils/config";
import SearchUsersOrItems from "@/Components/Search/SearchUsersOrItems.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import OpeningClosingHours from "@/Components/OpeningClosingHours/OpeningClosingHours.vue";
import {
    PageBuilder,
    PageBuilderClass,
    sharedPageBuilderStore,
} from "vue-website-page-builder";
import "vue-website-page-builder/style.css";

import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";

import {
    CheckIcon,
    ChevronUpDownIcon,
    LockClosedIcon,
    LockOpenIcon,
    SquaresPlusIcon,
    TrashIcon,
    UserIcon,
    UserPlusIcon,
    XMarkIcon,
    Squares2X2Icon,
    PhotoIcon,
    MapPinIcon,
    PlusIcon,
    FolderPlusIcon,
    MinusIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    currentUserTeamRole: {
        required: false,
    },
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
    post: {
        default: null,
        required: false,
    },
    postAuthor: {
        default: null,
        required: false,
    },
    categories: {
        default: null,
        required: false,
    },
    states: {
        default: null,
        required: false,
    },
    coverImages: {
        default: null,
        required: false,
    },
    brandLogos: {
        default: null,
        required: false,
    },
});

const modalShowClearForm = ref(false);

// modal content
const typeModal = ref("");
const gridColumnModal = ref(Number(1));
const titleModal = ref("");
const descriptionModal = ref("");
const firstButtonModal = ref("");
const secondButtonModal = ref(null);
const thirdButtonModal = ref(null);
// set dynamic modal handle functions
const firstModalButtonFunction = ref(null);
const secondModalButtonFunction = ref(null);
const thirdModalButtonFunction = ref(null);

// Check if the user has a role other than 'reader' for this team
const userTeamsWithoutReaderRole = props.user.all_teams.filter((team) => {
    if (team.membership !== undefined && team.membership.role === "reader") {
        return team.membership.role !== "reader";
    }

    return team;
});

// store
const store = useStore();

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});
const getCurrentAttachedUsers = computed(() => {
    return store.getters["attachedUsersOrItems/getCurrentAttachedUsers"];
});

const getCurrentAttachedStoreStates = computed(() => {
    return store.getters["attachedUsersOrItems/getCurrentAttachedStoreStates"];
});
const getCurrentAttachedStoreCategories = computed(() => {
    return store.getters[
        "attachedUsersOrItems/getCurrentAttachedStoreCategories"
    ];
});

const formType = ref("create");

const pathLocalStorage = `store-form-${
    props.currentUserTeam ? props.currentUserTeam.id : null
}`;
const pathPageBuilderLocalStorageCreate = `page-builder-create-store-team-${
    props.currentUserTeam ? props.currentUserTeam.id : null
}`;

const pathPageBuilderLocalStorageUpdateDraft = ref(null);

// use media library
const showMediaLibraryModal = ref(false);
// modal content
const titleMedia = ref("");
const descriptionMedia = ref("");
const firstButtonMedia = ref("");
const secondButtonMedia = ref(null);
const thirdButtonMedia = ref(null);
// set dynamic modal handle functions
const firstMediaButtonFunction = ref(null);
const secondMediaButtonFunction = ref(null);
const thirdMediaButtonFunction = ref(null);

const handleUploadCoverImage = function () {
    // handle show media library modal
    showMediaLibraryModal.value = true;

    // set media library modal standards
    titleMedia.value = "Media Library";
    descriptionMedia.value = null;
    firstButtonMedia.value = "Close";
    secondButtonMedia.value = "Select image";
    thirdButtonMedia.value = null;
    // handle click
    firstMediaButtonFunction.value = function () {
        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    //
    // handle click
    secondMediaButtonFunction.value = function () {
        if (Array.isArray(postForm.cover_image) === false) {
            postForm.cover_image = [];
        }

        const idExists = postForm.cover_image?.some((item) => {
            return (
                item.id === getCurrentImage.value.currentImage.mediaLibrary.id
            );
        });

        if (idExists === false && Array.isArray(postForm.cover_image)) {
            postForm.cover_image.unshift(
                getCurrentImage.value.currentImage.mediaLibrary
            );
        }

        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
const handleUploadBrandLogo = function () {
    // handle show media library modal
    showMediaLibraryModal.value = true;

    // set media library modal standards
    titleMedia.value = "Media Library";
    descriptionMedia.value = null;
    firstButtonMedia.value = "Close";
    secondButtonMedia.value = "Select image";
    thirdButtonMedia.value = null;
    // handle click
    firstMediaButtonFunction.value = function () {
        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    //
    // handle click
    secondMediaButtonFunction.value = function () {
        if (Array.isArray(postForm.brand_logo) === false) {
            postForm.brand_logo = [];
        }

        const idExists = postForm.brand_logo?.some((item) => {
            return (
                item.id === getCurrentImage.value.currentImage.mediaLibrary.id
            );
        });

        if (idExists === false && Array.isArray(postForm.brand_logo)) {
            postForm.brand_logo.unshift(
                getCurrentImage.value.currentImage.mediaLibrary
            );
        }

        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
const handleRemoveBrandLogo = function (imageId) {
    postForm.brand_logo = postForm.brand_logo.filter(
        (image) => image.id !== imageId
    );
};

const removePrimaryImageBrandLogo = function (imageId) {
    postForm.brand_logo = postForm.brand_logo.map((image) => {
        return {
            ...image,
            pivot: {
                ...image.pivot,
                primary: image.id === imageId ? false : image?.pivot?.primary,
            },
        };
    });
};

const setAsPrimaryImageBrandLogo = function (imageId) {
    postForm.brand_logo = postForm.brand_logo.map((image) => {
        if (image.id === imageId) {
            return {
                ...image,
                pivot: {
                    ...image.pivot,
                    primary: true,
                },
            };
        } else {
            return {
                ...image,
                pivot: {
                    ...image.pivot,
                    primary: false,
                },
            };
        }
    });
};

const removePrimaryImage = function (imageId) {
    postForm.brand_logo = postForm.brand_logo.map((image) => {
        return {
            ...image,
            pivot: {
                ...image.pivot,
                primary: image.id === imageId ? false : image?.pivot?.primary,
            },
        };
    });
};

const setAsPrimaryImage = function (imageId) {
    postForm.cover_image = postForm.cover_image.map((image) => {
        if (image.id === imageId) {
            return {
                ...image,
                pivot: {
                    ...image.pivot,
                    primary: true,
                },
            };
        } else {
            return {
                ...image,
                pivot: {
                    ...image.pivot,
                    primary: false,
                },
            };
        }
    });
};

const handleRemoveCoverImage = function (imageId) {
    postForm.cover_image = postForm.cover_image.filter(
        (image) => image.id !== imageId
    );
};

const showSearchStoreStatesModal = ref(false);
const showSearchStoreCategoriesModal = ref(false);
const showSearchUserModal = ref(false);

// modal content
const titleModalSearchItems = ref("");
const descriptionModalSearchItems = ref("");
const firstButtonModalSearchItems = ref("");
const secondButtonModalSearchItems = ref(null);
// set dynamic modal handle functions
const firstModalButtonSearchItemsFunction = ref(null);
const secondModalButtonSearchItemsFunction = ref(null);

const handleAddAuthor = function () {
    // handle show modal for unique content
    showSearchUserModal.value = true;

    titleModalSearchItems.value = "Assign people";
    descriptionModalSearchItems.value = "Assign people to this store.";
    firstButtonModalSearchItems.value = "Close";
    secondButtonModalSearchItems.value = "Save";
    // handle click
    firstModalButtonSearchItemsFunction.value = function () {
        // handle show modal for unique content
        showSearchUserModal.value = false;
    };
    // handle click
    secondModalButtonSearchItemsFunction.value = function () {
        const currentAttachedUsers = [...getCurrentAttachedUsers.value];
        // Set post form author to the non-reactive copy
        postForm.author = currentAttachedUsers;

        // handle show modal for unique content
        showSearchUserModal.value = false;
    };

    // end modal
};

const handleRemoveAttachedUser = function (userId) {
    // filter the array to exclude user with matching ID
    postForm.author = postForm.author.filter((user) => user.id !== userId);
};

const handleRemoveAttachedCategory = function (itemId) {
    // filter the array to exclude item with matching ID
    postForm.categories = postForm.categories.filter(
        (item) => item.id !== itemId
    );
};
const handleAddCategories = function () {
    // handle show modal for unique content
    showSearchStoreCategoriesModal.value = true;

    titleModalSearchItems.value = "Add Store Categories";
    descriptionModalSearchItems.value = "Add Store Categories";
    firstButtonModalSearchItems.value = "Close";
    secondButtonModalSearchItems.value = "Save";
    // handle click
    firstModalButtonSearchItemsFunction.value = function () {
        // handle show modal for unique content
        showSearchStoreCategoriesModal.value = false;
    };
    // handle click
    secondModalButtonSearchItemsFunction.value = function () {
        const currentAttachedPostCategories = [
            ...getCurrentAttachedStoreCategories.value,
        ];
        // Set post form author to the non-reactive copy
        postForm.categories = currentAttachedPostCategories;

        // handle show modal for unique content
        showSearchStoreCategoriesModal.value = false;
    };

    // end modal
};

const handleRemoveAttachedStates = function (itemId) {
    // filter the array to exclude item with matching ID
    postForm.states = postForm.states.filter((item) => item.id !== itemId);
};
const handleAddStates = function () {
    // handle show modal for unique content
    showSearchStoreStatesModal.value = true;

    titleModalSearchItems.value = "Add Store State";
    descriptionModalSearchItems.value = "Add Store State";
    firstButtonModalSearchItems.value = "Close";
    secondButtonModalSearchItems.value = "Save";
    // handle click
    firstModalButtonSearchItemsFunction.value = function () {
        // handle show modal for unique content
        showSearchStoreStatesModal.value = false;
    };
    // handle click
    secondModalButtonSearchItemsFunction.value = function () {
        const currentAttachedPostCategories = [
            ...getCurrentAttachedStoreStates.value,
        ];
        // Set post form author to the non-reactive copy
        postForm.states = currentAttachedPostCategories;

        // handle show modal for unique content
        showSearchStoreStatesModal.value = false;
    };

    // end modal
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

const isSlugEditable = ref(false);
const slugValueTitle = ref("");
const slugValueCustom = ref("");

const postForm = useForm({
    title: "",
    slug: "",
    brand_website_url: "",
    address: "",
    contact_page_url: "",
    floor: "",
    content: "",
    published: true,
    team: props.currentUserTeam,
    user_id: props.user.id,

    time_zone: "Asia/Dubai",
    use_team_opening_hours: false,
    use_store_opening_hours: false,

    monday_opening_time_store: null,
    monday_closing_time_store: null,

    tuesday_opening_time_store: null,
    tuesday_closing_time_store: null,

    wednesday_opening_time_store: null,
    wednesday_closing_time_store: null,

    thursday_opening_time_store: null,
    thursday_closing_time_store: null,

    friday_opening_time_store: null,
    friday_closing_time_store: null,

    saturday_opening_time_store: null,
    saturday_closing_time_store: null,

    sunday_opening_time_store: null,
    sunday_closing_time_store: null,

    monday_opening_time_team: null,
    monday_closing_time_team: null,

    tuesday_opening_time_team: null,
    tuesday_closing_time_team: null,

    wednesday_opening_time_team: null,
    wednesday_closing_time_team: null,

    thursday_opening_time_team: null,
    thursday_closing_time_team: null,

    friday_opening_time_team: null,
    friday_closing_time_team: null,

    saturday_opening_time_team: null,
    saturday_closing_time_team: null,

    sunday_opening_time_team: null,
    sunday_closing_time_team: null,

    tags: "",
    show_author: false,
    author: [],
    states: [],
    categories: [],
    cover_image: [],
    brand_logo: [],
});

// The above code uses the watch function from Vue 3 to watch for changes to the
// slugValueCustom property and update the postForm.slug field with the new value
const watchSlugInputChanges = function () {
    watch(
        () => slugValueCustom.value,
        (newValue) => {
            if (isSlugEditable.value === true) {
                postForm.slug = slugify(
                    slugValueCustom.value,
                    config.slugifyOptions
                );
            }
        },
        { immediate: true }
    );
    watch(
        () => postForm.title,
        (newValue) => {
            if (isSlugEditable.value === false) {
                postForm.slug = slugify(postForm.title, config.slugifyOptions);
                slugValueTitle.value = slugify(
                    postForm.title,
                    config.slugifyOptions
                );
            }
        },
        { immediate: true }
    );
};

watch(
    () => isSlugEditable.value,
    (newValue) => {
        watchSlugInputChanges();
    },
    { immediate: true }
);

const handleCloseLock = function () {
    isSlugEditable.value = false;
};
const handleOpenLock = function () {
    isSlugEditable.value = true;
};
const handleTags = function (tags) {
    postForm.tags = tags;
};
const handleCreatePost = function () {
    // try to store post
    createPost();
};

const submittedOnUpdate = ref(true);

// watch store opening hours # start
// monday # start
const getMondayOpeningTime = computed(() => {
    return store.getters["fashionStore/getMondayOpeningTime"];
});
//

watch(getMondayOpeningTime, (newValue) => {
    postForm.monday_opening_time_store = newValue;
});

const getMondayClosingTime = computed(() => {
    return store.getters["fashionStore/getMondayClosingTime"];
});
//

watch(getMondayClosingTime, (newValue) => {
    postForm.monday_closing_time_store = newValue;
});
// monday # end

// tuesday # start
const getTuesdayOpeningTime = computed(() => {
    return store.getters["fashionStore/getTuesdayOpeningTime"];
});
//

watch(getTuesdayOpeningTime, (newValue) => {
    postForm.tuesday_opening_time_store = newValue;
});

const getTuesdayClosingTime = computed(() => {
    return store.getters["fashionStore/getTuesdayClosingTime"];
});
//

watch(getTuesdayClosingTime, (newValue) => {
    postForm.tuesday_closing_time_store = newValue;
});
// tuesday # end

// wednesday # start
const getWednesdayOpeningTime = computed(() => {
    return store.getters["fashionStore/getWednesdayOpeningTime"];
});
//

watch(getWednesdayOpeningTime, (newValue) => {
    postForm.wednesday_opening_time_store = newValue;
});

const getWednesdayClosingTime = computed(() => {
    return store.getters["fashionStore/getWednesdayClosingTime"];
});
//

watch(getWednesdayClosingTime, (newValue) => {
    postForm.wednesday_closing_time_store = newValue;
});
// wednesday # end

// thursday # start
const getThursdayOpeningTime = computed(() => {
    return store.getters["fashionStore/getThursdayOpeningTime"];
});
//

watch(getThursdayOpeningTime, (newValue) => {
    postForm.thursday_opening_time_store = newValue;
});

const getThursdayClosingTime = computed(() => {
    return store.getters["fashionStore/getThursdayClosingTime"];
});
//

watch(getThursdayClosingTime, (newValue) => {
    postForm.thursday_closing_time_store = newValue;
});
// thursday # end

// friday # start
const getFridayOpeningTime = computed(() => {
    return store.getters["fashionStore/getFridayOpeningTime"];
});
//

watch(getFridayOpeningTime, (newValue) => {
    postForm.friday_opening_time_store = newValue;
});

const getFridayClosingTime = computed(() => {
    return store.getters["fashionStore/getFridayClosingTime"];
});
//

watch(getFridayClosingTime, (newValue) => {
    postForm.friday_closing_time_store = newValue;
});
// friday # end

// saturday # start
const getSaturdayOpeningTime = computed(() => {
    return store.getters["fashionStore/getSaturdayOpeningTime"];
});
//

watch(getSaturdayOpeningTime, (newValue) => {
    postForm.saturday_opening_time_store = newValue;
});

const getSaturdayClosingTime = computed(() => {
    return store.getters["fashionStore/getSaturdayClosingTime"];
});
//

watch(getSaturdayClosingTime, (newValue) => {
    postForm.saturday_closing_time_store = newValue;
});
// saturday # end

// sunday # start
const getSundayOpeningTime = computed(() => {
    return store.getters["fashionStore/getSundayOpeningTime"];
});
//

watch(getSundayOpeningTime, (newValue) => {
    postForm.sunday_opening_time_store = newValue;
});

const getSundayClosingTime = computed(() => {
    return store.getters["fashionStore/getSundayClosingTime"];
});
//

watch(getSundayClosingTime, (newValue) => {
    postForm.sunday_closing_time_store = newValue;
});
// sunday # end
// watch store opening hours # end

// watch team opening hours # start
// monday # start
const getMondayOpeningTimeTeam = computed(() => {
    return store.getters["team/getMondayOpeningTime"];
});
//

watch(getMondayOpeningTimeTeam, (newValue) => {
    postForm.monday_opening_time_team = newValue;
});

const getMondayClosingTimeTeam = computed(() => {
    return store.getters["team/getMondayClosingTime"];
});
//

watch(getMondayClosingTimeTeam, (newValue) => {
    postForm.monday_closing_time_team = newValue;
});
// monday # end

// tuesday # start
const getTuesdayOpeningTimeTeam = computed(() => {
    return store.getters["team/getTuesdayOpeningTime"];
});
//

watch(getTuesdayOpeningTimeTeam, (newValue) => {
    postForm.tuesday_opening_time_team = newValue;
});

const getTuesdayClosingTimeTeam = computed(() => {
    return store.getters["team/getTuesdayClosingTime"];
});
//

watch(getTuesdayClosingTimeTeam, (newValue) => {
    postForm.tuesday_closing_time_team = newValue;
});
// tuesday # end

// wednesday # start
const getWednesdayOpeningTimeTeam = computed(() => {
    return store.getters["team/getWednesdayOpeningTime"];
});
//

watch(getWednesdayOpeningTimeTeam, (newValue) => {
    postForm.wednesday_opening_time_team = newValue;
});

const getWednesdayClosingTimeTeam = computed(() => {
    return store.getters["team/getWednesdayClosingTime"];
});
//

watch(getWednesdayClosingTimeTeam, (newValue) => {
    postForm.wednesday_closing_time_team = newValue;
});
// wednesday # end

// thursday # start
const getThursdayOpeningTimeTeam = computed(() => {
    return store.getters["team/getThursdayOpeningTime"];
});
//

watch(getThursdayOpeningTimeTeam, (newValue) => {
    postForm.thursday_opening_time_team = newValue;
});

const getThursdayClosingTimeTeam = computed(() => {
    return store.getters["team/getThursdayClosingTime"];
});
//

watch(getThursdayClosingTimeTeam, (newValue) => {
    postForm.thursday_closing_time_team = newValue;
});
// thursday # end

// friday # start
const getFridayOpeningTimeTeam = computed(() => {
    return store.getters["team/getFridayOpeningTime"];
});
//

watch(getFridayOpeningTimeTeam, (newValue) => {
    postForm.friday_opening_time_team = newValue;
});

const getFridayClosingTimeTeam = computed(() => {
    return store.getters["team/getFridayClosingTime"];
});
//

watch(getFridayClosingTimeTeam, (newValue) => {
    postForm.friday_closing_time_team = newValue;
});
// friday # end

// saturday # start
const getSaturdayOpeningTimeTeam = computed(() => {
    return store.getters["team/getSaturdayOpeningTime"];
});
//

watch(getSaturdayOpeningTimeTeam, (newValue) => {
    postForm.saturday_opening_time_team = newValue;
});

const getSaturdayClosingTimeTeam = computed(() => {
    return store.getters["team/getSaturdayClosingTime"];
});
//

watch(getSaturdayClosingTimeTeam, (newValue) => {
    postForm.saturday_closing_time_team = newValue;
});
// saturday # end

// sunday # start
const getSundayOpeningTimeTeam = computed(() => {
    return store.getters["team/getSundayOpeningTime"];
});
//

watch(getSundayOpeningTimeTeam, (newValue) => {
    postForm.sunday_opening_time_team = newValue;
});

const getSundayClosingTimeTeam = computed(() => {
    return store.getters["team/getSundayClosingTime"];
});
//

watch(getSundayClosingTimeTeam, (newValue) => {
    postForm.sunday_closing_time_team = newValue;
});
// sunday # end
// watch team opening hours # end

const createPost = () => {
    if (formType.value === "create") {
        postForm.post(route("team.stores.store"), {
            preserveScroll: true,
            onSuccess: () => {
                clearForm();
            },
            onError: () => {},
            onFinish: () => {},
        });
    }
    if (formType.value === "update") {
        postForm.post(route("team.stores.update", props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                submittedOnUpdate.value = false;
                clearPageBuilderOnSuccessUpdate();
            },
            onError: () => {},
            onFinish: () => {},
        });
    }
};

const handleClearForm = function () {
    // handle show modal for unique content
    modalShowClearForm.value = true;

    typeModal.value = "warning";
    gridColumnModal.value = 2;
    titleModal.value = `Clear the form?`;
    descriptionModal.value =
        "Are you certain that you want to clear the form? The form will be cleared.";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Clear form";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowClearForm.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowClearForm.value = false;
        clearForm();
    };
};
// end modal

const clearTags = ref(0);

// clear form
const clearForm = function () {
    postForm.title = "";
    // slug
    postForm.slug = "";
    postForm.brand_website_url = "";

    if (props.currentUserTeam && props.currentUserTeam.address) {
        postForm.address = props.currentUserTeam.address;
    } else {
        postForm.address = "";
    }

    if (props.currentUserTeam && props.currentUserTeam.contact_page_url) {
        postForm.contact_page_url = props.currentUserTeam.contact_page_url;
    } else {
        postForm.contact_page_url = "";
    }

    postForm.floor = "";
    isSlugEditable.value = false;
    slugValueTitle.value = "";
    slugValueCustom.value = "";
    postForm.content = "";
    postForm.published = true;
    postForm.team = props.currentUserTeam;
    postForm.user_id = props.user.id;

    postForm.tags = "";
    clearTags.value++;

    postForm.show_author = false;

    postForm.use_team_opening_hours = false;
    postForm.use_store_opening_hours = false;

    postForm.author = [];
    postForm.states = [];
    postForm.categories = [];
    postForm.cover_image = [];
    postForm.brand_logo = [];

    localStorage.removeItem(pathLocalStorage);
    localStorage.removeItem(pathPageBuilderLocalStorageCreate);
    store.commit("pageBuilderState/setComponents", []);
};

const addToFloor = function () {
    postForm.floor = Number(postForm.floor);
    if (isNaN(postForm.floor)) {
        postForm.floor = 0;
    }

    if (typeof postForm.floor !== "number") {
        postForm.floor = 0;
    }
    if (typeof postForm.floor === "number" && postForm.floor < 1) {
        postForm.floor = 0;
    }
    postForm.floor++;
};

const removeFromFloor = function () {
    postForm.floor = Number(postForm.floor);
    if (isNaN(postForm.floor)) {
        postForm.floor = 1;
    }

    if (typeof postForm.floor !== "number") {
        postForm.floor = 1;
    }

    if (typeof postForm.floor === "number" && postForm.floor < 1) {
        postForm.floor = 1;
    }
    postForm.floor--;
};

// Is form dirty? Returns true or false
const formIsDirty = computed(() => {
    return postForm.isDirty;
});

const storeDirtyFormInLocalStorage = function () {
    if (formIsDirty.value === true && formType.value === "create") {
        // page-builder
        // Convert the form data to a JSON string
        postForm.isSlugEditable = isSlugEditable.value;
        const formDataJson = JSON.stringify(postForm);
        // Save the form data to local storage using the form ID as the key
        localStorage.setItem(pathLocalStorage, formDataJson);
    }
};

const authorSorted = computed(() => {
    return postForm.author.sort((a, b) => {
        const firstNameA = a.first_name;
        const firstNameB = b.first_name;

        if (firstNameA < firstNameB) {
            return -1;
        } else if (firstNameA > firstNameB) {
            return 1;
        } else {
            return 0;
        }
    });
});

const statesSorted = computed(() => {
    return postForm.states.sort((a, b) => {
        const nameA = a.name;
        const nameB = b.name;

        if (nameA < nameB) {
            return -1;
        } else if (nameA > nameB) {
            return 1;
        } else {
            return 0;
        }
    });
});

const categoriesSorted = computed(() => {
    return postForm.categories.sort((a, b) => {
        const nameA = a.name;
        const nameB = b.name;

        if (nameA < nameB) {
            return -1;
        } else if (nameA > nameB) {
            return 1;
        } else {
            return 0;
        }
    });
});

// Builder # Start
const getComponents = computed(() => {
    return store.getters["pageBuilderState/getComponents"];
});

const openPageBuilder = ref(false);

// use designer model
const pageBuilderPrimaryHandler = ref(null);
const pageBuilderSecondaryHandler = ref(null);
const handleDraftForUpdate = async function () {
    store.commit("user/setIsLoading", true);

    if (formType.value === "update") {
    }
};

// use media library
const showPageBuilderModal = ref(false);
// modal content
const titlePageBuilder = ref("");
const descriptionPageBuilder = ref("");
const firstButtonPageBuilder = ref("");
const secondButtonPageBuilder = ref(null);
const thirdButtonPageBuilder = ref(null);
// set dynamic modal handle functions
const firstPageBuilderButtonFunction = ref(null);
const secondPageBuilderButtonFunction = ref(null);
const thirdPageBuilderButtonFunction = ref(null);

const handlePageBuilder = function () {
    showPageBuilderModal.value = true;
    titlePageBuilder.value = "Add Content";
    descriptionPageBuilder.value = null;
    firstButtonPageBuilder.value = "Close";
    secondButtonPageBuilder.value = "Something here";
    thirdButtonPageBuilder.value = null;
    // handle click
    firstPageBuilderButtonFunction.value = function () {
        console.log("Laravel Dynamic Modal first button – Close FN ran..");
        // showPageBuilderModal.value = false;
    };

    secondPageBuilderButtonFunction.value = function () {
        showPageBuilderModal.value = false;
    };
    // end modal
};

const pageBuilderStateStore = sharedPageBuilderStore;
const pageBuilderClass = new PageBuilderClass(pageBuilderStateStore);

// get unique post if needs to be updated
onBeforeMount(async () => {
    if (!props.post) {
        //
        // User is creating a new Resource
        // if (pageBuilder.areComponentsStoredInLocalStorage()) {
        //     postForm.content =
        //         Array.isArray(getComponents.value) &&
        //         getComponents.value
        //             .map((component) => {
        //                 return component.html_code;
        //             })
        //             .join("");
        // }

        // local storage for form
        if (localStorage.getItem(pathLocalStorage) === null) {
            if (props.currentUserTeam && props.currentUserTeam.address) {
                postForm.address = props.currentUserTeam.address;
            }
        }

        // local storage for form
        if (localStorage.getItem(pathLocalStorage) === null) {
            if (
                props.currentUserTeam &&
                props.currentUserTeam.contact_page_url
            ) {
                postForm.contact_page_url =
                    props.currentUserTeam.contact_page_url;
            }

            // monday opening
            postForm.monday_opening_time_team =
                props.currentUserTeam.monday_opening_time;

            // monday closing
            postForm.monday_closing_time_team =
                props.currentUserTeam.monday_closing_time;

            // tuesday opening
            postForm.tuesday_opening_time_team =
                props.currentUserTeam.tuesday_opening_time;

            // tuesday closing
            postForm.tuesday_closing_time_team =
                props.currentUserTeam.tuesday_closing_time;

            // wednesday opening
            postForm.wednesday_opening_time_team =
                props.currentUserTeam.wednesday_opening_time;

            // wednesday closing
            postForm.wednesday_closing_time_team =
                props.currentUserTeam.wednesday_closing_time;

            // thursday opening
            postForm.thursday_opening_time_team =
                props.currentUserTeam.thursday_opening_time;

            // thursday closing
            postForm.thursday_closing_time_team =
                props.currentUserTeam.thursday_closing_time;

            // friday opening
            postForm.friday_opening_time_team =
                props.currentUserTeam.friday_opening_time;

            // friday closing
            postForm.friday_closing_time_team =
                props.currentUserTeam.friday_closing_time;

            // saturday opening
            postForm.saturday_opening_time_team =
                props.currentUserTeam.saturday_opening_time;

            // saturday closing
            postForm.saturday_closing_time_team =
                props.currentUserTeam.saturday_closing_time;

            // sunday opening
            postForm.sunday_opening_time_team =
                props.currentUserTeam.sunday_opening_time;

            // sunday closing
            postForm.sunday_closing_time_team =
                props.currentUserTeam.sunday_closing_time;
        }
        // local storage for form
        if (localStorage.getItem(pathLocalStorage) !== null) {
            // Get the saved form data from local storage using the form ID as the key
            const formDataJson = localStorage.getItem(pathLocalStorage);
            let formLocalStorage = JSON.parse(formDataJson);

            isSlugEditable.value = formLocalStorage.isSlugEditable;

            if (formLocalStorage.isSlugEditable === false) {
                slugValueTitle.slug = formLocalStorage.title;
            }

            if (formLocalStorage.isSlugEditable === true) {
                slugValueCustom.value = formLocalStorage.slug;
            }

            postForm.title = formLocalStorage.title;
            postForm.brand_website_url = formLocalStorage.brand_website_url;

            if (!formLocalStorage.address) {
                if (props.currentUserTeam && props.currentUserTeam.address) {
                    postForm.address = props.currentUserTeam.address;
                }
            }

            if (!formLocalStorage.contact_page_url) {
                if (
                    props.currentUserTeam &&
                    props.currentUserTeam.contact_page_url
                ) {
                    postForm.contact_page_url =
                        props.currentUserTeam.contact_page_url;
                }
            }

            if (formLocalStorage.address) {
                postForm.address = formLocalStorage.address;
            }

            if (formLocalStorage.contact_page_url) {
                postForm.contact_page_url = formLocalStorage.contact_page_url;
            }

            postForm.floor = formLocalStorage.floor;
            postForm.published = formLocalStorage.published;
            postForm.show_author = formLocalStorage.show_author;

            // opening hours # start
            postForm.use_team_opening_hours =
                formLocalStorage.use_team_opening_hours;
            postForm.use_store_opening_hours =
                formLocalStorage.use_store_opening_hours;
            // opening hours # end

            // store logic opening hours # start

            postForm.monday_opening_time_store =
                formLocalStorage.monday_opening_time_store;
            postForm.monday_closing_time_store =
                formLocalStorage.monday_closing_time_store;

            postForm.tuesday_opening_time_store =
                formLocalStorage.tuesday_opening_time_store;
            postForm.tuesday_closing_time_store =
                formLocalStorage.tuesday_closing_time_store;

            postForm.wednesday_opening_time_store =
                formLocalStorage.wednesday_opening_time_store;
            postForm.wednesday_closing_time_store =
                formLocalStorage.wednesday_closing_time_store;

            postForm.thursday_opening_time_store =
                formLocalStorage.thursday_opening_time_store;
            postForm.thursday_closing_time_store =
                formLocalStorage.thursday_closing_time_store;

            postForm.friday_opening_time_store =
                formLocalStorage.friday_opening_time_store;
            postForm.friday_closing_time_store =
                formLocalStorage.friday_closing_time_store;

            postForm.saturday_opening_time_store =
                formLocalStorage.saturday_opening_time_store;
            postForm.saturday_closing_time_store =
                formLocalStorage.saturday_closing_time_store;

            postForm.sunday_opening_time_store =
                formLocalStorage.sunday_opening_time_store;
            postForm.sunday_closing_time_store =
                formLocalStorage.sunday_closing_time_store;

            // store logic opening hours # end

            // team opening hours team # start

            // monday opening
            if (formLocalStorage.monday_opening_time_team !== undefined) {
                postForm.monday_opening_time_team =
                    formLocalStorage.monday_opening_time_team;
            }

            // monday closing
            if (formLocalStorage.monday_closing_time_team !== undefined) {
                postForm.monday_closing_time_team =
                    formLocalStorage.monday_closing_time_team;
            }

            // tuesday opening
            if (formLocalStorage.tuesday_opening_time_team !== undefined) {
                postForm.tuesday_opening_time_team =
                    formLocalStorage.tuesday_opening_time_team;
            }

            // tuesday closing
            if (formLocalStorage.tuesday_closing_time_team !== undefined) {
                postForm.tuesday_closing_time_team =
                    formLocalStorage.tuesday_closing_time_team;
            }

            // wednesday opening
            if (formLocalStorage.wednesday_opening_time_team !== undefined) {
                postForm.wednesday_opening_time_team =
                    formLocalStorage.wednesday_opening_time_team;
            }

            // wednesday closing
            if (formLocalStorage.wednesday_closing_time_team !== undefined) {
                postForm.wednesday_closing_time_team =
                    formLocalStorage.wednesday_closing_time_team;
            }

            // thursday opening
            if (formLocalStorage.thursday_opening_time_team !== undefined) {
                postForm.thursday_opening_time_team =
                    formLocalStorage.thursday_opening_time_team;
            }

            // thursday closing
            if (formLocalStorage.thursday_closing_time_team !== undefined) {
                postForm.thursday_closing_time_team =
                    formLocalStorage.thursday_closing_time_team;
            }

            // friday opening
            if (formLocalStorage.friday_opening_time_team !== undefined) {
                postForm.friday_opening_time_team =
                    formLocalStorage.friday_opening_time_team;
            }
            // friday closing
            if (formLocalStorage.friday_closing_time_team !== undefined) {
                postForm.friday_closing_time_team =
                    formLocalStorage.friday_closing_time_team;
            }

            // saturday opening
            if (formLocalStorage.saturday_opening_time_team !== undefined) {
                postForm.saturday_opening_time_team =
                    formLocalStorage.saturday_opening_time_team;
            }
            // saturday closing
            if (formLocalStorage.saturday_closing_time_team !== undefined) {
                postForm.saturday_closing_time_team =
                    formLocalStorage.saturday_closing_time_team;
            }

            // sunday opening
            if (formLocalStorage.sunday_opening_time_team !== undefined) {
                postForm.sunday_opening_time_team =
                    formLocalStorage.sunday_opening_time_team;
            }

            // sunday closing
            if (formLocalStorage.sunday_closing_time_team !== undefined) {
                postForm.sunday_closing_time_team =
                    formLocalStorage.sunday_closing_time_team;
            }
            // team opening hours team # end

            //
            //
            //
            //
            postForm.tags = formLocalStorage.tags;

            // Authors
            if (
                formLocalStorage.author === undefined ||
                formLocalStorage.author === null
            ) {
                postForm.author = [];
            }
            if (
                formLocalStorage.author !== undefined ||
                formLocalStorage.author !== null
            ) {
                // Determine whether all elements in an array are null.
                const arrayContainsOnlyNull = formLocalStorage.author?.every(
                    (element) => {
                        return element === null;
                    }
                );

                if (arrayContainsOnlyNull === true) {
                    postForm.author = [];
                }
                if (arrayContainsOnlyNull === false) {
                    postForm.author = formLocalStorage.author;
                }
            }
            // Cover image
            if (
                formLocalStorage.cover_image === undefined ||
                formLocalStorage.cover_image === null
            ) {
                postForm.cover_image = [];
            }
            if (
                formLocalStorage.cover_image !== undefined ||
                formLocalStorage.cover_image !== null
            ) {
                const arrayContainsOnlyNull =
                    formLocalStorage.cover_image?.every((element) => {
                        return element === null;
                    });

                if (arrayContainsOnlyNull === true) {
                    postForm.cover_image = [];
                }
                if (arrayContainsOnlyNull === false) {
                    postForm.cover_image = formLocalStorage.cover_image;
                }
            }
            // Brand logo
            if (
                formLocalStorage.brand_logo === undefined ||
                formLocalStorage.brand_logo === null
            ) {
                postForm.brand_logo = [];
            }
            if (
                formLocalStorage.brand_logo !== undefined ||
                formLocalStorage.brand_logo !== null
            ) {
                const arrayContainsOnlyNull =
                    formLocalStorage.brand_logo?.every((element) => {
                        return element === null;
                    });

                if (arrayContainsOnlyNull === true) {
                    postForm.brand_logo = [];
                }
                if (arrayContainsOnlyNull === false) {
                    postForm.brand_logo = formLocalStorage.brand_logo;
                }
            }
            // states
            if (
                formLocalStorage.states === undefined ||
                formLocalStorage.states === null
            ) {
                postForm.states = [];
            }
            if (
                formLocalStorage.states !== undefined ||
                formLocalStorage.states !== null
            ) {
                // Determine whether all elements in an array are null.
                // Checks if each element is equal to null.
                // If every element in the array is indeed null, the function returns true,
                const arrayContainsOnlyNull = formLocalStorage.states?.every(
                    (element) => {
                        return element === null;
                    }
                );

                if (arrayContainsOnlyNull === true) {
                    postForm.states = [];
                }
                if (arrayContainsOnlyNull === false) {
                    postForm.states = formLocalStorage.states;
                }
            }
            // Categories
            if (
                formLocalStorage.categories === undefined ||
                formLocalStorage.categories === null
            ) {
                postForm.categories = [];
            }
            if (
                formLocalStorage.categories !== undefined ||
                formLocalStorage.categories !== null
            ) {
                // Determine whether all elements in an array are null.
                // Checks if each element is equal to null.
                // If every element in the array is indeed null, the function returns true,
                const arrayContainsOnlyNull =
                    formLocalStorage.categories?.every((element) => {
                        return element === null;
                    });

                if (arrayContainsOnlyNull === true) {
                    postForm.categories = [];
                }
                if (arrayContainsOnlyNull === false) {
                    postForm.categories = formLocalStorage.categories;
                }
            }
        }
    }

    // User is editing an existing Resource, rather than creating a new one from scratch.
    if (props.post) {
        formType.value = "update";

        // øøø
        console.log("props.post content eeeeeer", props.post.content);
        pageBuilderClass.loadExistingContent(props.post.content);

        // team opening hours team # start
        postForm.monday_opening_time_team =
            props.currentUserTeam.monday_opening_time;
        postForm.monday_closing_time_team =
            props.currentUserTeam.monday_closing_time;

        postForm.tuesday_opening_time_team =
            props.currentUserTeam.tuesday_opening_time;
        postForm.tuesday_closing_time_team =
            props.currentUserTeam.tuesday_closing_time;

        postForm.wednesday_opening_time_team =
            props.currentUserTeam.wednesday_opening_time;
        postForm.wednesday_closing_time_team =
            props.currentUserTeam.wednesday_closing_time;

        postForm.thursday_opening_time_team =
            props.currentUserTeam.thursday_opening_time;
        postForm.thursday_closing_time_team =
            props.currentUserTeam.thursday_closing_time;

        postForm.friday_opening_time_team =
            props.currentUserTeam.friday_opening_time;
        postForm.friday_closing_time_team =
            props.currentUserTeam.friday_closing_time;

        postForm.saturday_opening_time_team =
            props.currentUserTeam.saturday_opening_time;
        postForm.saturday_closing_time_team =
            props.currentUserTeam.saturday_closing_time;

        postForm.sunday_opening_time_team =
            props.currentUserTeam.sunday_opening_time;
        postForm.sunday_closing_time_team =
            props.currentUserTeam.sunday_closing_time;
        // team opening hours team # end

        // opening hours store # start

        postForm.monday_opening_time_store = props.post.monday_opening_time;
        postForm.monday_closing_time_store = props.post.monday_closing_time;

        postForm.tuesday_opening_time_store = props.post.tuesday_opening_time;
        postForm.tuesday_closing_time_store = props.post.tuesday_closing_time;

        postForm.wednesday_opening_time_store =
            props.post.wednesday_opening_time;
        postForm.wednesday_closing_time_store =
            props.post.wednesday_closing_time;

        postForm.thursday_opening_time_store = props.post.thursday_opening_time;
        postForm.thursday_closing_time_store = props.post.thursday_closing_time;

        postForm.friday_opening_time_store = props.post.friday_opening_time;
        postForm.friday_closing_time_store = props.post.friday_closing_time;

        postForm.saturday_opening_time_store = props.post.saturday_opening_time;
        postForm.saturday_closing_time_store = props.post.saturday_closing_time;

        postForm.sunday_opening_time_store = props.post.sunday_opening_time;
        postForm.sunday_closing_time_store = props.post.sunday_closing_time;
        // opening hours store # end

        postForm.title = props.post.title;
        postForm.brand_website_url = props.post.brand_website_url;
        postForm.address = props.post.address;
        postForm.contact_page_url = props.post.contact_page_url;
        postForm.floor = props.post.floor;
        // slug logic
        // slug is editable when editing an existing post
        isSlugEditable.value = true;
        slugValueCustom.value = props.post.slug;

        postForm.content = props.post.content;
        postForm.published = props.post.published === 1 ? true : false;
        postForm.show_author = props.post.show_author === 1 ? true : false;

        postForm.use_team_opening_hours =
            props.post.use_team_opening_hours === 1 ? true : false;
        //
        postForm.use_store_opening_hours =
            props.post.use_store_opening_hours === 1 ? true : false;

        postForm.tags = props.post.tags;

        // check if the post author is available and should be displayed
        if (props.post.show_author === 1 && props.postAuthor !== null) {
            // add the author to the postForm
            postForm.author = props.postAuthor;
        }

        postForm.author = props.postAuthor;
        postForm.states = props.states;
        postForm.categories = props.categories;
        postForm.cover_image = props.coverImages;
        postForm.brand_logo = props.brandLogos;
    }

    store.commit(
        "pageBuilderState/setLocalStorageItemName",
        pathPageBuilderLocalStorageCreate
    );

    store.commit(
        "pageBuilderState/setLocalStorageItemNameUpdate",
        pathPageBuilderLocalStorageUpdateDraft.value
    );
});

onMounted(() => {
    submittedOnUpdate.value = true;
});
</script>
<template>
    <div>
        <FormSection @submitted="handleCreatePost">
            <template #title> Listing details</template>
            <template #description> Create a new Listing. </template>
            <template #main>
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Listing name & description
                        </div>
                    </div>
                    <!-- post title start -->
                    <div class="myInputGroup">
                        <InputLabel for="title" value="Listing name" />
                        <TextInput
                            placeholder="Enter your title.."
                            id="title"
                            v-model="postForm.title"
                            type="text"
                            class="block w-full"
                            autocomplete="off"
                        />
                        <InputError :message="postForm.errors.title" />
                    </div>
                    <!-- post title end -->
                    <!-- post slug start -->
                    <div class="myInputGroup">
                        <div v-show="isSlugEditable === false">
                            <InputLabel for="slug" value="Slug" />
                            <div class="relative flex items-center">
                                <TextInput
                                    placeholder="Post slug"
                                    id="slug"
                                    v-model="slugValueTitle"
                                    type="text"
                                    class="block w-full myPrimaryInputReadonly"
                                    readonly
                                    autocomplete="off"
                                />
                                <div
                                    @click="handleOpenLock"
                                    class="cursor-pointer absolute inset-y-0 right-0 pr-1.5 flex items-center"
                                >
                                    <LockClosedIcon
                                        class="w-5 h-5 text-myPrimaryErrorColor"
                                    >
                                    </LockClosedIcon>
                                </div>
                            </div>
                        </div>
                        <div v-show="isSlugEditable === true">
                            <InputLabel for="slug" value="Slug" />
                            <div class="relative flex items-center">
                                <TextInput
                                    placeholder="Post slug"
                                    id="slug"
                                    v-model="slugValueCustom"
                                    type="text"
                                    class="block w-full"
                                    autocomplete="off"
                                />
                                <div
                                    @click="handleCloseLock"
                                    class="cursor-pointer absolute inset-y-0 right-0 pr-1.5 flex items-center"
                                >
                                    <LockOpenIcon
                                        class="w-5 h-5 text-myPrimaryLinkColor"
                                    >
                                    </LockOpenIcon>
                                </div>
                            </div>
                            <div class="myPrimaryTag italic">
                                Slug: {{ postForm.slug }}
                            </div>
                        </div>
                        <InputError :message="postForm.errors.slug" />
                    </div>
                    <!-- post slug end -->
                    <!-- post address and floor start -->
                    <div
                        class="md:flex items-center justify-center myPrimaryGap"
                    >
                        <div class="myInputGroup md:w-2/3">
                            <InputLabel for="address" value="Listing address" />
                            <input
                                placeholder="Enter store address.."
                                id="address"
                                v-model="postForm.address"
                                type="text"
                                class="block w-full myPrimaryInput"
                                autocomplete="off"
                            />
                            <p class="myPrimaryParagraph italic">
                                Example: Emaar Business Park 1
                            </p>
                            <InputError :message="postForm.errors.address" />
                        </div>

                        <div class="myInputGroup md:w-1/3">
                            <InputLabel
                                for="floor"
                                value="Floor of the Store in the Mall"
                            />
                            <!-- Input Number -->
                            <div class="myPrimaryInput p-0">
                                <div
                                    class="w-full flex gap-2 justify-between items-center"
                                >
                                    <input
                                        placeholder="Enter floor of the Store.."
                                        id="floor"
                                        v-model="postForm.floor"
                                        class="myPrimaryInputNoBorder mt-0"
                                        autocomplete="off"
                                    />
                                    <div class="flex items-center">
                                        <button
                                            @click="removeFromFloor"
                                            type="button"
                                            class="h-10 w-10 cursor-pointer rounded flex items-center justify-center hover:bg-gray-50 aspect-square focus-visible:ring-0"
                                        >
                                            <span
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                remove
                                            </span>
                                        </button>
                                        <button
                                            @click="addToFloor"
                                            type="button"
                                            class="h-10 w-10 cursor-pointer rounded flex items-center justify-center hover:bg-gray-50 aspect-square focus-visible:ring-0"
                                        >
                                            <span
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                add
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="myPrimaryParagraph italic">
                                Enter 0 for Ground Floor
                            </p>
                            <!-- End Input Number -->
                            <InputError :message="postForm.errors.floor" />
                        </div>
                    </div>
                    <!-- post address and floor end -->
                </div>

                <!-- Builder #start -->
                <div class="myInputsOrganization">
                    <div class="myPrimaryFormOrganizationHeader">
                        Manage Content
                    </div>
                    <!-- Add Content # start -->
                    <div
                        class="rounded-lg mt-4 border-2 border-dashed border-gray-300 p-8 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <button type="button" @click="handlePageBuilder">
                            <span
                                class="myMediumIcon material-symbols-outlined"
                            >
                                stacks
                            </span>
                        </button>

                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            Build your Store by adding Components
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Get started by adding components using the drag &
                            drop Page Builder.
                        </p>
                        <div class="mt-6">
                            <button
                                @click="handlePageBuilder"
                                type="button"
                                class="myPrimaryButton"
                            >
                                <span
                                    class="myMediumIcon material-symbols-outlined"
                                >
                                    stacks
                                </span>
                                Add content
                            </button>
                        </div>
                    </div>
                    <!-- Add Content # end -->
                    <InputError :message="postForm.errors.content" />
                </div>
                <!-- Builder #end -->
            </template>

            <template #sidebar>
                <!-- post status - start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Status
                        </div>
                        <p class="myPrimaryParagraph">
                            {{
                                postForm.published
                                    ? "Public and accessible for public viewing."
                                    : "Private and not accessible for public viewing."
                            }}
                        </p>
                    </div>
                    <div
                        class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                    >
                        <InputLabel
                            :value="
                                postForm.published ? 'Published' : 'Unpublished'
                            "
                            :class="{
                                'text-myPrimaryLinkColor': postForm.published,
                                'text-myPrimaryErrorColor': !postForm.published,
                            }"
                        />
                        <Switch
                            v-model="postForm.published"
                            :class="[
                                postForm.published
                                    ? 'bg-myPrimaryLinkColor'
                                    : 'bg-gray-200',
                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryLinkColor focus:ring-offset-2',
                            ]"
                        >
                            <span class="sr-only">Use setting</span>
                            <span
                                :class="[
                                    postForm.published
                                        ? 'translate-x-5'
                                        : 'translate-x-0',
                                    'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                ]"
                            >
                                <span
                                    :class="[
                                        postForm.published
                                            ? 'opacity-0 ease-out duration-100'
                                            : 'opacity-100 ease-in duration-200',
                                        'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                    ]"
                                    aria-hidden="true"
                                >
                                    <svg
                                        class="h-3 w-3 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 12 12"
                                    >
                                        <path
                                            d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </span>
                                <span
                                    :class="[
                                        postForm.published
                                            ? 'opacity-100 ease-in duration-200'
                                            : 'opacity-0 ease-out duration-100',
                                        'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                    ]"
                                    aria-hidden="true"
                                >
                                    <svg
                                        class="h-3 w-3 text-myPrimaryLinkColor"
                                        fill="currentColor"
                                        viewBox="0 0 12 12"
                                    >
                                        <path
                                            d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                                        />
                                    </svg>
                                </span>
                            </span>
                        </Switch>
                    </div>
                    <InputError :message="postForm.errors.published" />
                </div>
                <!-- post status - end -->

                <!-- post contact page url # start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Company or Mall website
                        </div>
                        <p class="myPrimaryParagraph text-xs italic">
                            Company or mall website updated with these changes.
                        </p>
                    </div>
                    <!-- post title start -->
                    <div class="myInputGroup">
                        <InputLabel
                            for="contact_page_url"
                            value="Url for mall or company"
                        />
                        <TextInput
                            placeholder="Company or Mall url.."
                            id="contact_page_url"
                            v-model="postForm.contact_page_url"
                            type="text"
                            class="block w-full"
                            autocomplete="off"
                        />
                        <p class="myPrimaryParagraph italic">
                            Example: www.thedubaimall.com
                        </p>
                        <InputError
                            :message="postForm.errors.contact_page_url"
                        />
                    </div>
                </div>
                <!-- post contact page url # end -->

                <!-- Check box Opening hours # start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Opening hours
                        </div>
                    </div>

                    <!-- Check box Team opening hours # start -->
                    <div class="myInputGroup">
                        <div class="relative flex items-center">
                            <div class="flex h-6 items-center">
                                <input
                                    id="use_team_opening_hours"
                                    name="use_team_opening_hours"
                                    v-model="postForm.use_team_opening_hours"
                                    type="checkbox"
                                    class="h-5 w-5 rounded border-gray-300 text-myPrimaryBrandColor focus:ring-myPrimaryBrandColor"
                                />
                            </div>
                            <div class="ml-3 min-w-0 flex-1 text-sm leading-6">
                                <label
                                    for="use_team_opening_hours"
                                    class="select-none font-medium text-gray-900"
                                    >Use Mall or Company Opening Hours
                                </label>
                            </div>
                        </div>
                        <p class="myPrimaryParagraph mt-4">
                            Recommended for malls. Set once and reuse when
                            creating new listings.
                        </p>

                        <p class="myPrimaryParagraph text-xs italic">
                            Company opening hours will be updated with these
                            changes.
                        </p>
                        <InputError
                            :message="postForm.errors.use_team_opening_hours"
                        />
                        <!-- Check box Team opening hours # end -->

                        <div class="border-t border-gray-200 pt-6"></div>

                        <!-- Check box Store Opening hours # start -->
                        <div class="relative flex items-center">
                            <div class="flex h-6 items-center">
                                <input
                                    id="use_store_opening_hours"
                                    name="use_store_opening_hours"
                                    v-model="postForm.use_store_opening_hours"
                                    type="checkbox"
                                    class="h-5 w-5 rounded border-gray-300 text-myPrimaryBrandColor focus:ring-myPrimaryBrandColor"
                                />
                            </div>
                            <div class="ml-3 min-w-0 flex-1 text-sm leading-6">
                                <label
                                    for="use_store_opening_hours"
                                    class="select-none font-medium text-gray-900"
                                >
                                    Use Store Opening Hours
                                </label>
                            </div>
                        </div>
                        <InputError
                            :message="postForm.errors.use_store_opening_hours"
                        />
                    </div>
                    <template
                        v-if="
                            postForm.use_team_opening_hours &&
                            postForm.use_store_opening_hours
                        "
                    >
                        <p
                            class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
                        >
                            Select only one option.
                        </p>
                    </template>
                    <!-- Check box Store Opening hours # end -->

                    <template
                        v-if="
                            postForm.use_store_opening_hours &&
                            !postForm.use_team_opening_hours
                        "
                    >
                        <div
                            class="border-2 border-red-100 rounded-lg px-2 py-2 divide-y divide-myPrimaryLightGrayColor flex flex-col gap-4"
                        >
                            <OpeningClosingHours
                                weekday="monday"
                                resourceId="store"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.monday_opening_time_store
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.monday_closing_time_store
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="tuesday"
                                resourceId="store"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.tuesday_opening_time_store
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.tuesday_closing_time_store
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="wednesday"
                                resourceId="store"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.wednesday_opening_time_store
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.wednesday_closing_time_store
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="thursday"
                                resourceId="store"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.thursday_opening_time_store
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.thursday_closing_time_store
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="friday"
                                resourceId="store"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.friday_opening_time_store
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.friday_closing_time_store
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="saturday"
                                resourceId="store"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.saturday_opening_time_store
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.saturday_closing_time_store
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="sunday"
                                resourceId="store"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.sunday_opening_time_store
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.sunday_closing_time_store
                                "
                            ></OpeningClosingHours>
                            <InputError
                                :message="postForm.errors.store_opening_hours"
                            />
                        </div>
                    </template>
                    <template
                        v-if="
                            postForm.use_team_opening_hours &&
                            !postForm.use_store_opening_hours
                        "
                    >
                        <div
                            class="border-2 border-red-100 rounded-lg px-2 py-2 divide-y divide-myPrimaryLightGrayColor flex flex-col gap-4"
                        >
                            <OpeningClosingHours
                                weekday="monday"
                                resourceId="team"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.monday_opening_time_team
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.monday_closing_time_team
                                "
                            ></OpeningClosingHours>

                            <OpeningClosingHours
                                weekday="tuesday"
                                resourceId="team"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.tuesday_opening_time_team
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.tuesday_closing_time_team
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="wednesday"
                                resourceId="team"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.wednesday_opening_time_team
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.wednesday_closing_time_team
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="thursday"
                                resourceId="team"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.thursday_opening_time_team
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.thursday_closing_time_team
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="friday"
                                resourceId="team"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.friday_opening_time_team
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.friday_closing_time_team
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="saturday"
                                resourceId="team"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.saturday_opening_time_team
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.saturday_closing_time_team
                                "
                            ></OpeningClosingHours>
                            <OpeningClosingHours
                                weekday="sunday"
                                resourceId="team"
                                :openingClockFromDatabaseOrLocalStorage="
                                    postForm.sunday_opening_time_team
                                "
                                :closingClockFromDatabaseOrLocalStorage="
                                    postForm.sunday_closing_time_team
                                "
                            ></OpeningClosingHours>
                            <InputError
                                :message="postForm.errors.team_opening_hours"
                            />
                        </div>
                    </template>
                </div>
                <!--  Check box Opening hours # end -->

                <!-- cover image - start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Cover image
                        </div>
                    </div>
                    <!-- select - start -->
                    <div
                        @click="handleUploadCoverImage"
                        class="myPrimaryFakeSelect"
                    >
                        <div class="relative flex items-center w-full py-0 p-0">
                            <span>
                                {{
                                    postForm.cover_image &&
                                    postForm.cover_image?.length === 0
                                        ? "Select Cover image"
                                        : "Add Additional Cover Images"
                                }}
                            </span>
                        </div>
                        <div
                            class="border-none rounded flex items-center justify-center h-full w-8"
                        >
                            <span class="material-symbols-outlined">
                                unfold_more
                            </span>
                        </div>
                    </div>
                    <!-- select - end -->

                    <div
                        v-if="
                            postForm.cover_image &&
                            postForm.cover_image?.length === 0
                        "
                        class="space-y-6 mt-2"
                    >
                        <p class="myPrimaryParagraph">No items selected.</p>
                    </div>

                    <div>
                        <p
                            v-if="
                                postForm.cover_image &&
                                postForm.cover_image?.length !== 0
                            "
                            class="py-4"
                        >
                            Added
                            {{
                                postForm.cover_image &&
                                postForm.cover_image?.length
                            }}
                            {{
                                postForm.cover_image &&
                                postForm.cover_image?.length === 1
                                    ? "Item"
                                    : "Items"
                            }}
                        </p>
                        <div
                            v-if="
                                postForm.cover_image &&
                                Array.isArray(postForm?.cover_image) &&
                                postForm.cover_image?.length !== 0
                            "
                            class="p-2 border border-myPrimaryLightGrayColor rounded-lg"
                        >
                            <div
                                class="min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll divide-y divide-gray-200 pr-2"
                            >
                                <div
                                    v-for="image in postForm.cover_image !==
                                        null && postForm.cover_image"
                                    :key="image?.id"
                                >
                                    <div
                                        class="flex justify-between items-center my-2 gap-4 myPrimaryTag w-max"
                                    >
                                        <div
                                            class="flex justify-left items-center gap-2"
                                        >
                                            <div class="flex-shrink-0">
                                                <img
                                                    @click="
                                                        handleUploadCoverImage
                                                    "
                                                    :src="`/storage/uploads/${image?.thumbnail_path}`"
                                                    alt="image"
                                                    class="myPrimarythumbnailInsertPreview"
                                                />
                                            </div>

                                            <button
                                                class="myPrimaryTag bg-myPrimaryLinkColor text-white break-keep"
                                                v-if="
                                                    image?.pivot?.primary &&
                                                    postForm.cover_image
                                                        .length > 1
                                                "
                                                type="button"
                                                @click="
                                                    removePrimaryImageBrandLogo(
                                                        image?.id
                                                    )
                                                "
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-2"
                                                >
                                                    <span> Primary </span>
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </div>
                                            </button>
                                            <button
                                                class="myPrimaryTag transition bg-white break-keep"
                                                v-if="
                                                    !image?.pivot?.primary &&
                                                    postForm.cover_image
                                                        ?.length > 1
                                                "
                                                type="button"
                                                @click="
                                                    setAsPrimaryImage(image?.id)
                                                "
                                            >
                                                <span> Set as Primary </span>
                                            </button>
                                        </div>

                                        <button
                                            type="button"
                                            @click="
                                                handleRemoveCoverImage(
                                                    image?.id
                                                )
                                            "
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                        >
                                            <span
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                delete
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="
                                    postForm.cover_image &&
                                    postForm.cover_image?.length >= 1
                                "
                                class="flex items-center justify-between border-t border-gray-200 pt-2 mt-1"
                            >
                                <p
                                    @click="handleUploadCoverImage"
                                    class="myPrimaryParagraph text-xs cursor-pointer font-medium"
                                >
                                    Add Additional Images
                                </p>
                                <button
                                    type="button"
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                    @click="handleUploadCoverImage"
                                >
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        add
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <InputError :message="postForm.errors.cover_image" />
                </div>
                <!-- cover image - end -->
                <!-- post states - start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">State</div>
                    </div>
                    <!-- select - start -->
                    <div @click="handleAddStates" class="myPrimaryFakeSelect">
                        <div class="relative flex items-center w-full py-0 p-0">
                            <span>
                                {{
                                    postForm.states &&
                                    postForm.states?.length === 0
                                        ? "Select State"
                                        : "Update State"
                                }}
                            </span>
                        </div>
                        <div
                            class="border-none rounded flex items-center justify-center h-full w-8"
                        >
                            <span class="material-symbols-outlined">
                                unfold_more
                            </span>
                        </div>
                    </div>
                    <!-- select - end -->

                    <div
                        v-if="postForm.states && postForm.states?.length === 0"
                        class="space-y-6 mt-2"
                    >
                        <p class="myPrimaryParagraph">No items selected.</p>
                    </div>

                    <div>
                        <p
                            v-if="
                                postForm.states && postForm.states?.length !== 0
                            "
                            class="py-4"
                        >
                            Added
                            {{ postForm.states && postForm.states?.length }}
                            {{
                                postForm.states && postForm.states?.length === 1
                                    ? "Item"
                                    : "Items"
                            }}
                        </p>

                        <div
                            v-if="
                                postForm.states && postForm.states?.length !== 0
                            "
                            class="p-2 min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200 rounded-lg"
                        >
                            <div
                                v-for="state in Array.isArray(statesSorted) &&
                                statesSorted"
                                :key="state?.id"
                            >
                                <div
                                    class="flex justify-between items-center my-2 gap-4 myPrimaryTag w-max"
                                >
                                    <div
                                        @click="handleAddStates"
                                        class="flex items-center gap-4 my-2 cursor-pointer font-medium"
                                    >
                                        <button
                                            type="button"
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                        >
                                            <span
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                location_on
                                            </span>
                                        </button>
                                        <div>
                                            {{ state?.name }}
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        @click="
                                            handleRemoveAttachedStates(
                                                state?.id
                                            )
                                        "
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                    >
                                        <span
                                            class="myMediumIcon material-symbols-outlined"
                                        >
                                            delete
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <InputError :message="postForm.errors.states" />
                </div>
                <!-- post states - end -->
                <!-- post categories - start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Categories
                        </div>
                    </div>
                    <!-- select - start -->
                    <div
                        @click="handleAddCategories"
                        class="myPrimaryFakeSelect"
                    >
                        <div class="relative flex items-center w-full py-0 p-0">
                            <span>
                                {{
                                    postForm.categories &&
                                    postForm.categories?.length === 0
                                        ? "Select Category"
                                        : "Update Category"
                                }}
                            </span>
                        </div>
                        <div
                            class="border-none rounded flex items-center justify-center h-full w-8"
                        >
                            <span class="material-symbols-outlined">
                                unfold_more
                            </span>
                        </div>
                    </div>
                    <!-- select - end -->

                    <div
                        v-if="
                            postForm.categories &&
                            postForm.categories?.length === 0
                        "
                        class="space-y-6 mt-2"
                    >
                        <p class="myPrimaryParagraph">No items selected.</p>
                    </div>

                    <div>
                        <p
                            v-if="
                                postForm.categories &&
                                postForm.categories?.length !== 0
                            "
                            class="py-4"
                        >
                            Added
                            {{
                                postForm.categories &&
                                postForm.categories?.length
                            }}
                            {{
                                postForm.categories &&
                                postForm.categories?.length === 1
                                    ? "Item"
                                    : "Items"
                            }}
                        </p>

                        <div
                            v-if="
                                postForm.categories &&
                                postForm.categories?.length !== 0
                            "
                            class="p-2 min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200 rounded-lg"
                        >
                            <div
                                v-for="category in Array.isArray(
                                    categoriesSorted
                                ) && categoriesSorted"
                                :key="category?.id"
                            >
                                <div
                                    class="flex justify-between items-center my-2 gap-4 myPrimaryTag w-max"
                                >
                                    <div
                                        @click="handleAddCategories"
                                        class="flex items-center gap-4 my-2 cursor-pointer font-medium"
                                    >
                                        <button
                                            type="button"
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                        >
                                            <span
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                interests
                                            </span>
                                        </button>
                                        <div>
                                            {{ category?.name }}
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        @click="
                                            handleRemoveAttachedCategory(
                                                category?.id
                                            )
                                        "
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                    >
                                        <span
                                            class="myMediumIcon material-symbols-outlined"
                                        >
                                            delete
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <InputError :message="postForm.errors.categories" />
                </div>
                <!-- post categories - end -->

                <!-- tags - start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">Tags</div>
                    </div>
                    <div class="myInputGroup">
                        <Tags
                            :clearTags="clearTags"
                            :tagsOnLoad="postForm.tags"
                            @handleTags="handleTags"
                        ></Tags>
                        <InputError :message="postForm.errors.tags" />
                    </div>
                </div>
                <!-- tags - end -->

                <!-- post brand website url # start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Brand website
                        </div>
                    </div>
                    <!-- post title start -->
                    <div class="myInputGroup">
                        <InputLabel
                            for="brand_website_url"
                            value="Url for brand"
                        />
                        <TextInput
                            placeholder="Brand url.."
                            id="brand_website_url"
                            v-model="postForm.brand_website_url"
                            type="text"
                            class="block w-full"
                            autocomplete="off"
                        />
                        <p class="myPrimaryParagraph italic">
                            Example: www.dior.com
                        </p>
                        <InputError
                            :message="postForm.errors.brand_website_url"
                        />
                    </div>
                </div>
                <!-- post brand website url # end -->

                <!-- Brand logos - start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Brand logo
                        </div>
                    </div>
                    <!-- select - start -->
                    <div
                        @click="handleUploadBrandLogo"
                        class="myPrimaryFakeSelect"
                    >
                        <div class="relative flex items-center w-full py-0 p-0">
                            <span>
                                {{
                                    postForm.brand_logo &&
                                    postForm.brand_logo?.length === 0
                                        ? "Select Logo"
                                        : "Add Additional Logos"
                                }}
                            </span>
                        </div>
                        <div
                            class="border-none rounded flex items-center justify-center h-full w-8"
                        >
                            <span class="material-symbols-outlined">
                                unfold_more
                            </span>
                        </div>
                    </div>
                    <!-- select - end -->

                    <div
                        v-if="
                            postForm.brand_logo &&
                            postForm.brand_logo?.length === 0
                        "
                        class="space-y-6 mt-2"
                    >
                        <p class="myPrimaryParagraph">No items selected.</p>
                    </div>

                    <div>
                        <p
                            v-if="
                                postForm.brand_logo &&
                                postForm.brand_logo?.length !== 0
                            "
                            class="py-4"
                        >
                            Added
                            {{
                                postForm.brand_logo &&
                                postForm.brand_logo?.length
                            }}
                            {{
                                postForm.brand_logo &&
                                postForm.brand_logo?.length === 1
                                    ? "Item"
                                    : "Items"
                            }}
                        </p>
                        <div
                            v-if="
                                postForm.brand_logo &&
                                Array.isArray(postForm?.brand_logo) &&
                                postForm.brand_logo?.length !== 0
                            "
                            class="p-2 border border-myPrimaryLightGrayColor rounded-lg"
                        >
                            <div
                                class="min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll divide-y divide-gray-200 pr-2"
                            >
                                <div
                                    v-for="image in postForm.brand_logo !==
                                        null && postForm.brand_logo"
                                    :key="image?.id"
                                >
                                    <div
                                        class="flex justify-between items-center my-2 gap-4 myPrimaryTag w-max"
                                    >
                                        <div
                                            class="flex justify-left items-center gap-2"
                                        >
                                            <div class="flex-shrink-0">
                                                <img
                                                    @click="
                                                        handleUploadBrandLogo
                                                    "
                                                    :src="`/storage/uploads/${image?.thumbnail_path}`"
                                                    alt="image"
                                                    class="myPrimarythumbnailInsertPreview"
                                                />
                                            </div>

                                            <button
                                                class="myPrimaryTag bg-myPrimaryLinkColor text-white break-keep"
                                                v-if="
                                                    image?.pivot?.primary &&
                                                    postForm.brand_logo.length >
                                                        1
                                                "
                                                type="button"
                                                @click="
                                                    removePrimaryImageBrandLogo(
                                                        image?.id
                                                    )
                                                "
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-2"
                                                >
                                                    <span> Primary </span>
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </div>
                                            </button>
                                            <button
                                                class="myPrimaryTag transition bg-white break-keep"
                                                v-if="
                                                    !image?.pivot?.primary &&
                                                    postForm.brand_logo
                                                        ?.length > 1
                                                "
                                                type="button"
                                                @click="
                                                    setAsPrimaryImageBrandLogo(
                                                        image?.id
                                                    )
                                                "
                                            >
                                                <span> Set as Primary </span>
                                            </button>
                                        </div>

                                        <button
                                            type="button"
                                            @click="
                                                handleRemoveBrandLogo(image?.id)
                                            "
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                        >
                                            <span
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                delete
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div
                                v-if="
                                    postForm.brand_logo &&
                                    postForm.brand_logo?.length >= 1
                                "
                                class="flex items-center justify-between border-t border-gray-200 pt-2 mt-1"
                            >
                                <p
                                    @click="handleUploadBrandLogo"
                                    class="myPrimaryParagraph text-xs cursor-pointer font-medium"
                                >
                                    Add Additional Images
                                </p>
                                <button
                                    type="button"
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                    @click="handleUploadBrandLogo"
                                >
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        add
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <InputError :message="postForm.errors.brand_logo" />
                </div>
                <!--Brand logos - end -->

                <!-- post author - start -->
                <div class="myInputsOrganization">
                    <div
                        class="myPrimaryFormOrganizationHeaderDescriptionSection"
                    >
                        <div class="myPrimaryFormOrganizationHeader">
                            Show Store Staff Members
                        </div>
                    </div>
                    <div
                        class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                    >
                        <InputLabel
                            :value="postForm.show_author ? 'Show' : 'Hide'"
                            :class="{
                                'text-myPrimaryLinkColor': postForm.show_author,
                                'text-myPrimaryErrorColor':
                                    !postForm.show_author,
                            }"
                        />
                        <Switch
                            v-model="postForm.show_author"
                            :class="[
                                postForm.show_author
                                    ? 'bg-myPrimaryLinkColor'
                                    : 'bg-gray-200',
                                'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryLinkColor focus:ring-offset-2',
                            ]"
                        >
                            <span class="sr-only">Use setting</span>
                            <span
                                :class="[
                                    postForm.show_author
                                        ? 'translate-x-5'
                                        : 'translate-x-0',
                                    'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                ]"
                            >
                                <span
                                    :class="[
                                        postForm.show_author
                                            ? 'opacity-0 ease-out duration-100'
                                            : 'opacity-100 ease-in duration-200',
                                        'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                    ]"
                                    aria-hidden="true"
                                >
                                    <svg
                                        class="h-3 w-3 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 12 12"
                                    >
                                        <path
                                            d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </span>
                                <span
                                    :class="[
                                        postForm.show_author
                                            ? 'opacity-100 ease-in duration-200'
                                            : 'opacity-0 ease-out duration-100',
                                        'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                    ]"
                                    aria-hidden="true"
                                >
                                    <svg
                                        class="h-3 w-3 text-myPrimaryLinkColor"
                                        fill="currentColor"
                                        viewBox="0 0 12 12"
                                    >
                                        <path
                                            d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                                        />
                                    </svg>
                                </span>
                            </span>
                        </Switch>
                    </div>
                    <InputError :message="postForm.errors.show_author" />

                    <!-- select - start -->
                    <div v-if="postForm.show_author === true">
                        <div
                            @click="handleAddAuthor"
                            class="myPrimaryFakeSelect"
                        >
                            <div
                                class="relative flex items-center w-full py-0 p-0"
                            >
                                <span>
                                    {{
                                        postForm.author &&
                                        postForm.author.length === 0
                                            ? "Select Staff Members"
                                            : "Update Staff Members"
                                    }}
                                </span>
                            </div>
                            <div
                                class="border-none rounded flex items-center justify-center h-full w-8"
                            >
                                <span class="material-symbols-outlined">
                                    unfold_more
                                </span>
                            </div>
                        </div>
                        <!-- select - end -->

                        <div
                            v-if="
                                postForm.author && postForm.author.length === 0
                            "
                            class="space-y-6 mt-2"
                        >
                            <p class="myPrimaryParagraph">
                                No people have been selected.
                            </p>
                        </div>

                        <div>
                            <p
                                v-if="
                                    postForm.author &&
                                    postForm.author.length !== 0
                                "
                                class="py-4"
                            >
                                Added
                                {{ postForm.author && postForm.author.length }}
                                {{
                                    postForm.author &&
                                    postForm.author.length === 1
                                        ? "person"
                                        : "people"
                                }}
                            </p>

                            <div
                                v-if="
                                    postForm.author &&
                                    postForm.author.length !== 0
                                "
                                class="p-2 min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200 rounded-lg"
                            >
                                <div
                                    v-for="user in Array.isArray(
                                        authorSorted
                                    ) && authorSorted"
                                    :key="user.id"
                                >
                                    <div
                                        class="flex justify-between items-center rounded"
                                    >
                                        <div
                                            @click="handleAddAuthor"
                                            class="flex items-center gap-2 my-4 cursor-pointer"
                                        >
                                            <!-- start photo -->
                                            <div
                                                class="flex-shrink-0"
                                                v-if="
                                                    user &&
                                                    user.profile_photo_path !==
                                                        null
                                                "
                                            >
                                                <img
                                                    class="object-cover h-12 w-12 rounded-full"
                                                    :src="`/storage/${user.profile_photo_path}`"
                                                    :alt="
                                                        user.first_name +
                                                        user.last_name
                                                    "
                                                />
                                            </div>

                                            <div
                                                v-if="
                                                    user &&
                                                    user.profile_photo_path ===
                                                        null
                                                "
                                                class="flex-shrink-0 myPrimaryParagraph h-12 w-12 gap-0.5 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                            >
                                                <span>
                                                    {{
                                                        user.first_name
                                                            .charAt(0)
                                                            .toUpperCase()
                                                    }}
                                                </span>
                                                <span>
                                                    {{
                                                        user.last_name
                                                            .charAt(0)
                                                            .toUpperCase()
                                                    }}
                                                </span>
                                            </div>

                                            <!-- end photo -->
                                            <span
                                                class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                            >
                                                <span class="font-medium">
                                                    {{ user.first_name }}
                                                    {{ user.last_name }}
                                                </span>
                                            </span>
                                        </div>
                                        <button
                                            type="button"
                                            @click="
                                                handleRemoveAttachedUser(
                                                    user.id
                                                )
                                            "
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                        >
                                            <span
                                                class="myMediumIcon material-symbols-outlined"
                                            >
                                                delete
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <InputError :message="postForm.errors.author" />
                    </div>
                </div>
                <!-- post author - end -->
            </template>

            <template #actions>
                <div class="flex myPrimaryGap justify-end items-center">
                    <template v-if="formType === 'create'">
                        <button
                            type="button"
                            @click="handleClearForm"
                            class="text-myPrimaryErrorColor italic text-xs"
                        >
                            Clear form
                        </button>
                    </template>
                    <button
                        type="button"
                        @click="handlePageBuilder"
                        class="mySecondaryButton"
                    >
                        Manage Content
                    </button>
                    <SubmitButton
                        :disabled="postForm.processing"
                        buttonText="Save"
                    >
                    </SubmitButton>
                </div>
                <div
                    class="flex justify-end mt-4"
                    v-if="Object.values(postForm.errors).length !== 0"
                >
                    <div
                        @click="showErrorNotifications = true"
                        class="w-fit py-1 flex items-center gap-2 rounded-md px-2 cursor-pointer italic"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4 text-myPrimaryErrorColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                        <p
                            class="myPrimaryParagraph text-xs text-myPrimaryErrorColor py-0 my-0"
                        >
                            Show
                            {{ Object.values(postForm.errors).length }}
                            {{
                                Object.values(postForm.errors).length === 1
                                    ? "error"
                                    : "errors"
                            }}
                        </p>
                    </div>
                </div>

                <DynamicModal
                    :maxWidth="'full'"
                    :show="showPageBuilderModal"
                    :type="typeModal"
                    :gridColumnAmount="gridColumnModal"
                    :title="titleModal"
                    :description="descriptionModal"
                    :firstButtonText="firstButtonPageBuilder"
                    :secondButtonText="secondButtonPageBuilder"
                    :thirdButtonText="thirdButtonPageBuilder"
                    @firstModalButtonFunction="firstPageBuilderButtonFunction"
                    @secondModalButtonFunction="secondPageBuilderButtonFunction"
                    @thirdModalButtonFunction="thirdPageBuilderButtonFunction"
                >
                    <header></header>
                    <main>
                        <PageBuilder PageBuilderLogo="/logo/logo.svg" />
                    </main>
                </DynamicModal>

                <DynamicModal
                    :show="modalShowClearForm"
                    :type="typeModal"
                    :gridColumnAmount="gridColumnModal"
                    :title="titleModal"
                    :description="descriptionModal"
                    :firstButtonText="firstButtonModal"
                    :secondButtonText="secondButtonModal"
                    :thirdButtonText="thirdButtonModal"
                    @firstModalButtonFunction="firstModalButtonFunction"
                    @secondModalButtonFunction="secondModalButtonFunction"
                    @thirdModalButtonFunction="thirdModalButtonFunction"
                >
                    <header></header>
                    <main></main>
                </DynamicModal>

                <MediaLibraryModal
                    :forUserNotTeam="false"
                    :user="user"
                    :team="postForm.team"
                    :open="showMediaLibraryModal"
                    :title="titleMedia"
                    :description="descriptionMedia"
                    :firstButtonText="firstButtonMedia"
                    :secondButtonText="secondButtonMedia"
                    :thirdButtonText="thirdButtonMedia"
                    @firstMediaButtonFunction="firstMediaButtonFunction"
                    @secondMediaButtonFunction="secondMediaButtonFunction"
                    @thirdMediaButtonFunction="thirdMediaButtonFunction"
                >
                </MediaLibraryModal>

                <SearchUsersOrItems
                    v-if="showSearchUserModal"
                    apiUrlRouteName="attach.user.index"
                    :existingItems="postForm.author"
                    vuexActionMethod="attachedUsersOrItems/fetchUsers"
                    vuexGetCurrentItems="attachedUsersOrItems/getCurrentUsers"
                    vuexGetCurrentAttachedItems="attachedUsersOrItems/getCurrentAttachedUsers"
                    vuexSetCurrentAttachedItems="attachedUsersOrItems/setCurrentAttachedUsers"
                    vuexSetRemoveAttachedItem="attachedUsersOrItems/setRemoveAttachedUser"
                    vuexSetCurrentAttachedItemsToEmptyArray="attachedUsersOrItems/setCurrentAttachedUsersToEmptyArray"
                    :user="user"
                    :team="postForm.team"
                    :title="titleModalSearchItems"
                    :description="descriptionModalSearchItems"
                    :firstButtonText="firstButtonModalSearchItems"
                    :secondButtonText="secondButtonModalSearchItems"
                    @firstModalButtonSearchItemsFunction="
                        firstModalButtonSearchItemsFunction
                    "
                    @secondModalButtonSearchItemsFunction="
                        secondModalButtonSearchItemsFunction
                    "
                    :displayIcon="false"
                    :show="showSearchUserModal"
                >
                </SearchUsersOrItems>

                <SearchUsersOrItems
                    v-if="showSearchStoreStatesModal"
                    apiUrlRouteName="attach.store.states.index"
                    :existingItems="postForm.states"
                    vuexActionMethod="attachedUsersOrItems/fetchStoreStates"
                    vuexGetCurrentItems="attachedUsersOrItems/getCurrentStoreStates"
                    vuexGetCurrentAttachedItems="attachedUsersOrItems/getCurrentAttachedStoreStates"
                    vuexSetCurrentAttachedItems="attachedUsersOrItems/setCurrentAttachedStoreStates"
                    vuexSetRemoveAttachedItem="attachedUsersOrItems/setRemoveAttachedStoreStates"
                    vuexSetCurrentAttachedItemsToEmptyArray="attachedUsersOrItems/setCurrentAttachedStoreStatesToEmptyArray"
                    :user="user"
                    :team="postForm.team"
                    :title="titleModalSearchItems"
                    :description="descriptionModalSearchItems"
                    :firstButtonText="firstButtonModalSearchItems"
                    :secondButtonText="secondButtonModalSearchItems"
                    @firstModalButtonSearchItemsFunction="
                        firstModalButtonSearchItemsFunction
                    "
                    @secondModalButtonSearchItemsFunction="
                        secondModalButtonSearchItemsFunction
                    "
                    :displayIcon="true"
                    icon="MapPinIcon"
                    :show="showSearchStoreStatesModal"
                >
                </SearchUsersOrItems>

                <SearchUsersOrItems
                    v-if="showSearchStoreCategoriesModal"
                    apiUrlRouteName="attach.store.categories.index"
                    :existingItems="postForm.categories"
                    vuexActionMethod="attachedUsersOrItems/fetchStoreCategories"
                    vuexGetCurrentItems="attachedUsersOrItems/getCurrentStoreCategories"
                    vuexGetCurrentAttachedItems="attachedUsersOrItems/getCurrentAttachedStoreCategories"
                    vuexSetCurrentAttachedItems="attachedUsersOrItems/setCurrentAttachedStoreCategories"
                    vuexSetRemoveAttachedItem="attachedUsersOrItems/setRemoveAttachedStoreCategories"
                    vuexSetCurrentAttachedItemsToEmptyArray="attachedUsersOrItems/setCurrentAttachedStoreCategoriesToEmptyArray"
                    :user="user"
                    :team="postForm.team"
                    :title="titleModalSearchItems"
                    :description="descriptionModalSearchItems"
                    :firstButtonText="firstButtonModalSearchItems"
                    :secondButtonText="secondButtonModalSearchItems"
                    @firstModalButtonSearchItemsFunction="
                        firstModalButtonSearchItemsFunction
                    "
                    @secondModalButtonSearchItemsFunction="
                        secondModalButtonSearchItemsFunction
                    "
                    :displayIcon="true"
                    icon="interests"
                    :show="showSearchStoreCategoriesModal"
                >
                </SearchUsersOrItems>

                <NotificationsFixedBottom
                    :listOfMessages="Object.values(postForm.errors)"
                    :show="showErrorNotifications"
                    @notificationsModalButton="notificationsModalButton"
                >
                    <div class="flex items-center justify-start gap-2">
                        <p class="myPrimaryParagraphError">
                            {{ Object.values(postForm.errors).length }}
                            {{
                                Object.values(postForm.errors).length === 1
                                    ? "error"
                                    : "errors"
                            }}
                        </p>
                    </div>
                </NotificationsFixedBottom>
            </template>
        </FormSection>
    </div>
</template>
