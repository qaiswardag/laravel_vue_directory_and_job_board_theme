<script setup>
import { computed, ref } from "vue";
import UploadImagesForm from "@/Components/Forms/UploadImagesForm.vue";
import MediaLibraryGalleryList from "@/Components/GalleryList/MediaLibraryGalleryList.vue";
import {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";

// new version
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import { useStore } from "vuex";

// store
const store = useStore();

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});

const getCurrentMedia = computed(() => {
    return store.getters["mediaLibrary/getCurrentMedia"];
});

const getCurrentPreviewImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentPreviewImage"];
});

const search_query = ref("");

const selected = ref("Upload");

const tabs = ref([
    {
        name: "Upload",
        current: true,
    },
    {
        name: "Media library",
        current: false,
    },
]);

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        required: true,
    },
    firstButtonText: {
        type: String,
    },
    secondButtonText: {
        type: String,
    },
    thirdButtonText: {
        type: String,
    },
    open: {
        required: true,
    },
});

const emit = defineEmits([
    "firstMediaButtonFunction",
    "secondMediaButtonFunction",
    "thirdMediaButtonFunction",
]);

// first button function
const firstButton = function () {
    emit("firstMediaButtonFunction");
};

// second button  function
const secondButton = function () {
    emit("secondMediaButtonFunction");
};

