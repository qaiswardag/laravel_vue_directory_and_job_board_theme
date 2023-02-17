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
import { ref } from "vue";
import { Switch } from "@headlessui/vue";
import SingleSelect from "@/Components/Forms/SingleSelect.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

// start Quill Editor
// define options
const globalOptions = {
    // debug: "info",
    modules: {
        toolbar: ["bold", "italic", "underline"],
    },
    placeholder: "Compose an epic...",
    readOnly: false,
    theme: "bubble",

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
    placeholder: "Description - short (optional)",
    readOnly: false,
};
// end Quill Editor

const createPostForm = useForm({
    title: "",
    content: "",
    published: true,
    team: "la la",
});

const handleCreatePost = function () {
    console.log("ok, so you want to post a post!");

    // store post
    createPost();
};

const createPost = () => {
    createPostForm.post(route("overview.posts.store"), {
        errorBag: "createPost",
        preserveScroll: true,
        onSuccess: () => {},
        onError: () => {
            console.log("something went wrong posting the Post");
        },
        onFinish: () => {
            console.log("came to on finish");
        },
    });
};

const people = [
    { id: 1, name: "Wade Cooper" },
    { id: 2, name: "Arlene Mccoy" },
    { id: 3, name: "Devon Webb" },
    { id: 4, name: "Tom Cook" },
    { id: 5, name: "Tanya Fox" },
    { id: 6, name: "Hellen Schmidt" },
    { id: 7, name: "Caroline Schultz" },
    { id: 8, name: "Mason Heaney" },
    { id: 9, name: "Claudie Smitham" },
    { id: 10, name: "Emil Schaefer" },
];

const breadcrumbsLinks = [
    { label: "Posts", url: "overview.posts.index" },
    { label: "Add Post" },
];
// toggle end
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
                        <div class="myPrimaryFormOrganizationHeader"></div>

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
                            <InputLabel
                                for="title"
                                value="Your Post title"
                                class="mb-1"
                            />
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
                            <InputLabel
                                for="content"
                                value="Text editor"
                                class="mb-1"
                            />
                            <QuillEditor
                                id="content"
                                v-model:content="createPostForm.content"
                                autofocus
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
                            <InputLabel
                                for="title"
                                value="Your Post title"
                                class="mb-1"
                            />
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
                            <InputLabel
                                for="select_team"
                                value="Post belongs to Team"
                                class="mb-1"
                            />
                            <SingleSelect
                                :list="$page.props.user.all_teams"
                                v-model="createPostForm.team"
                            ></SingleSelect>
                            <InputError
                                :message="createPostForm.errors.team_id"
                            />
                        </div>
                    </div>
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
                            <InputLabel
                                for="title"
                                value="Your Post title"
                                class="mb-1"
                            />
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
                            <InputLabel
                                for="title"
                                value="Your Post title"
                                class="mb-1"
                            />
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
                    </div>
                </template>

                <template #actions>
                    <SubmitButton
                        :disabled="createPostForm.processing"
                        buttonText="Create Post"
                    >
                    </SubmitButton>
                </template>
            </FormSection>
        </div>
    </LoggedInLayout>
</template>
