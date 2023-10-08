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
import { router } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import PageBuilderModal from "@/Components/Modals/PageBuilderModal.vue";
import PageBuilderView from "@/Pages/PageBuilder/PageBuilder.vue";
import PageBuilder from "@/composables/PageBuilder";
import { delay } from "@/helpers/delay";

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
const isLoading = ref(false);

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
const removePrimaryImage = function (imageId) {
    postForm.cover_image = postForm.cover_image.map((image) => {
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
    // set modal standards
    titleModalSearchItems.value = "Add author";
    descriptionModalSearchItems.value = "Add store author";
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
    // set modal standards
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
    // set modal standards
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
    address: "",
    content: "",
    published: true,
    team: props.currentUserTeam,
    user_id: props.user.id,

    tags: "",
    show_author: false,
    author: [],
    states: [],
    categories: [],
    cover_image: [],
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
    // set modal standards
    typeModal.value = "warning";
    gridColumnModal.value = 2;
    titleModal.value = `Are you certain that you want to clear the form?`;
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
    postForm.address = "";
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
    postForm.author = [];
    postForm.states = [];
    postForm.categories = [];
    postForm.cover_image = [];

    localStorage.removeItem(pathLocalStorage);
    localStorage.removeItem(pathPageBuilderLocalStorageCreate);
    store.commit("pageBuilderState/setComponents", []);
};
const clearPageBuilderOnSuccessUpdate = function () {
    pageBuilder.removeItemComponentsLocalStorageUpdate();
    store.commit("pageBuilderState/setComponents", []);
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
// Will be executed when the user switch current route
router.on = async () => {
    storeDirtyFormInLocalStorage();

    isLoading.value = true;

    if (formType.value === "update") {
        if (
            submittedOnUpdate.value &&
            Array.isArray(getComponents.value) &&
            getComponents.value.length !== 0
        ) {
            await nextTick();
            pageBuilder.saveComponentsLocalStorageUpdate();
        }
    }

    // set open modal
    openDesignerModal.value = false;

    await delay();
    isLoading.value = false;
};

// Will be executed when the user clicks refresh or closes the tab/window
window.addEventListener("beforeunload", async function () {
    storeDirtyFormInLocalStorage();

    isLoading.value = true;

    if (formType.value === "update") {
        if (
            submittedOnUpdate.value &&
            Array.isArray(getComponents.value) &&
            getComponents.value.length !== 0
        ) {
            await nextTick();
            pageBuilder.saveComponentsLocalStorageUpdate();
        }
    }

    // set open modal
    openDesignerModal.value = false;

    await delay();
    isLoading.value = false;
});

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

const openDesignerModal = ref(false);

// use designer model
const firstDesignerModalButtonFunction = ref(null);
const secondDesignerModalButtonFunction = ref(null);
const handleDraftForUpdate = async function () {
    isLoading.value = true;
    if (formType.value === "update") {
        await nextTick();
        pageBuilder.areComponentsStoredInLocalStorageUpdate();
        await nextTick();
        pageBuilder.setEventListenersForElements();

        await delay();
        isLoading.value = false;
    }
};

const handlePageBuilder = function () {
    // set modal standards
    openDesignerModal.value = true;

    if (formType.value === "create") {
        store.commit("pageBuilderState/setComponents", []);

        // local storage for page builder
        if (pageBuilder.areComponentsStoredInLocalStorage()) {
            postForm.content =
                Array.isArray(getComponents.value) &&
                getComponents.value
                    .map((component) => {
                        return component.html_code;
                    })
                    .join("");
        }
    }

    if (formType.value === "update") {
        // store.commit("pageBuilderState/setComponents", []);
    }

    // handle click
    firstDesignerModalButtonFunction.value = async function () {
        isLoading.value = true;

        if (formType.value === "update") {
            await nextTick();
            pageBuilder.saveComponentsLocalStorageUpdate();
            await delay();
        }

        // set open modal
        openDesignerModal.value = false;
        isLoading.value = false;
    };

    // handle click
    secondDesignerModalButtonFunction.value = async function () {
        isLoading.value = true;

        // save to local storage if new resource
        if (formType.value === "create") {
            await nextTick();
            pageBuilder.saveComponentsLocalStorage();

            await nextTick();
            postForm.content =
                Array.isArray(getComponents.value) &&
                getComponents.value
                    .map((component) => {
                        return component.html_code;
                    })
                    .join("");
        }
        // save to local storage if update
        if (formType.value === "update") {
            await nextTick();
            pageBuilder.synchronizeDOMAndComponents();

            await nextTick();
            postForm.content =
                Array.isArray(getComponents.value) &&
                getComponents.value
                    .map((component) => {
                        return component.html_code;
                    })
                    .join("");
        }

        // set open modal
        openDesignerModal.value = false;

        await delay();
        isLoading.value = false;
    };

    // end modal
};
// Builder # End

// get unique post if needs to be updated
onBeforeMount(async () => {
    if (!props.post) {
        if (pageBuilder.areComponentsStoredInLocalStorage()) {
            postForm.content =
                Array.isArray(getComponents.value) &&
                getComponents.value
                    .map((component) => {
                        return component.html_code;
                    })
                    .join("");
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
            postForm.address = formLocalStorage.address;
            postForm.published = formLocalStorage.published;
            postForm.show_author = formLocalStorage.show_author;

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
                const arrayContainsOnlyNull = formLocalStorage.author.every(
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
                    formLocalStorage.cover_image.every((element) => {
                        return element === null;
                    });

                if (arrayContainsOnlyNull === true) {
                    postForm.cover_image = [];
                }
                if (arrayContainsOnlyNull === false) {
                    postForm.cover_image = formLocalStorage.cover_image;
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
                const arrayContainsOnlyNull = formLocalStorage.states.every(
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
                const arrayContainsOnlyNull = formLocalStorage.categories.every(
                    (element) => {
                        return element === null;
                    }
                );

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

        pathPageBuilderLocalStorageUpdateDraft.value = `page-builder-draft-update-store-${
            props.post.id ? props.post.id : null
        }-team-${props.currentUserTeam ? props.currentUserTeam.id : null}`;

        // Parse the HTML content using DOMParser
        const parser = new DOMParser();
        const doc = parser.parseFromString(props.post.content, "text/html");

        // Select all <section> elements with data-componentid attribute
        const sectionElements = doc.querySelectorAll(
            "section[data-componentid]"
        );

        const extractedSections = [];
        // Loop through the selected elements and extract outerHTML
        sectionElements.forEach((section) => {
            extractedSections.push({
                html_code: section.outerHTML,
                id: section.dataset.componentid,
            });
        });

        store.commit("pageBuilderState/setComponents", extractedSections);

        postForm.title = props.post.title;
        postForm.address = props.post.address;
        // slug logic
        // slug is editable when editing an existing post
        isSlugEditable.value = true;
        slugValueCustom.value = props.post.slug;

        postForm.content = props.post.content;
        postForm.published = props.post.published === 1 ? true : false;
        postForm.show_author = props.post.show_author === 1 ? true : false;

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
const pageBuilder = new PageBuilder(store);
</script>
<template>
    <PageBuilderModal
        :show="openDesignerModal"
        :updateOrCreate="formType"
        @firstDesignerModalButtonFunction="firstDesignerModalButtonFunction"
        @secondDesignerModalButtonFunction="secondDesignerModalButtonFunction"
        @handleDraftForUpdate="handleDraftForUpdate"
    >
        <PageBuilderView :user="user" :team="postForm.team"></PageBuilderView>
    </PageBuilderModal>

    <FormSection @submitted="handleCreatePost">
        <template #title> Store details</template>
        <template #description> Create a new Store. </template>
        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Title & description
                    </div>
                </div>
                <!-- post title start -->
                <div class="myInputGroup">
                    <InputLabel for="title" value="Your Post title" />
                    <TextInput
                        placeholder="Enter your title.."
                        id="title"
                        v-model="postForm.title"
                        type="text"
                        class="block w-full mt-1"
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
                                class="block w-full mt-1 myPrimaryInputReadonly"
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
                                class="block w-full mt-1"
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
                        <p class="myPrimaryTag italic">
                            Slug: {{ postForm.slug }}
                        </p>
                    </div>
                    <InputError :message="postForm.errors.slug" />
                </div>
                <!-- post slug end -->
                <!-- post address start -->
                <div class="myInputGroup">
                    <InputLabel for="address" value="Store address" />
                    <TextInput
                        placeholder="Enter store address.."
                        id="address"
                        v-model="postForm.address"
                        type="text"
                        class="block w-full mt-1"
                        autocomplete="off"
                    />
                    <InputError :message="postForm.errors.address" />
                </div>
                <!-- post address end -->
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
                    <FolderPlusIcon
                        @click="handlePageBuilder"
                        class="mx-auto h-12 w-12 text-gray-400 cursor-pointer"
                    ></FolderPlusIcon>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">
                        Add Components
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Get started by adding components.
                    </p>
                    <div class="mt-6">
                        <button
                            @click="handlePageBuilder"
                            type="button"
                            class="myPrimaryButton"
                        >
                            <FolderPlusIcon
                                class="-ml-0.5 mr-1.5 h-5 w-5"
                            ></FolderPlusIcon>
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
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
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

            <!-- cover image - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
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
                        <ChevronUpDownIcon class="w-4 h-4"></ChevronUpDownIcon>
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
                            postForm.cover_image && postForm.cover_image?.length
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
                        class="p-2 border border-myPrimaryLightGrayColor"
                    >
                        <div
                            class="min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll divide-y divide-gray-200 pr-2"
                        >
                            <div
                                v-for="image in postForm.cover_image !== null &&
                                postForm.cover_image"
                                :key="image?.id"
                            >
                                <div
                                    class="flex justify-between items-center my-2 gap-4 text-xs font-medium myPrimaryTag"
                                >
                                    <div
                                        class="flex justify-left items-center gap-2"
                                    >
                                        <img
                                            @click="handleUploadCoverImage"
                                            :src="`/storage/uploads/${image?.medium_path}`"
                                            alt="image"
                                            class="myPrimarythumbnailInsertPreview"
                                        />

                                        <button
                                            class="myPrimaryTag bg-myPrimaryLinkColor text-white"
                                            v-if="
                                                image?.pivot?.primary &&
                                                postForm.cover_image.length > 1
                                            "
                                            type="button"
                                            @click="
                                                removePrimaryImage(image?.id)
                                            "
                                        >
                                            <div
                                                class="flex items-center justify-center gap-2"
                                            >
                                                <span> Primary </span>
                                                <CheckIcon
                                                    class="w-3 h-3 stroke-2"
                                                ></CheckIcon>
                                            </div>
                                        </button>
                                        <button
                                            class="myPrimaryTag transition bg-white"
                                            v-if="
                                                !image?.pivot?.primary &&
                                                postForm.cover_image?.length > 1
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
                                            handleRemoveCoverImage(image?.id)
                                        "
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                    >
                                        <TrashIcon
                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                        ></TrashIcon>
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
                                class="myPrimaryParagraph text-xs italic cursor-pointer"
                            >
                                Add Additional Images
                            </p>
                            <button
                                type="button"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                @click="handleUploadCoverImage"
                            >
                                <PlusIcon
                                    class="shrink-0 w-4 h-4 m-2 stroke-2"
                                ></PlusIcon>
                            </button>
                        </div>
                    </div>
                </div>

                <InputError :message="postForm.errors.cover_image" />
            </div>
            <!-- cover image - end -->
            <!-- post states - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">State</div>
                </div>
                <!-- select - start -->
                <div @click="handleAddStates" class="myPrimaryFakeSelect">
                    <div class="relative flex items-center w-full py-0 p-0">
                        <span>
                            {{
                                postForm.states && postForm.states?.length === 0
                                    ? "Select State"
                                    : "Update State"
                            }}
                        </span>
                    </div>
                    <div
                        class="border-none rounded flex items-center justify-center h-full w-8"
                    >
                        <ChevronUpDownIcon class="w-4 h-4"></ChevronUpDownIcon>
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
                        v-if="postForm.states && postForm.states?.length !== 0"
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
                        v-if="postForm.states && postForm.states?.length !== 0"
                        class="p-2 min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                    >
                        <div
                            v-for="state in Array.isArray(statesSorted) &&
                            statesSorted"
                            :key="state?.id"
                        >
                            <div
                                class="flex justify-between items-center my-2 gap-4 text-xs font-medium myPrimaryTag"
                            >
                                <div
                                    @click="handleAddStates"
                                    class="flex items-center gap-4 my-2 cursor-pointer"
                                >
                                    <button
                                        type="button"
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                    >
                                        <MapPinIcon
                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                        ></MapPinIcon>
                                    </button>
                                    <p>
                                        {{ state?.name }}
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    @click="
                                        handleRemoveAttachedStates(state?.id)
                                    "
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                >
                                    <TrashIcon
                                        class="shrink-0 w-4 h-4 m-2 stroke-2"
                                    ></TrashIcon>
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
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Categories
                    </div>
                </div>
                <!-- select - start -->
                <div @click="handleAddCategories" class="myPrimaryFakeSelect">
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
                        <ChevronUpDownIcon class="w-4 h-4"></ChevronUpDownIcon>
                    </div>
                </div>
                <!-- select - end -->

                <div
                    v-if="
                        postForm.categories && postForm.categories?.length === 0
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
                        {{ postForm.categories && postForm.categories?.length }}
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
                        class="p-2 min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                    >
                        <div
                            v-for="category in Array.isArray(
                                categoriesSorted
                            ) && categoriesSorted"
                            :key="category?.id"
                        >
                            <div
                                class="flex justify-between items-center my-2 gap-4 text-xs font-medium myPrimaryTag"
                            >
                                <div
                                    @click="handleAddCategories"
                                    class="flex items-center gap-4 my-2 cursor-pointer"
                                >
                                    <button
                                        type="button"
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                    >
                                        <Squares2X2Icon
                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                        ></Squares2X2Icon>
                                    </button>
                                    <p>
                                        {{ category?.name }}
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    @click="
                                        handleRemoveAttachedCategory(
                                            category?.id
                                        )
                                    "
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                >
                                    <TrashIcon
                                        class="shrink-0 w-4 h-4 m-2 stroke-2"
                                    ></TrashIcon>
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
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
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
            <!-- post author - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Show Authors
                    </div>
                </div>
                <div
                    class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                >
                    <InputLabel
                        :value="postForm.show_author ? 'Show' : 'Hide'"
                        :class="{
                            'text-myPrimaryLinkColor': postForm.show_author,
                            'text-myPrimaryErrorColor': !postForm.show_author,
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
                    <div @click="handleAddAuthor" class="myPrimaryFakeSelect">
                        <div class="relative flex items-center w-full py-0 p-0">
                            <span>
                                {{
                                    postForm.author &&
                                    postForm.author.length === 0
                                        ? "Select Authors"
                                        : "Update Authors"
                                }}
                            </span>
                        </div>
                        <div
                            class="border-none rounded flex items-center justify-center h-full w-8"
                        >
                            <ChevronUpDownIcon
                                class="w-4 h-4"
                            ></ChevronUpDownIcon>
                        </div>
                    </div>
                    <!-- select - end -->

                    <div
                        v-if="postForm.author && postForm.author.length === 0"
                        class="space-y-6 mt-2"
                    >
                        <p class="myPrimaryParagraph">
                            No people have been selected.
                        </p>
                    </div>

                    <div>
                        <p
                            v-if="
                                postForm.author && postForm.author.length !== 0
                            "
                            class="py-4"
                        >
                            Added
                            {{ postForm.author && postForm.author.length }}
                            {{
                                postForm.author && postForm.author.length === 1
                                    ? "person"
                                    : "people"
                            }}
                        </p>

                        <div
                            v-if="
                                postForm.author && postForm.author.length !== 0
                            "
                            class="p-2 min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                        >
                            <div
                                v-for="user in Array.isArray(authorSorted) &&
                                authorSorted"
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
                                                user.profile_photo_path !== null
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
                                                user.profile_photo_path === null
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
                                            handleRemoveAttachedUser(user.id)
                                        "
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                    >
                                        <TrashIcon
                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                        ></TrashIcon>
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
                        class="myPrimaryDeleteButton"
                    >
                        Clear form
                    </button>
                </template>
                <SubmitButton :disabled="postForm.processing" buttonText="Save">
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
                        errors
                    </p>
                </div>
            </div>
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
                icon="Squares2X2Icon"
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
                        errors
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
</template>
