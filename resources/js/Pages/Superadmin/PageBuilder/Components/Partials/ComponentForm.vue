<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
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

const formType = ref("create");

const pathLocalStorage = `page-builder-form-${
    props.currentUserTeam ? props.currentUserTeam.reference_id : null
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
        postForm.cover_image_original =
            getCurrentImage.value.currentImage?.mediaLibrary?.path;
        postForm.cover_image_thumbnail =
            getCurrentImage.value.currentImage?.mediaLibrary?.thumbnail_path;
        postForm.cover_image_medium =
            getCurrentImage.value.currentImage?.mediaLibrary?.medium_path;
        postForm.cover_image_large =
            getCurrentImage.value.currentImage?.mediaLibrary?.large_path;

        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
const handleRemoveCoverImage = function () {
    postForm.cover_image_original = null;
    postForm.cover_image_thumbnail = null;
    postForm.cover_image_medium = null;
    postForm.cover_image_large = null;
};

// const modalShowSearchItemsModal = ref(false);

// modal content
const titleModalSearchItems = ref("");
const descriptionModalSearchItems = ref("");
const firstButtonModalSearchItems = ref("");
const secondButtonModalSearchItems = ref(null);
// set dynamic modal handle functions
const firstModalButtonSearchItemsFunction = ref(null);
const secondModalButtonSearchItemsFunction = ref(null);

const handleAddCategory = function () {
    modalShowSearchItemsModal.value = true;

    // handle show modal for unique content
    // set modal standards
    titleModalSearchItems.value = "Add author";
    descriptionModalSearchItems.value = "Add Job author";
    firstButtonModalSearchItems.value = "Close";
    secondButtonModalSearchItems.value = "Save";
    // handle click
    firstModalButtonSearchItemsFunction.value = function () {
        // handle show modal for unique content
        modalShowSearchItemsModal.value = false;
    };
    // handle click
    secondModalButtonSearchItemsFunction.value = function () {
        // reasons why using the spread operator to create a non-reactive copy of an
        // array is generally considered better than using JSON.stringify
        // and JSON.parse to achieve the same result:

        // 1. Performance:
        // The spread operator is generally faster than using JSON.stringify and JSON.parse
        // to create a non-reactive copy of an array.
        // This is because the spread operator is a built-in JavaScript feature,
        // whereas JSON.stringify and JSON.parse require parsing and serializing data,
        // which can be computationally expensive for large arrays.

        // 2. Maintainability:
        // Using the spread operator to create a non-reactive copy of an array is more
        // maintainable than using JSON.stringify and JSON.parse because it is more
        // concise and easier to read. The spread operator clearly indicates that a new array
        // is being created, whereas the JSON.stringify and JSON.parse approach requires
        // multiple function calls and string manipulations,
        // which can be harder to read and understand.

        // 3. Compatibility: The spread operator is a standard JavaScript feature that is
        // supported by all modern browsers and Node.js, whereas JSON.stringify and JSON.parse
        // may not be available in older browsers or
        // environments that do not support ECMAScript 5.
        const currentAttachedUsers = [...getCurrentAttachedUsers.value];
        // Set post form author to the non-reactive copy
        postForm.author = currentAttachedUsers;

        // handle show modal for unique content
        modalShowSearchItemsModal.value = false;
    };

    // end modal
};

const filteredUsers = ref([]);

const handleRemoveAttachedCategory = function (categoryId) {
    // filter the array to exclude user with matching ID
    postForm.category = postForm.category.filter(
        (user) => category.id !== categoryId
    );
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

    cover_image_original: "",
    cover_image_thumbnail: "",
    cover_image_medium: "",
    cover_image_large: "",
    categories: [],
});

const handleCreatePost = function () {
    // try to store post
    createPost();
};

const createPost = () => {
    if (formType.value === "create") {
        postForm.post(route("team.jobs.store"), {
            preserveScroll: true,
            onSuccess: () => {
                clearForm();
            },
            onError: () => {},
            onFinish: () => {},
        });
    }
    if (formType.value === "update") {
        postForm.post(route("team.jobs.update", props.post.id), {
            preserveScroll: true,
            onSuccess: () => {},
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

// const clearTags = ref(0);

// clear form
const clearForm = function () {
    postForm.title = "";
    // slug
    postForm.html_code = "";
    postForm.published = true;
    postForm.team = props.currentUserTeam;

    postForm.cover_image_original = "";
    postForm.cover_image_thumbnail = "";
    postForm.cover_image_medium = "";
    postForm.cover_image_large = "";

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

// // get unique post if needs to be updated
onBeforeMount(() => {
    // User is creating a new Resource from scratch, rather than editing an existing one
    // Check local storage
    if (props.post === null) {
        if (localStorage.getItem(pathLocalStorage) !== null) {
            // Get the saved form data from local storage using the form ID as the key
            const formDataJson = localStorage.getItem(pathLocalStorage);
            let formLocalStorage = JSON.parse(formDataJson);

            postForm.title = formLocalStorage.title;
            postForm.html_code = formLocalStorage.html_code;
            postForm.published = formLocalStorage.published;

            postForm.cover_image_original =
                formLocalStorage.cover_image_original;
            postForm.cover_image_thumbnail =
                formLocalStorage.cover_image_thumbnail;
            postForm.cover_image_medium = formLocalStorage.cover_image_medium;
            postForm.cover_image_large = formLocalStorage.cover_image_large;
        }
    }
    // User is editing an existing Resource, rather than creating a new one from scratch.
    if (props.post !== null) {
        formType.value = "update";
        postForm.title = props.post.title;
        postForm.html_code = props.post.html_code;
        postForm.published = props.post.published === 1 ? true : false;
        postForm.cover_image_original = props.post.cover_image_original;
        postForm.cover_image_thumbnail = props.post.cover_image_thumbnail;
        postForm.cover_image_medium = props.post.cover_image_medium;
        postForm.cover_image_large = props.post.cover_image_large;

        if (props.categories !== null) {
            postForm.categories = props.categories;
        }
    }
});
</script>

<template>
    <p class="my-6">currentUserTeam is: {{ currentUserTeam }}</p>
    <p class="my-6">component when edit is: {{ post }}</p>
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
                <div
                    class="mt-2 flex items-center justify-between border-t border-myPrimaryLightGrayColor pt-4"
                >
                    <button
                        @click="handleAddCategory"
                        type="button"
                        class="myPrimaryButton gap-2 items-center"
                    >
                        <SquaresPlusIcon class="w-4 h-4"></SquaresPlusIcon>
                        Add Category
                    </button>
                </div>

                <div
                    class="p-2 mt-4"
                    :class="
                        postForm.categories && postForm.categories.length === 0
                            ? 'bg-white'
                            : 'bg-white'
                    "
                >
                    <p
                        v-if="
                            postForm.categories &&
                            postForm.categories.length !== 0
                        "
                        class="myPrimaryParagraph italic text-xs py-4"
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
                        class="p-2 min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll border border-myPrimaryLightGrayColor divide-y divide-gray-200 bg-red-100 hover:bg-pink-200"
                    >
                        <div
                            v-for="category in postForm.categories"
                            :key="category.id"
                            class="hover:bg-gray-50 px-2 bg-white"
                        >
                            <div
                                class="flex justify-between items-center rounded my-2 gap-4 text-xs font-medium"
                            >
                                <div class="flex items-center gap-4 my-2">
                                    <div
                                        class="w-10 h-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                                    >
                                        <Squares2X2Icon
                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                        ></Squares2X2Icon>
                                    </div>
                                    <p>
                                        {{ category.name }}
                                    </p>
                                </div>
                                <div
                                    @click="
                                        handleRemoveAttachedCategory(
                                            category.id
                                        )
                                    "
                                >
                                    <TrashIcon
                                        class="w-4 h-4 text-myPrimaryErrorColor stroke-2 cursor-pointer"
                                    ></TrashIcon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-if="
                        postForm.categories && postForm.categories.length === 0
                    "
                    class="space-y-6"
                >
                    <p class="myPrimaryParagraph">No category selected.</p>
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
                    <p class="myPrimaryParagraph">
                        Uplaod or select a Component cover image.
                    </p>
                </div>

                <img
                    v-if="
                        postForm.cover_image_medium &&
                        postForm.cover_image_medium.length !== 0
                    "
                    @click="handleUploadCoverImage"
                    class="myPrimarythumbnailInsertPreview"
                    alt="cover image"
                    :src="`/storage/uploads/${postForm.cover_image_medium}`"
                />

                <div
                    class="myInputGroup flex items-center justify-between border-t border-myPrimaryLightGrayColor pt-4"
                >
                    <button
                        @click="handleUploadCoverImage"
                        type="button"
                        class="myPrimaryButton gap-2 items-center"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                            />
                        </svg>

                        Cover image
                    </button>

                    <div v-if="postForm && postForm.cover_image_medium">
                        <svg
                            @click="handleRemoveCoverImage"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5 text-myPrimaryErrorColor cursor-pointer"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                            />
                        </svg>
                    </div>
                </div>
                <InputError :message="postForm.errors.cover_image_original" />
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
