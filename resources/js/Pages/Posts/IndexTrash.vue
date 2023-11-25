<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import CardHeadings from "@/Components/Actions/CardHeadings.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { onMounted, ref } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import { parseISO, format } from "date-fns";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import TopMenu from "@/Components/Menues/TopMenu.vue";

import {
    ArrowLeftIcon,
    ArrowRightIcon,
    CheckIcon,
    ChevronDownIcon,
    DocumentDuplicateIcon,
    EllipsisVerticalIcon,
    PlusCircleIcon,
    PlusIcon,
    MapPinIcon,
    TagIcon,
    TrashIcon,
    Squares2X2Icon,
    UserIcon,
    PencilIcon,
    ArrowPathIcon,
} from "@heroicons/vue/24/outline";

import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import UserTag from "@/Components/Users/UserTag.vue";

const props = defineProps({
    posts: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
    oldInput: {
        search_query: {
            required: false,
        },
    },
});

const breadcrumbsLinks = [
    {
        label: "All Posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
];

const linksTopMenu = [
    {
        label: "All Post",
        icon: "hub",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Trash",
        icon: "delete",
        route: {
            name: "team.posts.index.trash",
            parameters: [props.currentUserTeam.id],
        },
    },
];

const routesArray = [
    {
        label: "All Posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Create Post",
        route: {
            name: "team.posts.create",
            parameters: [props.currentUserTeam.id],
        },
    },
];

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
const handleDelete = function (postId, post) {
    modalShowDeletePost.value = true;

    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 3;
    titleModal.value = `Permanently delete ${post.title}?`;
    descriptionModal.value = `Are you sure you want to permanently delete post with title ${post.title}?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete Post";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePost.value = false;
    };
    // handle click
    // secondModalButtonFunction.value = function () {
    //     // handle show modal for unique content
    //     modalShowDeletePost.value = false;
    // };
    // handle click
    thirdModalButtonFunction.value = function () {
        deletePost(postId);
    };
    // end modal
};
// form
const deletePostForm = useForm({});

// form action
const deletePost = (postId) => {
    deletePostForm.delete(
        route("team.posts.post.destroy.force", [
            postId,
            props.currentUserTeam.id,
        ]),
        {
            preserveScroll: true,
            onSuccess: () => (modalShowDeletePost.value = false),
            onError: (err) => {},
            onFinish: (log) => {
                modalShowDeletePost.value = false;
            },
        }
    );
};

// handle action
const modalShowRestorePost = ref(false);

const handleRestore = function (postId, post) {
    modalShowRestorePost.value = true;

    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 3;
    titleModal.value = `Restore post ${post.title}?`;
    descriptionModal.value = `Are you sure you want to restore this post?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Restore post";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowRestorePost.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        handleRestorePost(postId);
    };
    // end modal
};

const restoreForm = useForm({});

const handleRestorePost = function (postId) {
    restoreForm.post(
        route("team.posts.restore", [
            postId,
            props.currentUserTeam.id,
            //
        ]),
        {
            onSuccess: () => (modalShowRestorePost.value = false),
            onError: (err) => {},
            onFinish: (log) => {
                modalShowRestorePost.value = false;
            },
        }
    );
};

// form
const searchForm = useForm({
    search_query: "",
});

const handleSearch = function () {
    searchForm.get(
        route("team.posts.index.trash", [props.currentUserTeam.id]),
        {
            preserveScroll: true,
            onSuccess: () => {},
            onError: (err) => {},
            onFinish: () => {},
        }
    );
};

const scrolTableContainer = ref("scrolTableContainer");

const handleLeft = function () {
    if (scrolTableContainer.value) {
        scrolTableContainer.value.scrollBy({
            left: -800,
            behavior: "smooth",
        });
    }
};
const handleRight = function () {
    if (scrolTableContainer.value) {
        scrolTableContainer.value.scrollBy({
            left: +800,
            behavior: "smooth",
        });
    }
};

