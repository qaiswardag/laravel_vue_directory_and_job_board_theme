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

import { useStore } from "vuex";
import { computed } from "vue";

import TableSettings from "@/Components/Tables/TableSettings.vue";

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

const store = useStore();

const breadcrumbsLinks = [
    {
        label: "Running posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
];

const linksTopMenu = [
    {
        label: "Running Posts",
        icon: "article",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Draft",
        icon: "draw",
        route: {
            name: "team.posts.index.draft",
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
        label: "All Running posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: " Create Post",
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

// Builder # Start
const getUserSettings = computed(() => {
    return store.getters["user/getUserSettings"];
});

onMounted(() => {
    if (props.oldInput?.search_query) {
        searchForm.search_query = props.oldInput.search_query;
    }
});
</script>

<template>
    <Head title="Posts" />

    <MainLayout>
        <LoggedInLayout>
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
                Posts
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
                                <span class="material-symbols-outlined">
                                    search
                                </span>
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
                <h1 class="myQuaternaryHeader">No Posts</h1>
                <p class="myPrimaryParagraph">Looks like there are no posts!</p>
            </template>

            <!-- table start -->
            <TableSettings></TableSettings>
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
                                    <template
                                        v-if="
                                            getUserSettings &&
                                            getUserSettings.showPostId
                                        "
                                    >
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Post ID
                                        </th>
                                    </template>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Company Name
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Status
                                    </th>

                                    <template
                                        v-if="
                                            getUserSettings &&
                                            getUserSettings.categoriesShow
                                        "
                                    >
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Categories
                                        </th>
                                    </template>

                                    <th scope="col" class="myPrimaryTableTh">
                                        Updated By
                                    </th>

                                    <template
                                        v-if="
                                            getUserSettings &&
                                            getUserSettings.createdUpdatedDateShow
                                        "
                                    >
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated Date
                                        </th>

                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Created Date
                                        </th>
                                    </template>
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
                                                    imageHeight="h-28 min-h-28 max-h-28"
                                                    imageWidth="w-28 min-w-28 max-w-28 object-cover"
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
                                        <template
                                            v-if="
                                                getUserSettings &&
                                                getUserSettings.showPostId
                                            "
                                        >
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.id }}
                                            </td>
                                        </template>
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

                                        <template
                                            v-if="
                                                getUserSettings &&
                                                getUserSettings.categoriesShow
                                            "
                                        >
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

                                                                if (
                                                                    nameA <
                                                                    nameB
                                                                ) {
                                                                    return -1;
                                                                } else if (
                                                                    nameA >
                                                                    nameB
                                                                ) {
                                                                    return 1;
                                                                } else {
                                                                    return 0;
                                                                }
                                                            }
                                                        )"
                                                        :key="category"
                                                        class="text-xs py-1.5 px-2 flex justify-center items-center gap-1 myPrimaryTag"
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
                                        </template>

                                        <td class="myPrimaryTableTBodyTd">
                                            <UserTag
                                                :user="post.updatedBy"
                                                :showJobTitle="true"
                                            ></UserTag>
                                        </td>

                                        <template
                                            v-if="
                                                getUserSettings &&
                                                getUserSettings.createdUpdatedDateShow
                                            "
                                        >
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.updated_at
                                                        ),
                                                        "dd. MMMM yyyy HH:mm"
                                                    )
                                                }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.created_at
                                                        ),
                                                        "dd. MMMM yyyy HH:mm"
                                                    )
                                                }}
                                            </td>
                                        </template>

                                        <td class="myPrimaryTableTBodyTd">
                                            <button
                                                type="button"
                                                @click="
                                                    handleRestore(post.id, post)
                                                "
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
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
