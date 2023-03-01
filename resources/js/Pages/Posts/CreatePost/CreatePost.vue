<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import TextArea from "@/Components/Forms/TextArea.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import { onMounted, ref, computed } from "vue";
import { Switch } from "@headlessui/vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import Tags from "@/Components/Forms/Tags.vue";
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

const createPostForm = useForm({
    title: "",
    slug: "",
    content: "",
    published: true,
    team: props.currentUserTeam,
    user_id: props.user.id,
    tags: "One,Two,Three",
});

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
                        </div>
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
                        <div class="myInputGroup">
                            <InputLabel for="slug" value="Slug" />
                            <TextInput
                                placeholder="Post slug"
                                id="slug"
                                v-model="createPostForm.slug"
                                type="text"
                                class="block w-full mt-1"
                                autofocus
                                autocomplete="off"
                            />
                            <InputError :message="createPostForm.errors.slug" />
                        </div>

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
                    </div>

                    <div class="myInputsOrganization">
                        <div
                            class="myPrimaryFormOrganizationHeaderDescriptionSection"
                        >
                            <div class="myPrimaryFormOrganizationHeader">
                                Something here
                            </div>
                            <p class="myPrimaryParagraph">
                                Lorem ipsum dolor sit.
                            </p>
                        </div>
                        <div class="myInputGroup">
                            <div class="col-span-3">
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Cover photo</label
                                >
                                <div
                                    class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6"
                                >
                                    <div class="space-y-1 text-center">
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-400"
                                            stroke="currentColor"
                                            fill="none"
                                            viewBox="0 0 48 48"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <InputLabel
                                                for="file-upload"
                                                value="Upload a file"
                                                class="mb-1"
                                            />

                                            <input
                                                id="file-upload"
                                                name="file-upload"
                                                type="file"
                                                class="sr-only"
                                            />

                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG or JPG
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <InputError
                                :message="
                                    createPostForm.errors.post_cover_image
                                "
                            />
                        </div>
                    </div>
                </template>

                <template #sidebar>
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
                                class="w-5 h-5"
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
                            <p class="myPrimaryParagraph">Show errors</p>
                        </div>
                    </div>
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
