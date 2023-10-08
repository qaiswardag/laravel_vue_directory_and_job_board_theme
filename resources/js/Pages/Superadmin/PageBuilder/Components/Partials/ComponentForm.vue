<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { ref, computed, onBeforeMount, watch } from "vue";
import { Switch } from "@headlessui/vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import { useStore } from "vuex";
import SearchUsersOrItems from "@/Components/Search/SearchUsersOrItems.vue";
import { router } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";

import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import TextArea from "@/Components/Forms/TextArea.vue";

import {
    CheckIcon,
    ChevronUpDownIcon,
    Squares2X2Icon,
    SquaresPlusIcon,
    TrashIcon,
    XMarkIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    user: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
    post: {
        default: null,
        required: false,
    },
    categories: {
        default: null,
        required: false,
    },
    coverImages: {
        default: null,
        required: false,
    },
});

const modalShowClearForm = ref(false);
const modalShowSearchItemsModal = ref(false);

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

// // store
const store = useStore();

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});

const getCurrentAttachedComponentCategories = computed(() => {
    return store.getters[
        "attachedUsersOrItems/getCurrentAttachedComponentCategories"
    ];
});

const formType = ref("create");

const pathLocalStorage = `components-form-${
    props.currentUserTeam ? props.currentUserTeam.id : null
}`;

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

// modal content
const titleModalSearchItems = ref("");
const descriptionModalSearchItems = ref("");
const firstButtonModalSearchItems = ref("");
const secondButtonModalSearchItems = ref(null);
// set dynamic modal handle functions
const firstModalButtonSearchItemsFunction = ref(null);
const secondModalButtonSearchItemsFunction = ref(null);

