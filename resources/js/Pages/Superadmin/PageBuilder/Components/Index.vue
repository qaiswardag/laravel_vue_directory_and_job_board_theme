<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import SearchBarWithOptions from "@/Components/SearchBars/SearchBarWithOptions.vue";
import { onMounted, ref, watch } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import InputError from "@/Components/Forms/InputError.vue";
import { parseISO, format } from "date-fns";
import CardHeadings from "@/Components/Actions/CardHeadings.vue";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";
import UserTag from "@/Components/Users/UserTag.vue";

import {
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
} from "@headlessui/vue";
import {
    ArrowPathRoundedSquareIcon,
    ArrowsRightLeftIcon,
    MinusIcon,
    PencilIcon,
    PlusIcon,
    TrashIcon,
    Squares2X2Icon,
} from "@heroicons/vue/24/outline";

// loading status for props and view
const isLoaded = ref(false);

router.on("start", () => {
    isLoaded.value = true;
});
router.on("finish", () => {
    isLoaded.value = false;
});

const breadcrumbsLinks = [
    {
        label: "Admin Dashboard",
        route: {
            name: "admin.dashboard",
            parameters: null,
        },
    },
    { label: "Components" },
];

const props = defineProps({
    currentUserTeam: {
        required: true,
    },
    components: {
        required: true,
    },
    results: {
        required: false,
    },
    oldInput: {
        search_query: {
            required: false,
        },
        selected_category: {
            required: false,
        },
    },
});

const modalShowDeleteComponent = ref(false);
const showModalEditComponentTeamAuth = ref(false);
//
// use dynamic model
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

const checkUserTeamAuthorization = function () {
    // set modal standards
    showModalEditComponentTeamAuth.value = true;
    typeModal.value = "danger";
    gridColumnModal.value = 2;
    titleModal.value = "No Team selected";
    descriptionModal.value =
        "Sorry, you are forbidden from performing this action. Only team members who belong to unique teams can create, update or delete this resource. Please try switching team or contact IT support.";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = null;

    // handle click
    firstModalButtonFunction.value = function () {
        // set open modal
        showModalEditComponentTeamAuth.value = false;
    };
    // end modal
};

const handleEdit = function (componentID) {
    if (props.currentUserTeam === null || props.currentUserTeam === undefined) {
        checkUserTeamAuthorization();
        return;
    }

    router.get(
        route("admin.components.component.edit", [
            props.currentUserTeam &&
            props.currentUserTeam.id !== null &&
            props.currentUserTeam.id !== undefined
                ? props.currentUserTeam.id
                : 0,
            componentID,
        ])
    );
};