onMounted(() => {
    if (props.oldInput?.search_query) {
        searchForm.search_query = props.oldInput.search_query;
    }
});
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Posts" />
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
            <DynamicModal
                :show="modalShowRestorePost"
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
                Posts for
                {{ $page.props.user && $page.props.currentUserTeam.name }}
            </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <CardHeadings :routesArray="routesArray">
                <template #title
                    >Posts for
                    {{ $page.props.user && $page.props.user.current_team.name }}
                </template>
                <template #buttons>
                    <Link
                        class="myPrimaryButton"
                        type="button"
                        :href="route('team.posts.create', currentUserTeam.id)"
                    >
                        <span class="material-symbols-outlined"> add </span>
                        Create Post
                    </Link>
                </template>
            </CardHeadings>

            <TopMenu :links="linksTopMenu"></TopMenu>

            <form @submit.prevent="handleSearch">
                <div class="myPrimarySection">
                    <div class="mysearchBarWithOptions">
                        <div class="relative w-full">
                            <div
                                class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                            >
                                <svg
                                    aria-hidden="true"
                                    class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                    ></path>
                                </svg>
                            </div>
                            <input
                                v-model="searchForm.search_query"
                                type="search"
                                id="search_query"
                                class="myPrimarySearchInput"
                                autocomplete="off"
                                placeholder="Search..."
                            />
                        </div>

                        <button
                            @click="handleSearch"
                            type="button"
                            class="myPrimarySearchButton"
                        >
                            Search
                        </button>
                    </div>
                </div>
            </form>

            <template v-if="posts && posts.data.length <= 0">
                <h1 class="myTertiaryHeader">No Posts</h1>
                <p class="myPrimaryParagraph">Looks like there are no posts!</p>
            </template>

            <!-- table start -->
            <div
                v-if="posts && posts.data.length >= 1"
                class="myTableContainerPlusScrollButton"
            >
                <div class="myScrollButtonContainer">
                    <button
                        @click="handleLeft"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <ArrowLeftIcon class="mySmallIcon"></ArrowLeftIcon>
                    </button>
                    <button
                        @click="handleRight"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <ArrowRightIcon class="mySmallIcon"></ArrowRightIcon>
                    </button>
                </div>
                <div ref="scrolTableContainer" class="myTableContainer">
                    <div class="myTableSubContainer">
                        <table class="myPrimaryTable" aria-describedby="index">
                            <thead class="myPrimaryTableTHead">
                                <tr class="myPrimaryTableTr">
                                    <th scope="col" class="myPrimaryTableTh">
                                        Thumbnail
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Title
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Post ID
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Team Name
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Status
                                    </th>

                                    <th scope="col" class="myPrimaryTableTh">
                                        Show Authors
                                    </th>

                                    <th scope="col" class="myPrimaryTableTh">
                                        Authors
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Categories
                                    </th>

                                    <th scope="col" class="myPrimaryTableTh">
                                        Tags
                                    </th>

                                    <th scope="col" class="myPrimaryTableTh">
                                        Updated By
                                    </th>

                                    <th scope="col" class="myPrimaryTableTh">
                                        Updated Date
                                    </th>

                                    <th scope="col" class="myPrimaryTableTh">
                                        Created Date
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Restore
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Delete
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
                                            <div
                                                v-if="
                                                    Array.isArray(
                                                        post.cover_images
                                                    ) &&
                                                    post.cover_images.length !==
                                                        0
                                                "
                                            >
                                                <ThumbnailSmallImageSlider
                                                    :images="post.cover_images"
                                                    imageSize="medium_path"
                                                    imageHeight="h-28"
                                                    imageWidth="w-28"
                                                    :roundedFull="true"
                                                ></ThumbnailSmallImageSlider>
                                            </div>
                                        </td>

                                        <td
                                            class="myPrimaryTableTBodyTd myPrimaryResourceTableBodyTdTitle"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'team.posts.post.show',
                                                        [
                                                            $page.props.user
                                                                .current_team
                                                                .id,
                                                            post.slug,
                                                            post.id,
                                                        ]
                                                    )
                                                "
                                            >
                                                <span
                                                    class="text-myPrimaryLinkColor"
                                                >
                                                    {{ post.title }}
                                                </span>
                                            </Link>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{ post.id }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{
                                                $page.props.user &&
                                                $page.props.user.current_team
                                                    .name
                                            }}
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="myPrimaryTag"
                                                :class="
                                                    post.published
                                                        ? 'bg-myPrimaryLinkColor text-white'
                                                        : 'bg-myPrimaryErrorColor text-white'
                                                "
                                            >
                                                {{
                                                    post.published
                                                        ? "Published"
                                                        : "Unpublished"
                                                }}
                                            </div>
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="myPrimaryTag"
                                                :class="
                                                    post.show_author
                                                        ? 'bg-myPrimaryLinkColor text-white'
                                                        : 'bg-myPrimaryErrorColor text-white'
                                                "
                                            >
                                                {{
                                                    post.show_author
                                                        ? "Visible"
                                                        : "Hidden"
                                                }}
                                            </div>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="flex flex-wrap justify-start items-center gap-1"
                                            >
                                                <div
                                                    v-for="author in post.authors &&
                                                    post.authors"
                                                    :key="author"
                                                    class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                >
                                                    <div
                                                        v-if="
                                                            author.profile_photo_path !==
                                                            null
                                                        "
                                                    >
                                                        <div
                                                            class="h-5 w-5 flex-shrink-0"
                                                        >
                                                            <img
                                                                class="object-cover h-5 w-5 rounded-full"
                                                                :src="`/storage/${author.profile_photo_path}`"
                                                                alt="avatar"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div
                                                        style="
                                                            font-size: 9px;
                                                            gap: 2px;
                                                        "
                                                        v-if="
                                                            author.profile_photo_path ===
                                                            null
                                                        "
                                                        class="flex-shrink-0 h-5 w-5 rounded-full bg-myPrimaryBrandColor flex justify-center items-center font-normal text-white text-xs"
                                                    >
                                                        <span>
                                                            {{
                                                                author.first_name
                                                                    .charAt(0)
                                                                    .toUpperCase()
                                                            }}
                                                        </span>
                                                        <span>
                                                            {{
                                                                author.last_name
                                                                    .charAt(0)
                                                                    .toUpperCase()
                                                            }}
                                                        </span>
                                                    </div>
                                                    <span
                                                        class="flex flex-col items-left gap-1 myPrimaryParagraph"
                                                    >
                                                        <p
                                                            style="
                                                                font-size: 10px;
                                                            "
                                                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1 pl-0 pr-1 flex justify-center items-center gap-1"
                                                        >
                                                            <span>
                                                                {{
                                                                    author.first_name
                                                                }}
                                                                {{
                                                                    author.last_name
                                                                }}
                                                            </span>
                                                        </p>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="flex flex-wrap justify-start items-center gap-2"
                                            >
                                                <p
                                                    v-for="category in post.categories &&
                                                    Array.isArray(
                                                        post.categories
                                                    ) &&
                                                    post.categories.sort(
                                                        (a, b) => {
                                                            const nameA =
                                                                a.name;
                                                            const nameB =
                                                                b.name;

                                                            if (nameA < nameB) {
                                                                return -1;
                                                            } else if (
                                                                nameA > nameB
                                                            ) {
                                                                return 1;
                                                            } else {
                                                                return 0;
                                                            }
                                                        }
                                                    )"
                                                    :key="category"
                                                    class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        category
                                                    </span>
                                                    <span>
                                                        {{ category.name }}
                                                    </span>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="flex flex-wrap justify-start items-center gap-2"
                                            >
                                                <p
                                                    v-for="tag in post.tags &&
                                                    post.tags
                                                        .split(',')
                                                        .sort((a, b) =>
                                                            a.localeCompare(b)
                                                        )"
                                                    :key="tag"
                                                    class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        loyalty
                                                    </span>
                                                    <span>
                                                        {{ tag }}
                                                    </span>
                                                </p>
                                            </div>
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            <UserTag
                                                :user="post.updatedBy"
                                                :showJobTitle="true"
                                            ></UserTag>
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            {{
                                                format(
                                                    parseISO(post.updated_at),
                                                    "dd. MMMM yyyy HH:mm"
                                                )
                                            }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{
                                                format(
                                                    parseISO(post.created_at),
                                                    "dd. MMMM yyyy HH:mm"
                                                )
                                            }}
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            <button
                                                type="button"
                                                @click="
                                                    handleRestore(post.id, post)
                                                "
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                            >
                                                <span
                                                    class="material-symbols-outlined"
                                                >
                                                    undo
                                                </span>
                                            </button>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <button
                                                type="button"
                                                @click="
                                                    handleDelete(post.id, post)
                                                "
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                            >
                                                <span
                                                    class="myMediumIcon material-symbols-outlined"
                                                >
                                                    delete
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                </TransitionGroup>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- table end -->
            <Pagination :links="posts?.links ? posts.links : []"></Pagination>
        </LoggedInLayout>
    </MainLayout>
</template>
