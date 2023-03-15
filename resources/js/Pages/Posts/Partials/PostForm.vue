<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import { onMounted, ref, computed, watch } from "vue";
import { Switch } from "@headlessui/vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import Tags from "@/Components/Forms/Tags.vue";
import MediaLibraryModal from "@/Components/Modals/MediaLibraryModal.vue";
import { useStore } from "vuex";
import slugify from "slugify";
import config from "@/utils/config";

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
    XMarkIcon,
} from "@heroicons/vue/20/solid";

// store
const store = useStore();

const getCurrentImage = computed(() => {
    return store.getters["mediaLibrary/getCurrentImage"];
});

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
        createPostForm.thumbnail =
            getCurrentImage.value.currentImage.mediaLibrary.path;
        // handle show media library modal
        showMediaLibraryModal.value = false;
    };
    // end modal
};
const handleRemoveCoverImage = function () {
    createPostForm.thumbnail = null;
    // end modal
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

const props = defineProps({
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Posts",
        route: {
            name: "overview.posts.index",
            parameters: [props.currentUserTeam],
        },
    },
    { label: "Add Post" },
];

// start Quill Editor
// define options
const globalOptions = {
    // debug: "info",
    modules: {
        toolbar: ["bold", "italic", "underline"],
    },
    placeholder: "Compose an epic...",
    readOnly: false,
    theme: "snow",

    //
    //
    toolbar: [
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        ["bold", "italic", "underline"], // toggled buttons

        [{ list: "ordered" }, { list: "bullet" }],

        [{ align: [] }],

        ["clean"], // remove formatting button
    ],

    theme: "snow",
    placeholder: "Description",
    readOnly: false,
};
// end Quill Editor

// slug logic
const slugIsOpen = ref(false);

const createPostForm = useForm({
    title: "",
    slug: "",
    content: "",
    published: true,
    team: props.currentUserTeam,
    user_id: props.user.id,
    thumbnail: "",
    tags: "",
});

const productSlugLock = ref(""); // some_string

// if you prefer something other than '-' as separator
console.log(slugify("some string", "_"));

createPostForm.slug = computed(() => {
    if (slugIsOpen.value === false) {
        return slugify(createPostForm.title, config.slugifyOptions);
    }
    if (slugIsOpen.value === true) {
        return slugify(productSlugLock.value, config.slugifyOptions);

        return productSlugLock.value;
    }
});

const handleCloseLock = function () {
    slugIsOpen.value = false;
};

const handleOpenLock = function () {
    slugIsOpen.value = true;
};

const firstTagsButton = function (tags) {
    createPostForm.tags = tags;
};
const handleCreatePost = function () {
    // try to store post
    createPost();
};

const createPost = () => {
    createPostForm.post(route("overview.posts.store"), {
        errorBag: "createPost",
        preserveScroll: true,
        onSuccess: () => {},
        onError: () => {},
        onFinish: () => {},
    });
};
</script>

<template>
    <LoggedInLayout title="Create Post">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Create a New Post</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <div>
            <FormSection @submitted="handleCreatePost">
                <template #title> Post details </template>
                <template #description> Create a new Post. </template>

                <template #main>
                    <div class="myInputsOrganization">
                        <div
                            class="myPrimaryFormOrganizationHeaderDescriptionSection"
                        >
                            <div class="myPrimaryFormOrganizationHeader">
                                Title & description
                            </div>
                            <p class="myPrimaryParagraph">
                                Specify title & description.
                            </p>
                            <p class="my-12">form er: {{ createPostForm }}</p>
                        </div>
                        <!-- post title start -->
                        <div class="myInputGroup">
                            <InputLabel for="title" value="Your Post title" />
                            <TextInput
                                placeholder="Enter your title.."
                                id="title"
                                v-model="createPostForm.title"
                                type="text"
                                class="block w-full mt-1"
                                autofocus
                                autocomplete="off"
                            />
                            <InputError
                                :message="createPostForm.errors.title"
                            />
                        </div>
                        <!-- post title end -->
                        <!-- post slug start -->
                        <div v-if="slugIsOpen === false" class="myInputGroup">
                            <InputLabel for="slug" value="Slug" />
                            <div class="relative flex items-center">
                                <TextInput
                                    placeholder="Post slug - lock er låst"
                                    id="slug"
                                    v-model="createPostForm.slug"
                                    type="text"
                                    class="block w-full mt-1 myPrimaryInputReadonly"
                                    autofocus
                                    readonly
                                    autocomplete="off"
                                />
                                <div
                                    @click="handleOpenLock"
                                    class="cursor-pointer absolute inset-y-0 right-0 pr-1.5 flex items-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        class="w-5 h-5 text-myErrorColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <InputError :message="createPostForm.errors.slug" />
                        </div>
                        <div v-if="slugIsOpen === true" class="myInputGroup">
                            <InputLabel for="slug" value="Slug" />
                            <div class="relative flex items-center">
                                <TextInput
                                    placeholder="Post slug - lock er open"
                                    id="slug"
                                    v-model="productSlugLock"
                                    type="text"
                                    class="block w-full mt-1"
                                    autofocus
                                    autocomplete="off"
                                />
                                <div
                                    @click="handleCloseLock"
                                    class="cursor-pointer absolute inset-y-0 right-0 pr-1.5 flex items-center"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-5 h-5 text-green-600"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <p class="myPrimaryParagraph italic">
                                {{ createPostForm.slug }}
                            </p>
                            <InputError :message="createPostForm.errors.slug" />
                        </div>
                        <!-- post slug end -->

                        <!-- post content start -->
                        <div class="myInputGroup">
                            <InputLabel
                                for="content"
                                value="Text editor"
                                class="mb-1"
                            />
                            <QuillEditor
                                id="content"
                                v-model:content="createPostForm.content"
                                contentType="html"
                                :options="globalOptions"
                                class="rounded-b-md bg-white"
                            >
                            </QuillEditor>
                            <InputError
                                :message="createPostForm.errors.content"
                            />
                        </div>
                        <!-- post content end -->
                    </div>
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
                                Specify Post status.
                            </p>
                        </div>
                        <div
                            class="myInputGroup flex myPrimaryGap flex-row-reverse justify-end"
                        >
                            <InputLabel
                                :value="
                                    createPostForm.published
                                        ? 'Published'
                                        : 'Private'
                                "
                                :class="{
                                    'text-myPrimaryBrandColor':
                                        createPostForm.published,
                                    'text-myErrorColor':
                                        !createPostForm.published,
                                }"
                            />
                            <Switch
                                v-model="createPostForm.published"
                                :class="[
                                    createPostForm.published
                                        ? 'bg-myPrimaryBrandColor'
                                        : 'bg-gray-200',
                                    'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-myPrimaryBrandColor focus:ring-offset-2',
                                ]"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    :class="[
                                        createPostForm.published
                                            ? 'translate-x-5'
                                            : 'translate-x-0',
                                        'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                    ]"
                                >
                                    <span
                                        :class="[
                                            createPostForm.published
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
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </span>
                                    <span
                                        :class="[
                                            createPostForm.published
                                                ? 'opacity-100 ease-in duration-200'
                                                : 'opacity-0 ease-out duration-100',
                                            'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity',
                                        ]"
                                        aria-hidden="true"
                                    >
                                        <svg
                                            class="h-3 w-3 text-myPrimaryBrandColor"
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
                        <InputError
                            :message="createPostForm.errors.published"
                        />
                    </div>
                    <!-- post status - end -->
                    <!-- cover image - start -->
                    <div class="myInputsOrganization">
                        <div
                            class="myPrimaryFormOrganizationHeaderDescriptionSection"
                        >
                            <div class="myPrimaryFormOrganizationHeader">
                                Cover Image
                            </div>
                            <p class="myPrimaryParagraph">
                                Lorem ipsum dolor sit amet.
                            </p>
                        </div>
                        <img
                            v-if="
                                createPostForm.thumbnail &&
                                createPostForm.thumbnail.length !== 0
                            "
                            @click="handleUploadCoverImage"
                            class="myPrimarythumbnailInsertPreview"
                            alt="cover image"
                            :src="`/uploads/${createPostForm.thumbnail}`"
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

                                Cover Image
                            </button>

                            <div
                                v-if="
                                    createPostForm && createPostForm.thumbnail
                                "
                            >
                                <svg
                                    @click="handleRemoveCoverImage"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5 text-myErrorColor cursor-pointer"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                    />
                                </svg>
                            </div>
                        </div>
                        <InputError
                            :message="createPostForm.errors.thumbnail"
                        />
                    </div>
                    <!-- cover image - end -->
                    <!-- tags - start -->
                    <div class="myInputsOrganization">
                        <div
                            class="myPrimaryFormOrganizationHeaderDescriptionSection"
                        >
                            <div class="myPrimaryFormOrganizationHeader">
                                Tags
                            </div>
                            <p class="myPrimaryParagraph">
                                Lorem ipsum dolor sit amet.
                            </p>
                        </div>
                        <div class="myInputGroup">
                            <Tags
                                :tagsOnLoad="createPostForm.tags"
                                @firstTagsButton="firstTagsButton"
                            ></Tags>
                            <InputError :message="createPostForm.errors.tags" />
                        </div>
                    </div>
                    <!-- tags - end -->

                    <div class="myInputsOrganization">
                        <div
                            class="myPrimaryFormOrganizationHeaderDescriptionSection"
                        >
                            <div class="myPrimaryFormOrganizationHeader">
                                Lorem, ipsum dolor.
                            </div>
                            <p class="myPrimaryParagraph">
                                Lorem ipsum dolor sit amet.
                            </p>
                        </div>
                        <div class="myInputGroup">
                            <Listbox as="div" v-model="createPostForm.team">
                                <ListboxLabel class="myPrimaryInputLabel"
                                    >Post belongs to Team</ListboxLabel
                                >
                                <div class="relative mt-1">
                                    <ListboxButton class="myPrimarySelect">
                                        <span class="block truncate">
                                            {{ createPostForm.team.name }}
                                        </span>
                                        <span
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                        >
                                            <ChevronUpDownIcon
                                                class="h-5 w-5 text-gray-400"
                                                aria-hidden="true"
                                            />
                                        </span>
                                    </ListboxButton>

                                    <transition
                                        leave-active-class="transition ease-in duration-100"
                                        leave-from-class="opacity-100"
                                        leave-to-class="opacity-0"
                                    >
                                        <ListboxOptions
                                            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                        >
                                            <ListboxOption
                                                as="template"
                                                v-for="team in user.all_teams"
                                                :key="team.id"
                                                :value="team"
                                            >
                                                <li
                                                    class="hover:text-white hover:bg-myPrimaryDarkGrayColor"
                                                    :class="[
                                                        team.id ===
                                                        createPostForm.team.id
                                                            ? ''
                                                            : '',
                                                        'relative cursor-default select-none py-2 pl-3 pr-9',
                                                    ]"
                                                >
                                                    <span
                                                        :class="[
                                                            team.id ===
                                                            createPostForm.team
                                                                .id
                                                                ? 'font-normal'
                                                                : 'font-normal',
                                                            'block truncate',
                                                        ]"
                                                        >{{ team.name }}</span
                                                    >
                                                    <span
                                                        v-if="
                                                            team.id ===
                                                            createPostForm.team
                                                                .id
                                                        "
                                                        :class="[
                                                            team.id ===
                                                            createPostForm.team
                                                                .id
                                                                ? ''
                                                                : '',
                                                            'absolute inset-y-0 right-0 flex items-center pr-4',
                                                        ]"
                                                    >
                                                        <CheckIcon
                                                            :class="[
                                                                team.id ===
                                                                createPostForm
                                                                    .team.id
                                                                    ? ''
                                                                    : 'text-white',
                                                            ]"
                                                            class="h-5 w-5"
                                                            aria-hidden="true"
                                                        />
                                                    </span>
                                                </li>
                                            </ListboxOption>
                                        </ListboxOptions>
                                    </transition>
                                </div>
                            </Listbox>
                            <InputError :message="createPostForm.errors.team" />
                        </div>
                    </div>
                </template>

                <template #actions>
                    <SubmitButton
                        :disabled="createPostForm.processing"
                        buttonText="Create Post"
                    >
                    </SubmitButton>
                    <div
                        class="flex justify-end mt-4"
                        v-if="Object.values(createPostForm.errors).length !== 0"
                    >
                        <div
                            @click="showErrorNotifications = true"
                            class="w-fit py-1 flex item-center gap-2 rounded-md px-2 cursor-pointer italic"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-4 h-4 text-myErrorColor"
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
                                class="myPrimaryParagraph text-xs text-myErrorColor py-0 my-0"
                            >
                                Show
                                {{
                                    Object.values(createPostForm.errors).length
                                }}
                                errors
                            </p>
                        </div>
                    </div>
                    <MediaLibraryModal
                        :user="user"
                        :team="createPostForm.team"
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
                        :listOfMessages="Object.values(createPostForm.errors)"
                        :show="showErrorNotifications"
                        @notificationsModalButton="notificationsModalButton"
                    ></NotificationsFixedBottom>
                </template>
            </FormSection>
        </div>
    </LoggedInLayout>
</template>
