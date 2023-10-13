<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import CardHeadings from "@/Components/Actions/CardHeadings.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { computed, nextTick, onMounted, ref } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import { parseISO, format } from "date-fns";
import UserTag from "@/Components/Users/UserTag.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";

import {
    EllipsisVerticalIcon,
    BriefcaseIcon,
    CheckIcon,
    GlobeAmericasIcon,
    MapIcon,
    MapPinIcon,
    NewspaperIcon,
    PencilIcon,
    Squares2X2Icon,
    SquaresPlusIcon,
    TagIcon,
    TrashIcon,
    UserIcon,
    ArrowLeftIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/outline";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";

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
        label: "All Jobs",
        route: {
            name: "team.jobs.index",
            parameters: [props.currentUserTeam.id],
        },
    },
];

const routesArray = [
    {
        label: "All Jobs",
        route: {
            name: "team.jobs.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Create Job",
        route: {
            name: "team.jobs.create",
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
    descriptionModal.value = `Are you sure you want to delete job with title ${post.title}?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete Job";

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
        route("team.jobs.job.destroy", [postId, props.currentUserTeam.id]),
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
    router.get(route("team.jobs.job.edit", [props.currentUserTeam.id, postId]));
};

const duplicateForm = useForm({
    teamId: props.currentUserTeam.id,
    postId: "",
});

// handle action
const handleDuplicate = function (postId) {
    duplicateForm.postId = postId;
    //
    duplicateForm.post(route("team.jobs.duplicate"), {
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
    searchForm.get(route("team.jobs.index", [props.currentUserTeam.id]), {
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
            left: -600,
            behavior: "smooth",
        });
    }
};
const handleRight = function () {
    if (scrolTableContainer.value) {
        scrolTableContainer.value.scrollBy({
            left: +600,
            behavior: "smooth",
            block: "start",
            inline: "start",
        });
    }
};

window.addEventListener("keyup", (e) => {
    if (e.key === "ArrowLeft") {
        handleLeft();
    }
    if (e.key === "ArrowRight") {
        handleRight();
    }
});
onMounted(() => {
    if (props.oldInput?.search_query) {
        searchForm.search_query = props.oldInput.search_query;
    }
});
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Jobs" />
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
                    Jobs for
                    {{ $page.props.user && $page.props.currentUserTeam.name }}
                </h2>
            </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <CardHeadings :routesArray="routesArray">
                <template #title
                    >Jobs for
                    {{ $page.props.user && $page.props.user.current_team.name }}
                </template>
                <template #subTitle
                    >Lorem ipsum dolor sit amet consectetur adipisicing elit
                    quam corrupti consectetur.
                </template>
                <template #buttons>
                    <Link
                        class="myPrimaryButton"
                        type="button"
                        :href="route('team.jobs.create', currentUserTeam.id)"
                    >
                        Create Job
                    </Link>
                </template>
            </CardHeadings>

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
                            class="myPrimaryButton"
                        >
                            Search
                        </button>
                    </div>
                </div>
            </form>

            <template v-if="posts && posts.data.length <= 0">
                <h1 class="myPrimaryHeaderMessage">No Jobs</h1>
                <p class="myPrimaryParagraph">Looks like there are no jobs!</p>
            </template>

            <!-- table start -->
            <div
                v-if="posts && posts.data.length >= 1"
                class="myTableContainerPlusScrollButton"
            >
                <div class="myScrollButtonContainer">
                    <button
                        @click="handleLeft"
                        class="h-10 w-10 bg-gray-50 cursor-pointer rounded-full flex items-center justify-center aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                    >
                        <ArrowLeftIcon
                            class="shrink-0 h-4 w-4 m-2 stroke-2"
                        ></ArrowLeftIcon>
                    </button>
                    <button
                        @click="handleRight"
                        class="h-10 w-10 bg-gray-50 cursor-pointer rounded-full flex items-center justify-center aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                    >
                        <ArrowRightIcon
                            class="shrink-0 h-4 w-4 m-2 stroke-2"
                        ></ArrowRightIcon>
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
                                        Job ID
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
                                        Country
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        State
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Job Type
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
                                                        'team.jobs.job.show',
                                                        [
                                                            $page.props.user
                                                                .current_team
                                                                .id,
                                                            post.id,
                                                            post.slug,
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
                                            <span
                                                class="myPrimaryTag"
                                                :class="
                                                    post.published
                                                        ? 'bg-myPrimaryLinkColor text-white'
                                                        : 'bg-myPrimaryErrorColor text-white'
                                                "
                                                >{{
                                                    post.published
                                                        ? "Published"
                                                        : "Unpublished"
                                                }}</span
                                            >
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <span
                                                class="myPrimaryTag"
                                                :class="
                                                    post.show_author
                                                        ? 'bg-myPrimaryLinkColor text-white'
                                                        : 'bg-myPrimaryErrorColor text-white'
                                                "
                                                >{{
                                                    post.show_author
                                                        ? "Visible"
                                                        : "Hidden"
                                                }}</span
                                            >
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
                                                    v-for="jobCountry in post.countries &&
                                                    post.countries"
                                                    :key="jobCountry"
                                                    class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                >
                                                    <GlobeAmericasIcon
                                                        class="w-3 h-3 stroke-1"
                                                    ></GlobeAmericasIcon>
                                                    <span>
                                                        {{ jobCountry.name }}
                                                    </span>
                                                </p>
                                            </div>
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="flex flex-wrap justify-start items-center gap-2"
                                            >
                                                <p
                                                    v-for="jobState in post.states &&
                                                    post.states"
                                                    :key="jobState"
                                                    class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                >
                                                    <MapPinIcon
                                                        class="w-3 h-3 stroke-1"
                                                    ></MapPinIcon>
                                                    <span>
                                                        {{ jobState.name }}
                                                    </span>
                                                </p>
                                            </div>
                                        </td>

                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="flex flex-wrap justify-start items-center gap-2"
                                            >
                                                <p
                                                    v-for="jobType in post.types &&
                                                    post.types"
                                                    :key="jobType"
                                                    class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1.5 px-2 flex justify-center items-center gap-1"
                                                >
                                                    <NewspaperIcon
                                                        class="w-3 h-3 stroke-1"
                                                    >
                                                    </NewspaperIcon>
                                                    <span>
                                                        {{ jobType.name }}
                                                    </span>
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
                                                    <Squares2X2Icon
                                                        class="w-3 h-3 stroke-1"
                                                    ></Squares2X2Icon>

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
                                                    <TagIcon
                                                        class="w-3 h-3 stroke-1"
                                                    ></TagIcon>
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
                                                    "dd/MM/yyyy HH:mm"
                                                )
                                            }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{
                                                format(
                                                    parseISO(post.created_at),
                                                    "dd/MM/yyyy HH:mm"
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
                                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                    >
                                                        <EllipsisVerticalIcon
                                                            class="shrink-0 h-4 w-4 m-2 stroke-2"
                                                            aria-hidden="true"
                                                        />
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
                                                            class="w-full flex justify-start px-4 py-2 text-sm leading-5 text-myPrimaryDarkGrayColor hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition text-myPrimaryBrandColor"
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
                                                                <CheckIcon
                                                                    class="w-4 h-4"
                                                                ></CheckIcon>
                                                                Duplicate Job
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
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                            >
                                                <PencilIcon
                                                    class="shrink-0 w-4 h-4 m-2 stroke-2"
                                                ></PencilIcon>
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
                                                <TrashIcon
                                                    class="shrink-0 w-4 h-4 m-2 stroke-2"
                                                ></TrashIcon>
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