const handleRemoveAttachedCategory = function (itemId) {
    // filter the array to exclude item with matching ID
    postForm.categories = postForm.categories.filter(
        (item) => item.id !== itemId
    );
};
const handleAddCategories = function () {
    // handle show modal for unique content
    modalShowSearchItemsModal.value = true;
    // set modal standards
    titleModalSearchItems.value = "Add Component Categories";
    descriptionModalSearchItems.value = "Add Component Categories";
    firstButtonModalSearchItems.value = "Close";
    secondButtonModalSearchItems.value = "Save";
    // handle click
    firstModalButtonSearchItemsFunction.value = function () {
        // handle show modal for unique content
        modalShowSearchItemsModal.value = false;
    };
    // handle click
    secondModalButtonSearchItemsFunction.value = function () {
        const currentAttachedPostCategories = [
            ...getCurrentAttachedComponentCategories.value,
        ];
        // Set post form author to the non-reactive copy
        postForm.categories = currentAttachedPostCategories;

        // handle show modal for unique content
        modalShowSearchItemsModal.value = false;
    };

    // end modal
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

const postForm = useForm({
    title: "",
    html_code: "",
    published: true,

    team: props.currentUserTeam,
    user_id: props.user.id,

    team: props.currentUserTeam,
    categories: [],
    cover_image: [],
});

const handleCreatePost = function () {
    // try to store post
    createPost();
};

const createPost = () => {
    if (formType.value === "create") {
        postForm.post(
            route("admin.components.component.store", props.currentUserTeam.id),
            {
                preserveScroll: true,
                onSuccess: () => {
                    clearForm();
                },
                onError: () => {},
                onFinish: () => {},
            }
        );
    }
    if (formType.value === "update") {
        postForm.post(
            route("admin.components.component.update", [
                props.post.id,
                props.currentUserTeam.id,
            ]),
            {
                preserveScroll: true,
                onSuccess: () => {},
                onError: () => {},
                onFinish: () => {},
            }
        );
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

// const clearTags = ref(0);

// clear form
const clearForm = function () {
    postForm.title = "";
    // slug
    postForm.html_code = "";
    postForm.published = true;
    postForm.team = props.currentUserTeam;
    postForm.user_id = props.user.id;

    postForm.categories = [];

    localStorage.removeItem(pathLocalStorage);
};

// is form dirty? returns true or false
const formIsDirty = computed(() => {
    return postForm.isDirty;
});

const storeDirtyFormInLocalStorage = function () {
    if (formIsDirty.value === true && formType.value === "create") {
        // Convert the form data to a JSON string
        const formDataJson = JSON.stringify(postForm);
        // Save the form data to local storage using the form ID as the key
        localStorage.setItem(pathLocalStorage, formDataJson);
    }
};
//
//
//
// Will be executed when the user switch current route
router.on = () => {
    storeDirtyFormInLocalStorage();
};
// This function will be executed when the user clicks refresh or closes the tab/window
window.addEventListener("beforeunload", function () {
    storeDirtyFormInLocalStorage();
});

// get unique post if needs to be updated
onBeforeMount(() => {
    // User is creating a new Resource from scratch, rather than editing an existing one
    // Check local storage
    if (!props.post) {
        if (localStorage.getItem(pathLocalStorage) !== null) {
            // Get the saved form data from local storage using the form ID as the key
            const formDataJson = localStorage.getItem(pathLocalStorage);
            let formLocalStorage = JSON.parse(formDataJson);

            postForm.title = formLocalStorage.title;
            postForm.html_code = formLocalStorage.html_code;
            postForm.published = formLocalStorage.published;
            //
            //
            //
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
                // Determine whether all elements in an array are null.
                // Checks if each element is equal to null.
                // If every element in the array is indeed null, the function returns true,
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
    //
    // User is editing an existing Resource, rather than creating a new one from scratch.
    if (props.post) {
        formType.value = "update";
        postForm.title = props.post.title;
        postForm.html_code = props.post.html_code;
        postForm.published = props.post.published === 1 ? true : false;

        postForm.categories = props.categories;
        postForm.cover_image = props.coverImages;
    }
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
</script>

<template>
    <FormSection @submitted="handleCreatePost">
        <template #title>Component details</template>
        <template #description> Create a new Component. </template>
        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Title & HTML
                    </div>
                    <p class="myPrimaryParagraph">Specify title & HTML.</p>
                </div>
                <!-- post title start -->
                <div class="myInputGroup">
                    <InputLabel for="title" value="Component title" />
                    <TextInput
                        placeholder="Enter title.."
                        id="title"
                        v-model="postForm.title"
                        type="text"
                        class="block w-full mt-1"
                        autocomplete="off"
                    />
                    <InputError :message="postForm.errors.title" />
                </div>
                <!-- post title end -->

                <!-- post content start -->
                <InputLabel for="title" value="Component HTML" />
                <div class="editor">
                    <pre class="lang-html"><code></code></pre>
                    <TextArea
                        autocorrect="off"
                        autocapitalize="off"
                        translate="no"
                        class="myPrimaryTextArea"
                        rows="20"
                        placeholder="HTML for component"
                        id="html_code"
                        v-model="postForm.html_code"
                        autocomplete="off"
                    ></TextArea>
                </div>
                <InputError :message="postForm.errors.html_code" />
                <!-- post content end -->
            </div>
        </template>

        <template #sidebar>
            <!-- post status - start -->
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">Status</div>
                    <p class="myPrimaryParagraph">Specify status.</p>
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
                                postForm.categories.length === 0
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
                        postForm.categories && postForm.categories.length === 0
                    "
                    class="space-y-6 mt-2"
                >
                    <p class="myPrimaryParagraph">No items selected.</p>
                </div>

                <div>
                    <p
                        v-if="
                            postForm.categories &&
                            postForm.categories.length !== 0
                        "
                        class="py-4"
                    >
                        Added
                        {{ postForm.categories && postForm.categories.length }}
                        {{
                            postForm.categories &&
                            postForm.categories.length === 1
                                ? "Item"
                                : "Items"
                        }}
                    </p>

                    <div
                        v-if="
                            postForm.categories &&
                            postForm.categories.length !== 0
                        "
                        class="p-2 min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200"
                    >
                        <div
                            v-for="category in Array.isArray(
                                categoriesSorted
                            ) && categoriesSorted"
                            :key="category.id"
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
                                        {{ category.name }}
                                    </p>
                                </div>

                                <button
                                    type="button"
                                    @click="
                                        handleRemoveAttachedCategory(
                                            category.id
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
                                                postForm.cover_image?.length > 1
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
        </template>

        <template #actions>
            <div class="flex myPrimaryGap justify-end items-center">
                <template v-if="formType === 'create'">
                    <button
                        type="button"
                        @click="handleClearForm"
                        class="myPrimaryDeleteButton"
                    >
                        Clear
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
                v-if="modalShowSearchItemsModal"
                apiUrlRouteName="attach.component.categories.index"
                :existingItems="postForm.categories"
                vuexActionMethod="attachedUsersOrItems/fetchComponentCategories"
                vuexGetCurrentItems="attachedUsersOrItems/getCurrentComponentCategories"
                vuexGetCurrentAttachedItems="attachedUsersOrItems/getCurrentAttachedComponentCategories"
                vuexSetCurrentAttachedItems="attachedUsersOrItems/setCurrentAttachedComponentCategories"
                vuexSetRemoveAttachedItem="attachedUsersOrItems/setRemoveAttachedComponentCategories"
                vuexSetCurrentAttachedItemsToEmptyArray="attachedUsersOrItems/setCurrentAttachedComponentCategoriesToEmptyArray"
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
                :show="modalShowSearchItemsModal"
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
