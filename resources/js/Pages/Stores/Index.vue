<script setup>
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
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";

import {
    MapPinIcon,
    TagIcon,
    TrashIcon,
    Squares2X2Icon,
    UserIcon,
    PencilIcon,
} from "@heroicons/vue/24/outline";
// loading status for props and view
const isLoaded = ref(false);

router.on("start", () => {
    isLoaded.value = true;
});
router.on("finish", () => {
    isLoaded.value = false;
});

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
        label: "All Stores",
        route: {
            name: "team.stores.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
];

const routesArray = [
    {
        label: "All Stores",
        route: {
            name: "team.stores.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
    {
        label: "Create Store",
        route: {
            name: "team.stores.create",
            parameters: [props.currentUserTeam.reference_id],
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
    descriptionModal.value = `Are you sure you want to delete store with title ${post.title}?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete Store";

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
        route("team.stores.store.destroy", [postId, props.currentUserTeam.id]),
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
        route("team.stores.store.edit", [
            props.currentUserTeam.reference_id,
            postId,
        ])
    );
};

// form
const searchForm = useForm({
    search_query: "",
});

const handleSearch = function () {
    searchForm.get(
        route("team.stores.index", [props.currentUserTeam.reference_id]),
        {
            preserveScroll: true,
            onSuccess: () => {},
            onError: (err) => {},
            onFinish: () => {},
        }
    );
};

onMounted(() => {
    if (props.oldInput?.search_query) {
        searchForm.search_query = props.oldInput.search_query;
    }
});
</script>

<template>
    <template v-if="isLoaded">
        <FullScreenSpinner></FullScreenSpinner>
    </template>
    <LoggedInLayout>
        <Head title="Stores" />
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
                Stores for
                {{ $page.props.user && $page.props.currentUserTeam.name }}
            </h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <CardHeadings :routesArray="routesArray">
            <template #title
                >Stores for
                {{ $page.props.user && $page.props.user.current_team.name }}
            </template>
            <template #subTitle
                >Lorem ipsum dolor sit amet consectetur adipisicing elit quam
                corrupti consectetur.
            </template>
            <template #buttons>
                <Link
                    class="myPrimaryButton"
                    type="button"
                    :href="
                        route(
                            'team.stores.create',
                            currentUserTeam.reference_id
                        )
                    "
                >
                    Create Store
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
            <h1 class="myPrimaryHeaderMessage">No Stores</h1>
            <p class="myPrimaryParagraph">Looks like there are no stores!</p>
        </template>

        <!-- table start -->
        <div v-if="posts && posts.data.length >= 1" class="myTableContainer">
            <div class="myTableSubContainer">
                <table class="myPrimaryTable">
                    <caption class="myPrimaryTableCaption">
                        {{
                            $page.props.user &&
                            $page.props.user.current_team.name
                        }}
                    </caption>
                    <thead class="myPrimaryTableTHead">
                        <tr class="myPrimaryTableTr">
                            <th scope="col" class="myPrimaryTableTh">
                                Thumbnail
                            </th>
                            <th scope="col" class="myPrimaryTableTh">Title</th>
                            <th scope="col" class="myPrimaryTableTh">
                                Store ID
                            </th>
                            <th scope="col" class="myPrimaryTableTh">
                                Team Name
                            </th>
                            <th scope="col" class="myPrimaryTableTh">Status</th>
                            <th scope="col" class="myPrimaryTableTh">
                                Show Authors
                            </th>
                            <th scope="col" class="myPrimaryTableTh">
                                Authors
                            </th>
                            <th scope="col" class="myPrimaryTableTh">State</th>
                            <th scope="col" class="myPrimaryTableTh">
                                Categories
                            </th>
                            <th scope="col" class="myPrimaryTableTh">Tags</th>

                            <th scope="col" class="myPrimaryTableTh">
                                Updated By
                            </th>

                            <th scope="col" class="myPrimaryTableTh">
                                Updated Date
                            </th>

                            <th scope="col" class="myPrimaryTableTh">
                                Created Date
                            </th>
                            <th scope="col" class="myPrimaryTableTh">Edit</th>
                            <th scope="col" class="myPrimaryTableTh">Delete</th>
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
                                            Array.isArray(post.cover_images) &&
                                            post.cover_images.length !== 0
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
                                            route('team.posts.post.show', [
                                                $page.props.user.current_team
                                                    .reference_id,
                                                post.id,
                                                post.slug,
                                            ])
                                        "
                                    >
                                        <span class="text-myPrimaryLinkColor">
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
                                        $page.props.user.current_team.name
                                    }}
                                </td>
                                <td class="myPrimaryTableTBodyTd">
                                    <span
                                        :class="
                                            post.published
                                                ? 'bg-green-50 text-myPrimaryLinkColor'
                                                : 'bg-red-100 text-myPrimaryErrorColor'
                                        "
                                        class="inline-flex rounded-full px-2 font-medium leading-5 text-green-800"
                                        >{{
                                            post.published
                                                ? "Published"
                                                : "Unpublished"
                                        }}</span
                                    >
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <span
                                        :class="
                                            post.show_author
                                                ? 'bg-green-50 text-myPrimaryLinkColor'
                                                : 'bg-red-100 text-myPrimaryErrorColor'
                                        "
                                        class="inline-flex rounded-full px-2 font-medium leading-5 text-green-800"
                                        >{{
                                            post.show_author ? "Show" : "Hide"
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
                                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-0 px-1 flex justify-center items-center gap-1"
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
                                                style="font-size: 9px; gap: 2px"
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
                                                    style="font-size: 10px"
                                                    class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1 pl-0 pr-1 flex justify-center items-center gap-1"
                                                >
                                                    <span>
                                                        {{ author.first_name }}
                                                        {{ author.last_name }}
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
                                            v-for="storeState in post.states &&
                                            post.states"
                                            :key="storeState"
                                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1 px-2 flex justify-center items-center gap-1"
                                        >
                                            <MapPinIcon
                                                class="w-3 h-3 stroke-1"
                                            ></MapPinIcon>
                                            <span>
                                                {{ storeState.name }}
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
                                            Array.isArray(post.categories) &&
                                            post.categories.sort((a, b) => {
                                                const nameA = a.name;
                                                const nameB = b.name;

                                                if (nameA < nameB) {
                                                    return -1;
                                                } else if (nameA > nameB) {
                                                    return 1;
                                                } else {
                                                    return 0;
                                                }
                                            })"
                                            :key="category"
                                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1 px-2 flex justify-center items-center gap-1"
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
                                            class="text-xs rounded-full bg-myPrimaryLightGrayColor py-1 px-2 flex justify-center items-center gap-1"
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
                                    <div
                                        v-if="post.updatedBy !== null"
                                        class="flex items-center gap-2"
                                    >
                                        <!-- user photo - start -->

                                        <div
                                            v-if="
                                                post.updatedBy
                                                    .profile_photo_path !== null
                                            "
                                        >
                                            <div class="h-8 w-8 flex-shrink-0">
                                                <img
                                                    class="object-cover h-8 w-8 rounded-full"
                                                    :src="`/storage/${post.updatedBy.profile_photo_path}`"
                                                    alt="avatar"
                                                />
                                            </div>
                                        </div>

                                        <div
                                            v-if="
                                                post.updatedBy
                                                    .profile_photo_path === null
                                            "
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-myPrimaryBrandColor flex justify-center items-center font-normal text-white text-xs"
                                        >
                                            {{
                                                post.updatedBy.first_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                            {{
                                                post.updatedBy.last_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>
                                        <span
                                            class="flex flex-col items-left gap-1 myPrimaryParagraph"
                                        >
                                            <span>
                                                {{ post.updatedBy.first_name }}
                                                {{ post.updatedBy.last_name }}
                                            </span>
                                        </span>
                                    </div>
                                    <!-- user photo - end -->
                                    <span v-if="post.updatedBy === null">
                                        Unknown
                                    </span>
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
                                    <button
                                        type="button"
                                        @click="handleEdit(post.id)"
                                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                                    >
                                        <PencilIcon
                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                        ></PencilIcon>
                                    </button>
                                </td>
                                <td class="myPrimaryTableTBodyTd">
                                    <button
                                        type="button"
                                        @click="handleDelete(post.id, post)"
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
        <!-- table end -->
        <Pagination :links="posts?.links ? posts.links : []"></Pagination>
    </LoggedInLayout>
</template>