// third button function
const thirdButton = function () {
    emit("thirdMediaButtonFunction");
};
//
//
const changeSelectedMenuTab = function (clicked) {
    selected.value = clicked;
};
//
const uploadOnSuccess = function () {
    selected.value = "Media library";
};
//
//
// form
const formDeleteImage = useForm({
    image_id: null,
});
// form
const form = useForm({
    name: "",
    image_id: null,
});
//
//
const handleImageUpdate = function (imageId) {
    // search query
    search_query.value = getCurrentMedia.value?.fetchedMedia?.search_query;

    //  page = getCurrentMedia.value.fetchedMedia.current_clicked_page;
    let currentClickedPage =
        getCurrentMedia.value?.fetchedMedia?.current_clicked_page;

    // set image id
    form.image_id = imageId;

    form.post(route("media.update", [props.team.id]), {
        preserveScroll: true,
        onSuccess: () => {
            // reset form
            form.reset();
            // dispatch
            store.dispatch("mediaLibrary/loadImage", {
                mediaLibraryId: imageId,
                teamId: props.team.id,
            });

            //
            // dispatch
            store.dispatch("mediaLibrary/loadMedia", {
                teamId: props.team.id,
                page: currentClickedPage,
                search_query: search_query.value,
            });
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};

const handleDeleteImage = function (imageId) {
    // search query
    search_query.value = getCurrentMedia.value?.fetchedMedia?.search_query;
    // let page = getCurrentMedia.value.fetchedMedia.current_clicked_page;
    let currentClickedPage =
        getCurrentMedia.value?.fetchedMedia?.current_clicked_page;
    // set image id
    formDeleteImage.image_id = imageId;

    formDeleteImage.post(route("media.destroy", [props.team.id]), {
        preserveScroll: true,
        onSuccess: () => {
            // commit
            store.commit("mediaLibrary/setCurrentImage", null);
            // dispatch
            store.dispatch("mediaLibrary/loadMedia", {
                teamId: props.team.id,
                page: currentClickedPage,
                search_query: search_query.value,
            });
        },
        onError: (err) => {},
        onFinish: () => {},
    });
};
</script>

<template>
    <teleport to="body">
        <TransitionRoot :show="open" as="template">
            <Dialog
                as="div"
                class="fixed z-30 inset-0 overflow-y-auto sm:px-4 py-6"
                @close="firstButton"
            >
                <div
                    class="flex items-end justify-center pb-20 text-center sm:block sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        />
                    </TransitionChild>

                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span
                        aria-hidden="true"
                        class="hidden sm:inline-block sm:align-middle sm:h-screen"
                        >&#8203;</span
                    >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div
                            class="relative w-full min-h-[60rem] max-h-[60rem] inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-7xl sm:w-full sm:p-6"
                        >
                            <div
                                class="flex gap-2 justify-between items-center border-b border-gray-200 pb-2"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="tertiaryHeader my-0 py-0"
                                >
                                    {{ title }}
                                    {{ team.name }}
                                </DialogTitle>

                                <div class="flex-end">
                                    <span
                                        class="h-6 w-6 text-gray-700 self-center cursor-pointer"
                                        @click="firstButton"
                                        ><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-6 h-6"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center pb-2 mb-2">
                                <div class="flex-1">
                                    <!--content media library - start-->
                                    <div
                                        class="flex-1 flex md:flex-row myPrimaryGap flex-col items-stretch overflow-hidden mt-2"
                                    >
                                        <!-- Main content - start-->
                                        <main class="flex-1 relativ">
                                            <div class="py-4 max-w-7xl mx-auto">
                                                <!-- Tabs -->
                                                <div class="mt-2 sm:mt-2 mb-4">
                                                    <!-- Tabs Mobile -->
                                                    <div class="sm:hidden">
                                                        <label
                                                            for="tabs"
                                                            class="sr-only"
                                                            >Select a tab</label
                                                        >
                                                        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->

                                                        <select
                                                            v-model="selected"
                                                            id="tabs"
                                                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                                                        >
                                                            <option>
                                                                Upload
                                                            </option>
                                                            <option>
                                                                Media library
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div
                                                        class="hidden sm:block"
                                                    >
                                                        <div
                                                            class="flex items-center border border-gray-200 py-1 px-2 rounded"
                                                        >
                                                            <nav
                                                                class="flex-1 -mb-px flex space-x-6 xl:space-x-8"
                                                                aria-label="Tabs"
                                                            >
                                                                <div
                                                                    @click="
                                                                        changeSelectedMenuTab(
                                                                            tab.name
                                                                        )
                                                                    "
                                                                    v-for="tab in tabs"
                                                                    :key="
                                                                        tab.name
                                                                    "
                                                                    :aria-current="
                                                                        tab.current
                                                                            ? 'page'
                                                                            : undefined
                                                                    "
                                                                    class="py-2 px-3 my-1 text-xs rounded cursor-pointer"
                                                                    :class="[
                                                                        tab.name ===
                                                                        selected
                                                                            ? 'bg-myPrimaryBrandColor text-white'
                                                                            : 'text-gray-500 hover:text-gray-700 ',
                                                                        'whitespace-nowrap',
                                                                    ]"
                                                                >
                                                                    {{
                                                                        tab.name
                                                                    }}
                                                                </div>
                                                            </nav>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    v-if="selected === 'Upload'"
                                                >
                                                    <!-- image upload - start -->
                                                    <UploadImagesForm
                                                        :team="team"
                                                        :user="user"
                                                        @uploadOnSuccess="
                                                            uploadOnSuccess
                                                        "
                                                    ></UploadImagesForm>
                                                    <!-- image upload - end -->
                                                </div>
                                                <div
                                                    v-if="
                                                        selected ===
                                                        'Media library'
                                                    "
                                                >
                                                    <!-- image gallary - start -->
                                                    <MediaLibraryGalleryList
                                                        :team="team"
                                                    ></MediaLibraryGalleryList>
                                                    <!-- image gallary - end -->
                                                </div>
                                            </div>
                                        </main>
                                        <!-- Main content - end-->

                                        <!-- Details sidebar - media library start-->
                                        <aside
                                            v-if="selected === 'Media library'"
                                            aria-label="sidebar"
                                            class="md:w-72 relative min-h-[55rem] max-h-[55rem]"
                                        >
                                            <div
                                                class="md:w-72 md:min-h-[50rem] md:max-h-[50rem] min-h-[15rem] max-h-[15rem] overflow-y-scroll bg-white border border-gray-200 rounded"
                                            >
                                                <div
                                                    v-if="
                                                        (getCurrentImage &&
                                                            Object.keys(
                                                                getCurrentImage
                                                            ).length === 0) ||
                                                        getCurrentImage === null
                                                    "
                                                    class="pb-16 space-y-6 md:px-3 sm:px-2 pt-2"
                                                >
                                                    <p
                                                        class="myPrimaryParagraph text-xs p-2"
                                                    >
                                                        No image selected
                                                    </p>
                                                </div>
                                                <div
                                                    v-if="
                                                        getCurrentImage &&
                                                        getCurrentImage.isSuccess ===
                                                            false &&
                                                        getCurrentImage.isLoading ===
                                                            false &&
                                                        getCurrentImage.isError ===
                                                            true
                                                    "
                                                    class="myPrimaryParagraphError"
                                                >
                                                    {{ getCurrentImage.error }}
                                                </div>

                                                <!--  -->
                                                <div
                                                    v-if="
                                                        getCurrentImage &&
                                                        getCurrentImage.isLoading ===
                                                            true &&
                                                        getCurrentImage.isError ===
                                                            false
                                                    "
                                                    class="mx-auto block w-full rounded-sm object-cover object-center cursor-pointer hover:shadow-sm"
                                                >
                                                    <div
                                                        class="flex items-center justify-center pt-12"
                                                    >
                                                        <div
                                                            class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                                            role="status"
                                                        >
                                                            <span
                                                                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
                                                                >Loading...</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div
                                                    v-if="
                                                        getCurrentImage &&
                                                        getCurrentImage.currentImage &&
                                                        getCurrentImage
                                                            .currentImage
                                                            .mediaLibrary &&
                                                        getCurrentImage.isSuccess ===
                                                            true &&
                                                        getCurrentImage.isLoading ===
                                                            false &&
                                                        getCurrentImage.isError ===
                                                            false
                                                    "
                                                    class="pb-16 space-y-6"
                                                >
                                                    <a
                                                        :href="`/storage/uploads/${getCurrentImage.currentImage.mediaLibrary.path.toLowerCase()}`"
                                                        target="_blank"
                                                    >
                                                        <img
                                                            class="mx-auto block w-full rounded-sm object-cover object-center cursor-pointer hover:shadow-sm"
                                                            :src="`/storage/uploads/${getCurrentImage.currentImage.mediaLibrary.path}`"
                                                            alt="image"
                                                        />
                                                    </a>

                                                    <div
                                                        class="md:px-3 sm:px-2"
                                                    >
                                                        <div>
                                                            <h2
                                                                class="mySecondaryHeader py-2 break-words"
                                                            >
                                                                {{
                                                                    getCurrentImage
                                                                        .currentImage
                                                                        .mediaLibrary
                                                                        .name
                                                                        ? getCurrentImage
                                                                              .currentImage
                                                                              .mediaLibrary
                                                                              .name
                                                                        : "–"
                                                                }}
                                                            </h2>

                                                            <form
                                                                @submit.prevent="
                                                                    handleImageUpdate(
                                                                        getCurrentImage
                                                                            .currentImage
                                                                            .mediaLibrary
                                                                            .id
                                                                    )
                                                                "
                                                            >
                                                                <div
                                                                    class="myInputsOrganization mt-2 mb-8 pt-4 pr-0 pb-4 pl-0 border-b border-myPrimaryMediumGrayColor rounded-none"
                                                                >
                                                                    <InputLabel
                                                                        for="name"
                                                                        value="Image name"
                                                                    />
                                                                    <div
                                                                        class="flex gap-2 item-center flex-col"
                                                                    >
                                                                        <div
                                                                            class="flex gap-2 item-center"
                                                                        >
                                                                            <div
                                                                                class="mt-1 relative flex items-center w-full"
                                                                            >
                                                                                <input
                                                                                    placeholder="Image name.."
                                                                                    id="title"
                                                                                    v-model="
                                                                                        form.name
                                                                                    "
                                                                                    type="text"
                                                                                    autofocus
                                                                                    autocomplete="off"
                                                                                    class="myPrimaryInput"
                                                                                    @keydown.enter.tab.prevent="
                                                                                        handleImageUpdate(
                                                                                            getCurrentImage
                                                                                                .currentImage
                                                                                                .mediaLibrary
                                                                                                .id
                                                                                        )
                                                                                    "
                                                                                />

                                                                                <div
                                                                                    class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5"
                                                                                >
                                                                                    <kbd
                                                                                        class="inline-flex items-center border border-gray-200 rounded px-2 text-xs font-sans font-medium text-gray-400"
                                                                                    >
                                                                                        ⏎
                                                                                    </kbd>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p
                                                                            v-if="
                                                                                form.errors
                                                                            "
                                                                            class="myPrimaryInputError"
                                                                        >
                                                                            {{
                                                                                form
                                                                                    .errors
                                                                                    .name
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div>
                                                            <h3
                                                                class="font-medium text-gray-900"
                                                            >
                                                                Information
                                                            </h3>
                                                            <dl
                                                                class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200"
                                                            >
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-medium items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Dimensions
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .width
                                                                        }}
                                                                        x
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .height
                                                                        }}
                                                                    </dd>
                                                                </div>

                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-medium items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Size
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .size
                                                                        }}
                                                                        KB
                                                                    </dd>
                                                                </div>
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-medium items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Extension
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .extension
                                                                        }}
                                                                    </dd>
                                                                </div>
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-medium items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Image Id
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .mediaLibrary
                                                                                .id
                                                                        }}
                                                                    </dd>
                                                                </div>
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-medium items-center"
                                                                >
                                                                    <dt
                                                                        class="text-gray-500"
                                                                    >
                                                                        Uploaded
                                                                        by
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .uploadedBy
                                                                                .firstName
                                                                        }}
                                                                        {{
                                                                            getCurrentImage
                                                                                .currentImage
                                                                                .uploadedBy
                                                                                .lastName
                                                                        }}
                                                                    </dd>
                                                                </div>
                                                                <div
                                                                    class="py-3 flex justify-between text-sm font-medium items-center"
                                                                >
                                                                    <dt
                                                                        class="text-myErrorColor"
                                                                    >
                                                                        Delete
                                                                    </dt>
                                                                    <dd
                                                                        class="text-gray-900"
                                                                    >
                                                                        <form
                                                                            @submit.prevent="
                                                                                handleDeleteImage(
                                                                                    getCurrentImage
                                                                                        .currentImage
                                                                                        .mediaLibrary
                                                                                        .id
                                                                                )
                                                                            "
                                                                        >
                                                                            <SubmitButton
                                                                                :ButtonStyleDelete="
                                                                                    true
                                                                                "
                                                                                :TableStyle="
                                                                                    true
                                                                                "
                                                                                :disabled="
                                                                                    false
                                                                                "
                                                                                buttonText=""
                                                                                type="button"
                                                                                @firstButtonClick="
                                                                                    handleDeleteImage(
                                                                                        getCurrentImage
                                                                                            .currentImage
                                                                                            .mediaLibrary
                                                                                            .id
                                                                                    )
                                                                                "
                                                                            >
                                                                                <svg
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none"
                                                                                    viewBox="0 0 24 24"
                                                                                    stroke-width="1.5"
                                                                                    stroke="currentColor"
                                                                                    class="w-5 h-5"
                                                                                >
                                                                                    <path
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                                                                    />
                                                                                </svg>
                                                                            </SubmitButton>
                                                                        </form>
                                                                    </dd>
                                                                </div>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                v-if="
                                                    getCurrentImage &&
                                                    getCurrentImage.currentImage &&
                                                    getCurrentImage.currentImage
                                                        .mediaLibrary &&
                                                    getCurrentImage.isLoading ===
                                                        false &&
                                                    (getCurrentImage.isError ===
                                                        null ||
                                                        getCurrentImage.isError ===
                                                            false)
                                                "
                                                class="absolute bottom-0 right-0 left-0 px-2 my-2 sm:flex justify-center sm:gap-3 grid gap-4 sm:grid-flow-row-dense md:w-full md:float-right"
                                            >
                                                <div
                                                    v-if="firstButtonText"
                                                    class="w-full"
                                                >
                                                    <button
                                                        ref="firstButtonRef"
                                                        class="myPrimaryButton bg-gray-700 hover:bg-gray-800 text-white focus:ring-gray-700"
                                                        type="button"
                                                        @click="firstButton"
                                                    >
                                                        {{ firstButtonText }}
                                                    </button>
                                                </div>

                                                <div
                                                    v-if="secondButtonText"
                                                    class="w-full"
                                                >
                                                    <button
                                                        class="myPrimaryButton w-full"
                                                        type="button"
                                                        @click="secondButton"
                                                    >
                                                        {{ secondButtonText }}
                                                    </button>
                                                </div>

                                                <div
                                                    v-if="thirdButtonText"
                                                    class="w-full"
                                                >
                                                    <button
                                                        class="myPrimaryDeleteButton w-full"
                                                        type="button"
                                                        @click="thirdButton"
                                                    >
                                                        {{ thirdButtonText }}
                                                    </button>
                                                </div>
                                            </div>
                                        </aside>
                                        <!-- Details sidebar - media library end-->
                                        <!-- Details sidebar - upload start-->
                                        <aside
                                            v-if="selected === 'Upload'"
                                            aria-label="sidebar"
                                            class="md:w-72 md:min-h-[50rem] md:max-h-[50rem] min-h-[15rem] max-h-[15rem] overflow-y-scroll bg-white border border-gray-200 rounded"
                                        >
                                            <div
                                                v-if="
                                                    getCurrentPreviewImage ===
                                                    null
                                                "
                                                class="pb-16 space-y-6 md:px-3 sm:px-2 pt-2"
                                            >
                                                <p
                                                    class="myPrimaryParagraph text-xs p-2"
                                                >
                                                    No image selected
                                                </p>
                                            </div>
                                            <div
                                                v-if="
                                                    getCurrentPreviewImage !==
                                                    null
                                                "
                                                class="pb-16 space-y-6"
                                            >
                                                <img
                                                    :src="
                                                        getCurrentPreviewImage.preview_url
                                                    "
                                                    class="mx-auto block h-72 w-full rounded-sm object-cover object-center cursor-pointer hover:shadow-sm"
                                                    alt="image"
                                                />

                                                <div class="md:px-3 sm:px-2">
                                                    <div>
                                                        <h2
                                                            class="mySecondaryHeader py-2 break-words"
                                                        >
                                                            {{
                                                                getCurrentPreviewImage.file_name
                                                            }}
                                                        </h2>
                                                    </div>

                                                    <div>
                                                        <h3
                                                            class="font-medium text-gray-900"
                                                        >
                                                            Information
                                                        </h3>
                                                        <dl
                                                            class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200"
                                                        >
                                                            <div
                                                                class="py-3 flex justify-between text-sm font-medium items-center"
                                                            >
                                                                <dt
                                                                    class="text-gray-500"
                                                                >
                                                                    Size
                                                                </dt>
                                                                <dd
                                                                    class="text-gray-900"
                                                                >
                                                                    {{
                                                                        Number(
                                                                            getCurrentPreviewImage.file_size
                                                                        ).toFixed(
                                                                            0
                                                                        )
                                                                    }}
                                                                    KB
                                                                </dd>
                                                            </div>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                        </aside>
                                        <!-- Details sidebar end-->
                                    </div>
                                    <!--content media library - end-->
                                </div>
                            </div>

                            <div v-if="description">
                                <div
                                    v-html="description"
                                    class="text-sm text-gray-600 mt-4 mb-10"
                                ></div>
                                <header>
                                    <slot name="header"></slot>
                                </header>
                                <main>
                                    <slot></slot>
                                </main>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>
