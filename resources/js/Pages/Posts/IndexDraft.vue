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
    EllipsisVerticalIcon,
    CheckIcon,
    TagIcon,
    TrashIcon,
    Squares2X2Icon,
    UserIcon,
    PencilIcon,
    ArrowLeftIcon,
    ArrowRightIcon,
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
        label: "Running campaigns",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
];

const linksTopMenu = [
    {
        label: "Running Campaigns",
        icon: "campaign",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Draft Campaigns",
        icon: "draw",
        route: {
            name: "team.posts.index.draft",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Expired Campaigns",
        icon: "schedule",
        route: {
            name: "team.posts.index.expired",
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
        label: "All Running campaigns",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: " Create Campaign",
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
    titleModal.value = `Delete ${post.title}?`;
    descriptionModal.value = `Are you sure you want to delete post with title ${post.title}?`;
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
        route("team.posts.post.destroy", [postId, props.currentUserTeam.id]),
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
const handleEdit = function (postId) {
    router.get(
        route("team.posts.post.edit", [props.currentUserTeam.id, postId])
    );
};

const duplicateForm = useForm({
    teamId: props.currentUserTeam.id,
    postId: "",
});

// handle action
const handleDuplicate = function (postId) {
    duplicateForm.postId = postId;
    //
    duplicateForm.post(route("team.posts.duplicate"), {
        preserveScroll: false,
        onSuccess: () => {},
        onError: () => {},
        onFinish: () => {},
    });
};

// form
const searchForm = useForm({
    search_query: "",
});

const handleSearch = function () {
    searchForm.get(route("team.posts.index", [props.currentUserTeam.id]), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (err) => {},
        onFinish: () => {},
    });
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
            <Head title="Sales" />
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
                Running campaigns
                {{ $page.props.user && $page.props.currentUserTeam.name }}
            </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <CardHeadings :routesArray="routesArray">
                <template #title>
                    Running campaigns
                    {{ $page.props.user && $page.props.user.current_team.name }}
                </template>
                <template #buttons>
                    <Link
                        class="myPrimaryButton"
                        type="button"
                        :href="route('team.posts.create', currentUserTeam.id)"
                    >
                        <span class="material-symbols-outlined"> add </span>
                        Create Campaign
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
                <h1 class="myQuaternaryHeader">No Campaigns</h1>
                <p class="myPrimaryParagraph">
                    Looks like there are no campaigns!
                </p>
            </template>

            <!-- table start -->
            <div
                v-if="posts && posts.data.length >= 1"
                class="myTableContainerPlusScrollButton"
            >
                <div class="myScrollButtonContainer">
                    <button
                        @click="handleLeft"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <ArrowLeftIcon class="mySmallIcon"></ArrowLeftIcon>
                    </button>
                    <button
                        @click="handleRight"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
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
                                        Campaign start date
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Campaign end date
                                    </th>

                                    <th scope="col" class="myPrimaryTableTh">
                                        Stores
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
                                        Options
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Edit
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
                                            <template v-if="post.started_at">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.started_at
                                                        ),
                                                        "dd. MMMM yyyy"
                                                    )
                                                }}
                                            </template>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <template v-if="post.ended_at">
                                                {{
                                                    format(
                                                        parseISO(post.ended_at),
                                                        "dd. MMMM yyyy"
                                                    )
                                                }}
                                            </template>
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="flex flex-col justify-start gap-2"
                                            >
                                                <p
                                                    v-for="store in post.stores &&
                                                    Array.isArray(
                                                        post.stores
                                                    ) &&
                                                    post.stores.sort((a, b) => {
                                                        const nameA = a.title;
                                                        const nameB = b.title;

                                                        if (nameA < nameB) {
                                                            return -1;
                                                        } else if (
                                                            nameA > nameB
                                                        ) {
                                                            return 1;
                                                        } else {
                                                            return 0;
                                                        }
                                                    })"
                                                    :key="store.id"
                                                >
                                                    <Link
                                                        :href="
                                                            route(
                                                                'team.stores.store.show',
                                                                [
                                                                    $page.props
                                                                        .user
                                                                        .current_team
                                                                        .id,
                                                                    store.slug,
                                                                    store.id,
                                                                ]
                                                            )
                                                        "
                                                    >
                                                        <!-- address # start -->
                                                        <div
                                                            class="flex flex-col gap-6 w-full mt-4"
                                                        >
                                                            <div
                                                                class="myPrimaryTag hover:bg-white"
                                                            >
                                                                <div
                                                                    v-for="state in store.states"
                                                                    :key="
                                                                        state.id
                                                                    "
                                                                    class="flex items-center gap-4 my-2 cursor-pointer"
                                                                >
                                                                    <button
                                                                        type="button"
                                                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                                    >
                                                                        <span
                                                                            class="material-symbols-outlined"
                                                                        >
                                                                            local_mall
                                                                        </span>
                                                                    </button>
                                                                    <div
                                                                        class="flex flex-col gap-2"
                                                                    >
                                                                        <p
                                                                            class="myPrimaryParagraph font-medium"
                                                                        >
                                                                            {{
                                                                                store.title
                                                                            }}
                                                                        </p>
                                                                        <div
                                                                            class="flex items-center gap-1"
                                                                        >
                                                                            <span>
                                                                                {{
                                                                                    state.name
                                                                                }}{{
                                                                                    store.address
                                                                                        ? ", "
                                                                                        : ""
                                                                                }}
                                                                            </span>

                                                                            <span
                                                                                v-if="
                                                                                    store.address
                                                                                "
                                                                                class="text-sm flex items-center gap-1"
                                                                            >
                                                                                <span>
                                                                                    {{
                                                                                        store.address
                                                                                    }}
                                                                                </span>
                                                                            </span>
                                                                        </div>

                                                                        <p
                                                                            v-if="
                                                                                store.floor
                                                                            "
                                                                            class="myPrimaryParagraph text-sm flex items-center gap-1"
                                                                        >
                                                                            <span>
                                                                                {{
                                                                                    store.floor ===
                                                                                        0 ||
                                                                                    store.floor ===
                                                                                        "0"
                                                                                        ? "Ground floor"
                                                                                        : `Floor ${store.floor}`
                                                                                }}
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- address -->
                                                        </div>
                                                    </Link>
                                                </p>
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
                                                        interests
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
                                            <Menu
                                                as="div"
                                                class="relative inline-block text-left"
                                            >
                                                <div>
                                                    <MenuButton
                                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                    >
                                                        <span
                                                            class="myMediumIcon material-symbols-outlined"
                                                        >
                                                            more_vert
                                                        </span>
                                                    </MenuButton>
                                                </div>
                                                <transition
                                                    enter-active-class="transition ease-out duration-100"
                                                    enter-from-class="transform opacity-0 scale-95"
                                                    enter-to-class="transform opacity-100 scale-100"
                                                    leave-active-class="transition ease-in duration-75"
                                                    leave-from-class="transform opacity-100 scale-100"
                                                    leave-to-class="transform opacity-0 scale-95"
                                                >
                                                    <MenuItems
                                                        class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                    >
                                                        <MenuItem
                                                            class="w-full flex justify-start text-sm leading-5 text-myPrimaryDarkGrayColor py-4 px-4 z-30 rounded hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition text-myPrimaryBrandColor"
                                                        >
                                                            <button
                                                                class="flex gap-1 items-center"
                                                                type="button"
                                                                @click="
                                                                    handleDuplicate(
                                                                        post.id
                                                                    )
                                                                "
                                                            >
                                                                <span
                                                                    class="myMediumIcon material-symbols-outlined"
                                                                >
                                                                    check
                                                                </span>
                                                                Duplicate Post
                                                            </button>
                                                        </MenuItem>
                                                    </MenuItems>
                                                </transition>
                                            </Menu>
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            <button
                                                type="button"
                                                @click="handleEdit(post.id)"
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                            >
                                                <span
                                                    class="myMediumIcon material-symbols-outlined"
                                                >
                                                    edit
                                                </span>
                                            </button>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <button
                                                type="button"
                                                @click="
                                                    handleDelete(post.id, post)
                                                "
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
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
