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
import TopMenu from "@/Components/Menues/TopMenu.vue";
import PricingForJob from "@/Pages/Jobs/Components/PricingForJob.vue";

import { useStore } from "vuex";

import TableSettings from "@/Components/Tables/TableSettings.vue";

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

const store = useStore();

const breadcrumbsLinks = [
    {
        label: "Running Jobs",
        route: {
            name: "team.jobs.index",
            parameters: [props.currentUserTeam.id],
        },
    },
];

const linksTopMenu = [
    {
        label: "Running Jobs",
        icon: "work",
        route: {
            name: "team.jobs.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Draft & Unpiad Jobs",
        icon: "draw",
        route: {
            name: "team.jobs.index.draft",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Paid Expired Jobs",
        icon: "schedule",
        route: {
            name: "team.jobs.index.expired",
            parameters: [props.currentUserTeam.id],
        },
    },
    {
        label: "Trash",
        icon: "delete",
        route: {
            name: "team.jobs.index.trash",
            parameters: [props.currentUserTeam.id],
        },
    },
];

const routesArray = [
    {
        label: "Running Jobs",
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
    <Head title="Jobs" />

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

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <template #header>
                Jobs for
                {{ $page.props.user && $page.props.currentUserTeam.name }}
            </template>

            <PricingForJob :currentUserTeam="currentUserTeam"></PricingForJob>

            <CardHeadings :routesArray="routesArray">
                <template #title
                    >Jobs for
                    {{ $page.props.user && $page.props.user.current_team.name }}
                </template>
                <template #buttons>
                    <Link
                        class="myPrimaryButton"
                        type="button"
                        :href="route('team.jobs.create', currentUserTeam.id)"
                    >
                        <span class="material-symbols-outlined"> add </span>
                        Create Job
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
                <h1 class="myQuaternaryHeader">No Jobs</h1>
                <p class="myPrimaryParagraph">Looks like there are no jobs!</p>
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
                        class="h-10 w-10 bg-gray-50 cursor-pointer rounded-full flex items-center justify-center aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                    >
                        <ArrowLeftIcon
                            class="shrink-0 h-4 w-4 m-2 stroke-1.5"
                        ></ArrowLeftIcon>
                    </button>
                    <button
                        @click="handleRight"
                        class="h-10 w-10 bg-gray-50 cursor-pointer rounded-full flex items-center justify-center aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                    >
                        <ArrowRightIcon
                            class="shrink-0 h-4 w-4 m-2 stroke-1.5"
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
                                            Job ID
                                        </th>
                                    </template>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Is paid
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Paid at
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Company Name
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Status
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Application status
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Job publish date
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Job end date
                                    </th>

                                    <template
                                        v-if="
                                            getUserSettings &&
                                            getUserSettings.authorsOrPeopleShow
                                        "
                                    >
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Show Job Recruiters
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Job Recruiters
                                        </th>
                                    </template>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Country
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        State
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Job Type
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
                                                        'team.jobs.job.show',
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
                                            <span
                                                class="myPrimaryTag"
                                                :class="
                                                    post.is_paid
                                                        ? 'bg-myPrimaryLinkColor text-white'
                                                        : 'bg-myPrimaryErrorColor text-white'
                                                "
                                                >{{
                                                    post.is_paid
                                                        ? "Paid"
                                                        : "Unpaid"
                                                }}</span
                                            >
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <template v-if="post.paid_at">
                                                {{
                                                    format(
                                                        parseISO(post.paid_at),
                                                        "dd. MMMM yyyy HH:mm"
                                                    )
                                                }}
                                            </template>
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
                                                v-if="post.is_filled"
                                                class="myPrimaryTag bg-myPrimaryErrorColor text-white"
                                            >
                                                Closed for new applications
                                            </span>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <template v-if="post.started_at">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.started_at
                                                        ),
                                                        "dd. MMMM yyyy HH:mm"
                                                    )
                                                }}
                                            </template>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <template v-if="post.ended_at">
                                                {{
                                                    format(
                                                        parseISO(post.ended_at),
                                                        "dd. MMMM yyyy HH:mm"
                                                    )
                                                }}
                                            </template>
                                        </td>
                                        <template
                                            v-if="
                                                getUserSettings &&
                                                getUserSettings.authorsOrPeopleShow
                                            "
                                        >
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
                                                        class="text-xs py-1.5 px-2 flex justify-center items-center gap-1 myPrimaryTag"
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
                                                                        .charAt(
                                                                            0
                                                                        )
                                                                        .toUpperCase()
                                                                }}
                                                            </span>
                                                            <span>
                                                                {{
                                                                    author.last_name
                                                                        .charAt(
                                                                            0
                                                                        )
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
                                                                class="text-xs rounded-full py-1 pl-0 pr-1 flex justify-center items-center gap-1"
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
                                        </template>
                                        <td class="myPrimaryTableTBodyTd">
                                            <div
                                                class="flex flex-wrap justify-start items-center gap-2"
                                            >
                                                <p
                                                    v-for="jobCountry in post.countries &&
                                                    post.countries"
                                                    :key="jobCountry"
                                                    class="text-xs py-1.5 px-2 flex justify-center items-center gap-1 myPrimaryTag"
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        globe
                                                    </span>
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
                                                    class="text-xs py-1.5 px-2 flex justify-center items-center gap-1 myPrimaryTag"
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        location_on
                                                    </span>
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
                                                    class="text-xs py-1.5 px-2 flex justify-center items-center gap-1 myPrimaryTag"
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        sell
                                                    </span>
                                                    <span>
                                                        {{ jobType.name }}
                                                    </span>
                                                </p>
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
                                                            class="w-full flex justify-start text-sm leading-5 text-myPrimaryDarkGrayColor py-4 px-4 z-30 rounded hover:bg-myPrimaryLightGrayColor focus:outline-none focus:bg-myPrimaryLightGrayColor transition text-myPrimaryBrandColor"
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
