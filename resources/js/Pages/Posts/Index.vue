<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import ActionSection from "@/Components/ActionSection.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import DescriptionPlusCreate from "../../Components/Actions/DescriptionPlusCreate.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "../../Components/Buttons/SubmitButton.vue";
import { ref } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

defineProps({
    posts: {
        required: true,
    },
});

const modalShowDeletePost = ref(false);

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

// handle action
const handleDelete = function (id, post) {
    modalShowDeletePost.value = true;

    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 2;
    titleModal.value = `Delete Post: ${post.title}?`;
    descriptionModal.value = `Are you sure you want to delete post  with title ${post.title}?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete Post";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePost.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePost.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        deletePost(id);
    };
    // end modal
};
// form
const deletePostForm = useForm({});

// form action
const deletePost = (postId) => {
    deletePostForm.delete(route("overview.posts.destroy", postId), {
        preserveScroll: true,
        onSuccess: () => (modalShowDeletePost.value = false),
        onError: (err) => {
            console.log("Something went wrong chaning role. Error:", err);
        },
        onFinish: (log) => {
            // console.log("On finish. Here is the log:", log);
            modalShowDeletePost.value = false;
        },
    });
};

// handle action
const handleEdit = function (id) {
    console.log("handle edit function ran. post id is:", id);
};

const breadcrumbsLinks = [
    { label: "Posts", url: "overview.posts.index" },
    { label: "Add Post" },
];
</script>

<template>
    <LoggedInLayout title="overview Posts">
        <DynamicModal
            :show="modalShowDeletePost"
            :type="typeModal"
            :disabled="deletePostForm.processing"
            disabledWhichButton="thirdButton"
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
        <template #header>
            <h2 class="myPrimaryMainPageHeader">
                Posts for
                {{ $page.props.user && $page.props.user.current_team.name }}
            </h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <DescriptionPlusCreate>
            <template #title>
                <div class="py-4">
                    Manage Posts for
                    <span class="font-semibold">
                        {{
                            $page.props.user &&
                            $page.props.user.current_team.name
                        }}
                    </span>
                </div>
            </template>
            <template #buttons>
                <Link
                    class="myPrimaryButton"
                    type="button"
                    :href="route('overview.posts.create')"
                >
                    Add Category
                </Link>
                <Link
                    class="myPrimaryButton"
                    type="button"
                    :href="route('overview.posts.create')"
                >
                    Lorem
                </Link>
                <Link
                    class="myPrimaryButton"
                    type="button"
                    :href="route('overview.posts.create')"
                >
                    Add Post
                </Link>
            </template>
        </DescriptionPlusCreate>

        <h1
            v-if="posts && posts.data.length <= 0"
            class="myPrimarySuccessAlertMessage"
        >
            No Posts yet
        </h1>

        <!-- TABLE START -->
        <div v-if="posts && posts.data.length >= 1" class="myTableContainer">
            <div class="inline-block min-w-full align-middle px-1">
                <div
                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded"
                >
                    <table class="myPrimaryTable">
                        <caption class="myPrimaryTableCaption">
                            Your posts for Team:
                            <span class="font-semibold">
                                {{
                                    $page.props.user &&
                                    $page.props.user.current_team.name
                                }}
                            </span>
                        </caption>
                        <thead class="myPrimaryTableTHead">
                            <tr class="myPrimaryTableTr">
                                <th scope="col" class="myPrimaryTableTh">
                                    Thumbnail
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Title
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Status
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Post Id
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    User Id
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Created by
                                </th>

                                <th scope="col" class="myPrimaryTableTh">
                                    Team name
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Edit
                                    <span class="sr-only">Edit</span>
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Delete
                                    <span class="sr-only">Delete</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="myPrimaryTableTBody">
                            <TransitionGroup name="table">
                                <tr
                                    class="myPrimaryTableTBodyTr"
                                    v-for="post in posts && posts.data"
                                    :key="post.id"
                                >
                                    <td class="myPrimaryTableTBodyTd">
                                        <div class="flex items-center">
                                            <div
                                                class="h-12 w-12 flex-shrink-0"
                                            >
                                                <img
                                                    class="h-12 w-12 rounded-full object-cover"
                                                    src="https://emirateswoman.com/wp-content/uploads/2022/03/Arab-Fashion-Week-2022.png"
                                                    alt=""
                                                />
                                            </div>
                                            <div class="ml-4">
                                                {{ post.title }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        {{ post.title }}
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        <span
                                            :class="
                                                post.published
                                                    ? 'bg-green-100'
                                                    : 'bg-red-100 text-myErrorColor'
                                            "
                                            class="inline-flex rounded-full px-2 font-semibold leading-5 text-green-800"
                                            >{{
                                                post.published
                                                    ? "Published"
                                                    : "Privat"
                                            }}</span
                                        >
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        {{ post.id }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{ post.user_id }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        Author here
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        {{
                                            $page.props.user &&
                                            $page.props.user.current_team.name
                                        }}
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        <button
                                            @click="handleEdit(post.id)"
                                            type="button"
                                            class="myPrimaryButtonNoBackground"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="2"
                                                stroke="currentColor"
                                                class="w-5 h-5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"
                                                />
                                            </svg>

                                            <span class="sr-only"
                                                >, {{ post.title }}</span
                                            >
                                        </button>
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        <SubmitButton
                                            :ButtonStyleDelete="true"
                                            :TableStyle="true"
                                            :disabled="false"
                                            @firstButtonClick="
                                                handleDelete(post.id, post)
                                            "
                                            buttonText=""
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
                                    </td>
                                </tr>
                            </TransitionGroup>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- TABLE END -->
        <Pagination :links="posts?.links ? posts.links : []"></Pagination>
    </LoggedInLayout>
</template>
