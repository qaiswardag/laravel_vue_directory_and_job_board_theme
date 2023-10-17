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
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import UserTag from "@/Components/Users/UserTag.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import FullScreenPageSpinner from "@/Components/Loaders/FullScreenPageSpinner.vue";

import { vueFetch } from "use-lightweight-fetch";

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
    Squares2X2Icon,
    UserIcon,
} from "@heroicons/vue/20/solid";

import { TrashIcon, PencilIcon } from "@heroicons/vue/24/outline";

// get images
const {
    handleData: handleGetSubscriptions,
    fetchedData: fetchedSubscriptions,
    isError: isErrorSubscriptions,
    error: errorSubscriptions,
    errors: errorsSubscriptions,
    isLoading: isLoadingSubscriptions,
    isSuccess: isSuccessSubscriptions,
} = vueFetch();

const getSubscriptions = function () {
    handleGetSubscriptions(route("stripe.api.internal.subscriptions.index"));
};

onMounted(() => {
    getSubscriptions();
});

const breadcrumbsLinks = [
    {
        label: "Your Profile",
        route: {
            name: "profile.show",
            parameters: null,
        },
    },
    {
        label: "Subscriptions",
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
const handleDelete = function (postId) {
    modalShowDeletePost.value = true;

    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 3;
    titleModal.value = `Delete Subscription`;
    descriptionModal.value = `Are you sure you want to delete this subscription?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete Store";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePost.value = false;
    };

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
        route("stripe.stores.destroy.subscription", [postId]),
        {
            preserveScroll: true,
            onSuccess: () => (modalShowDeletePost.value = false),
            onError: (err) => {},
            onFinish: (log) => {
                modalShowDeletePost.value = false;
            },
        }
    );

    getSubscriptions();
};

// handle action
const handleEdit = function (postId) {
    router.get(route("stripe.stores.edit.subscription", [postId]));
};

// handle action
</script>

<template>
    <MainLayout>
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
                <h2 class="myPrimaryMainPageHeader">Subscriptions</h2>
            </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <FullScreenPageSpinner
                v-if="isLoadingSubscriptions && !isErrorSubscriptions"
            ></FullScreenPageSpinner>

            <h1 class="myPrimaryHeaderMessage">Subscriptions</h1>

            <template
                v-if="
                    fetchedSubscriptions &&
                    fetchedSubscriptions.subscriptions &&
                    Array.isArray(
                        fetchedSubscriptions.subscriptions.subscriptionsActive
                    ) &&
                    fetchedSubscriptions.subscriptions.subscriptionsActive
                        .length === 0
                "
            >
                <p class="myPrimaryParagraph">
                    Looks like there are no active subscriptions!
                </p>
            </template>

            <div class="my-20 p-8 px-4 border-2 border-red-300 rounded-lg">
                <p class="my-4">
                    fetchedSubscriptions subscriptionsActive:
                    {{
                        JSON.stringify(
                            fetchedSubscriptions?.subscriptions
                                ?.subscriptionsActive
                        )
                    }}
                </p>
                <p class="my-4">
                    fetchedSubscriptions subscriptionsIncomplete:
                    {{
                        JSON.stringify(
                            fetchedSubscriptions?.subscriptions
                                ?.subscriptionsIncomplete
                        )
                    }}
                </p>
                <p class="my-4">
                    fetchedSubscriptions subscriptionsEnded:
                    {{
                        JSON.stringify(
                            fetchedSubscriptions?.subscriptions
                                ?.subscriptionsEnded
                        )
                    }}
                </p>
                <p class="my-4">
                    fetchedSubscriptions subscriptionsCanceled:
                    {{
                        JSON.stringify(
                            fetchedSubscriptions?.subscriptions
                                ?.subscriptionsCanceled
                        )
                    }}
                </p>
            </div>

            <div
                v-if="
                    fetchedSubscriptions &&
                    fetchedSubscriptions.subscriptions &&
                    Array.isArray(
                        fetchedSubscriptions.subscriptions.subscriptionsActive
                    ) &&
                    fetchedSubscriptions.subscriptions.subscriptionsActive
                        .length > 0
                "
                class="myTableContainerPlusScrollButton"
            >
                <div ref="scrolTableContainer" class="myTableContainer">
                    <div class="myTableSubContainer">
                        <table class="myPrimaryTable" aria-describedby="index">
                            <thead class="myPrimaryTableTHead">
                                <tr class="myPrimaryTableTr">
                                    <th scope="col" class="myPrimaryTableTh">
                                        ID
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        status — ends at
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        name
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        updated_subscription_name
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        stripe_id
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        stripe_status
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        stripe_price
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        quantity
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        created_at
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        updated_at
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Edit
                                    </th>
                                    <th scope="col" class="myPrimaryTableTh">
                                        Cancel
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="myPrimaryTableTBody">
                                <TransitionGroup name="table">
                                    <tr
                                        class="myPrimaryTableTBodyTr"
                                        v-for="post in fetchedSubscriptions
                                            .subscriptions.subscriptionsActive"
                                        :key="post.id"
                                    >
                                        <td class="myPrimaryTableTBodyTd">
                                            {{ post.id }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            <span v-if="post.ends_at">
                                                {{
                                                    format(
                                                        parseISO(post.ends_at),
                                                        "dd/MM/yyyy"
                                                    )
                                                }}
                                            </span>
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{ post.name }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{ post.updated_subscription_name }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{ post.stripe_id }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{ post.stripe_status }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{ post.stripe_price }}
                                        </td>
                                        <td class="myPrimaryTableTBodyTd">
                                            {{ post.quantity }}
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
                                            {{
                                                format(
                                                    parseISO(post.updated_at),
                                                    "dd/MM/yyyy HH:mm"
                                                )
                                            }}
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
                                                @click="handleDelete(post.id)"
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
        </LoggedInLayout>
    </MainLayout>
</template>