// handle action
const handleDelete = function (postId, post) {
    modalShowDeleteComponent.value = true;

    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 3;
    titleModal.value = `Delete Component?`;
    descriptionModal.value = `Are you sure you want to delete this component?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete Post";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeleteComponent.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        deleteComponent(postId);
    };
    // end modal
};

// form
const deletePostForm = useForm({});

const deleteComponent = function (componentId) {
    deletePostForm.delete(
        route("admin.components.component.destroy", [
            componentId,
            props.currentUserTeam.id,
        ]),
        {
            preserveScroll: true,
            onSuccess: () => (modalShowDeleteComponent.value = false),
            onError: (err) => {},
            onFinish: (log) => {
                modalShowDeleteComponent.value = false;
            },
        }
    );
};

// handle search
const handleSearch = function () {
    search();
};

const searchCategories = ref(["id", "title"]);

const searchForm = useForm({
    search_query: "",
    selected_category: searchCategories.value[1],
});

const selectedUpdated = function (newSelect) {
    searchForm.selected_category = newSelect;
};

const search = () => {
    searchForm.get(route("admin.components"), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (err) => {},
        onFinish: () => {},
    });
};

onMounted(() => {
    if (props.oldInput?.search_query) {
        searchForm.search_query = props.oldInput.search_query;
    }
    if (props.oldInput?.selected_category) {
        searchForm.selected_category = props.oldInput.selected_category;
    }
});

const routesArray = [
    {
        label: "Admin Dashboard ",
        route: {
            name: "admin.dashboard",
        },
    },
    {
        label: "Create Compoenent",
        route: {
            name: "admin.components.component.create",
            parameters: props.currentUserTeam?.id,
        },
    },
];
</script>

<template>
    <template v-if="isLoaded">
        <FullScreenSpinner></FullScreenSpinner>
    </template>
    <LoggedInLayout>
        <DynamicModal
            :show="modalShowDeleteComponent"
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
        <Head title="Manage Components" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">List of Components</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <template #description></template>
        <CardHeadings :routesArray="routesArray">
            <template #title>Components </template>
            <template #subTitle
                >Lorem ipsum dolor sit amet consectetur adipisicing elit quam
                corrupti consectetur.
            </template>

            <template #buttons>
                <button
                    v-if="props.currentUserTeam === null"
                    class="myPrimaryButton"
                    type="button"
                    @click="checkUserTeamAuthorization"
                >
                    Create Component
                </button>
                <Link
                    class="myPrimaryButton"
                    type="button"
                    prop
                    v-if="props.currentUserTeam !== null"
                    :href="
                        route(
                            'admin.components.component.create',
                            props.currentUserTeam &&
                                props.currentUserTeam.id !== null &&
                                props.currentUserTeam.id !== undefined
                                ? props.currentUserTeam.id
                                : 0
                        )
                    "
                >
                    Create Component
                </Link>
            </template>
        </CardHeadings>
        <form @submit.prevent="handleSearch">
            <!-- search bar component - start -->
            <SearchBarWithOptions
                v-model="searchForm.search_query"
                @selectedUpdated="selectedUpdated"
                :currentSelect="searchForm.selected_category"
                @firstButtonClick="handleSearch"
                :categories="searchCategories"
                placeholderButton="Search"
                placeholderInput="Search.."
            ></SearchBarWithOptions>
            <!-- search bar component - ens -->
        </form>

        <div class="myTableContainer">
            <div class="myTableSubContainer">
                <table class="myPrimaryTable">
                    <caption class="myPrimaryTableCaption">
                        <p class="myPrimaryParagraph">Result {{ results }}</p>
                    </caption>
                    <thead class="myPrimaryTableTHead">
                        <tr class="myPrimaryTableTr">
                            <th scope="col" class="myPrimaryTableTh">Image</th>
                            <th scope="col" class="myPrimaryTableTh">Title</th>
                            <th scope="col" class="myPrimaryTableTh">ID</th>
                            <th scope="col" class="myPrimaryTableTh">
                                Published
                            </th>

                            <th scope="col" class="myPrimaryTableTh">
                                Categories
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
                            <th scope="col" class="myPrimaryTableTh">Edit</th>
                            <th scope="col" class="myPrimaryTableTh">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="myPrimaryTableTBody">
                        <TransitionGroup name="table">
                            <tr
                                class="myPrimaryTableTBodyTr"
                                v-for="component in components.data"
                                :key="component.id"
                            >
                                <td class="myPrimaryTableTBodyTd">
                                    <div
                                        v-if="
                                            Array.isArray(
                                                component.cover_images
                                            ) &&
                                            component.cover_images.length !==
                                                0 &&
                                            component.cover_images
                                        "
                                    >
                                        <ThumbnailSmallImageSlider
                                            :images="component.cover_images"
                                            imageSize="medium_path"
                                            imageHeight="h-28"
                                            imageWidth="w-28"
                                            :roundedFull="true"
                                        ></ThumbnailSmallImageSlider>
                                    </div>
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{ component.title }}
                                </td>
                                <td class="myPrimaryTableTBodyTd">
                                    {{ component.id }}
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <span
                                        class="myPrimaryTag"
                                        :class="
                                            component.published
                                                ? 'bg-myPrimaryLinkColor text-white'
                                                : 'bg-myPrimaryErrorColor text-white'
                                        "
                                        >{{
                                            component.published
                                                ? "Published"
                                                : "Unpublished"
                                        }}</span
                                    >
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <div
                                        class="flex flex-wrap justify-start items-center gap-2"
                                    >
                                        <p
                                            v-for="category in component.categories &&
                                            Array.isArray(
                                                component.categories
                                            ) &&
                                            component.categories.sort(
                                                (a, b) => {
                                                    const nameA = a.name;
                                                    const nameB = b.name;

                                                    if (nameA < nameB) {
                                                        return -1;
                                                    } else if (nameA > nameB) {
                                                        return 1;
                                                    } else {
                                                        return 0;
                                                    }
                                                }
                                            )"
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
                                    <UserTag
                                        :user="component.updatedBy"
                                    ></UserTag>
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{
                                        format(
                                            parseISO(component.updated_at),
                                            "dd/MM/yyyy HH:mm"
                                        )
                                    }}
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{
                                        format(
                                            parseISO(component.created_at),
                                            "dd/MM/yyyy HH:mm"
                                        )
                                    }}
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <button
                                        type="button"
                                        @click="handleEdit(component.id)"
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
                                        @click="
                                            handleDelete(component.id, post)
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
        <Pagination :links="components.links"></Pagination>

        <DynamicModal
            :show="showModalEditComponentTeamAuth"
            :type="typeModal"
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
        </DynamicModal>
    </LoggedInLayout>
</template>