<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import CardHeadings from "@/Components/Actions/CardHeadings.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { onMounted, ref, watch } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import { parseISO, format } from "date-fns";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import UserTag from "@/Components/Users/UserTag.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { useStore } from "vuex";
import { delay } from "@/helpers/delay";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";

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

const store = useStore();

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

const getSubscriptions = async function () {
    store.commit("user/setIsLoading", true);

    await handleGetSubscriptions(
        route("stripe.api.internal.subscriptions.index")
    );
    store.commit("user/setIsLoading", false);
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
const handleCancelSubscription = function (postId) {
    modalShowDeletePost.value = true;

    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 3;
    titleModal.value = `Cancel Subscription`;
    descriptionModal.value = `Are you sure you want to cancel this subscription?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Cancel";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePost.value = false;
    };

    thirdModalButtonFunction.value = function () {
        cancelSubscriptionForm(postId);
    };

    // end modal
};
// form
const deletePostForm = useForm({});

// form action
const cancelSubscriptionForm = async (postId) => {
    deletePostForm.delete(
        await route("stripe.stores.destroy.subscription", [postId]),
        {
            preserveScroll: true,
            onSuccess: () => {
                modalShowDeletePost.value = false;
                getSubscriptions();
            },
            onError: (err) => {
                console.error("Unable canceling subscription", err);
            },
            onFinish: () => {},
        }
    );
};

// handle action
const handleEdit = function (postId) {
    router.get(route("stripe.stores.edit.subscription", [postId]));
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
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

            <h1 class="myPrimaryHeaderMessage">Subscriptions</h1>

            <!-- Active subcriptions # start -->
            <div class="mb-24">
                <div class="mb-4">
                    <h2 class="mySecondaryHeader">Active Subscriptions</h2>
                </div>
                <template
                    v-if="
                        !fetchedSubscriptions?.subscriptions
                            ?.subscriptionsActive ||
                        (Array.isArray(
                            fetchedSubscriptions.subscriptions
                                .subscriptionsActive
                        ) &&
                            fetchedSubscriptions.subscriptions
                                .subscriptionsActive.length === 0)
                    "
                >
                    <p class="myPrimaryParagraph">
                        Looks like there are no active subscriptions!
                    </p>
                </template>

                <div
                    v-if="
                        fetchedSubscriptions &&
                        fetchedSubscriptions.subscriptions &&
                        Array.isArray(
                            fetchedSubscriptions.subscriptions
                                .subscriptionsActive
                        ) &&
                        fetchedSubscriptions.subscriptions.subscriptionsActive
                            .length > 0
                    "
                    class="myTableContainerPlusScrollButton"
                >
                    <div ref="scrolTableContainer" class="myTableContainer">
                        <div class="myTableSubContainer">
                            <table
                                class="myPrimaryTable"
                                aria-describedby="index"
                            >
                                <thead class="myPrimaryTableTHead">
                                    <tr class="myPrimaryTableTr">
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            ID
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Ends at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Status
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated name
                                        </th>

                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Quantity
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Created at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Edit
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Cancel
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="myPrimaryTableTBody">
                                    <TransitionGroup name="table">
                                        <tr
                                            class="myPrimaryTableTBodyTr"
                                            v-for="post in fetchedSubscriptions
                                                .subscriptions
                                                .subscriptionsActive"
                                            :key="post.id"
                                        >
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.id }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <span v-if="post.ends_at">
                                                    {{
                                                        format(
                                                            parseISO(
                                                                post.ends_at
                                                            ),
                                                            "dd/MM/yyyy"
                                                        )
                                                    }}
                                                </span>
                                                <span v-if="!post.ends_at">
                                                    Newer
                                                </span>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.stripe_status }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.name }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    post.updated_subscription_name
                                                }}
                                            </td>

                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.quantity }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.created_at
                                                        ),
                                                        "dd/MM/yyyy HH:mm"
                                                    )
                                                }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.updated_at
                                                        ),
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
                                                    @click="
                                                        handleCancelSubscription(
                                                            post.id
                                                        )
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
            </div>
            <!-- Active subcriptions # end -->
            <!-- Canceled subcriptions # start -->
            <div class="mb-24">
                <div class="mb-4">
                    <h2 class="mySecondaryHeader">Canceled Subscriptions</h2>
                </div>
                <template
                    v-if="
                        !fetchedSubscriptions?.subscriptions
                            ?.subscriptionsCanceled ||
                        (Array.isArray(
                            fetchedSubscriptions.subscriptions
                                .subscriptionsCanceled
                        ) &&
                            fetchedSubscriptions.subscriptions
                                .subscriptionsCanceled.length === 0)
                    "
                >
                    <p class="myPrimaryParagraph">
                        Looks like there are no canceled subscriptions!
                    </p>
                </template>

                <div
                    v-if="
                        fetchedSubscriptions &&
                        fetchedSubscriptions.subscriptions &&
                        Array.isArray(
                            fetchedSubscriptions.subscriptions
                                .subscriptionsCanceled
                        ) &&
                        fetchedSubscriptions.subscriptions.subscriptionsCanceled
                            .length > 0
                    "
                    class="myTableContainerPlusScrollButton"
                >
                    <div ref="scrolTableContainer" class="myTableContainer">
                        <div class="myTableSubContainer">
                            <table
                                class="myPrimaryTable"
                                aria-describedby="index"
                            >
                                <thead class="myPrimaryTableTHead">
                                    <tr class="myPrimaryTableTr">
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            ID
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Ends at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Status
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated name
                                        </th>

                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Quantity
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Created at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Reactivate Subscription
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Cancel
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="myPrimaryTableTBody">
                                    <TransitionGroup name="table">
                                        <tr
                                            class="myPrimaryTableTBodyTr"
                                            v-for="post in fetchedSubscriptions
                                                .subscriptions
                                                .subscriptionsCanceled"
                                            :key="post.id"
                                        >
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.id }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <span v-if="post.ends_at">
                                                    {{
                                                        format(
                                                            parseISO(
                                                                post.ends_at
                                                            ),
                                                            "dd/MM/yyyy"
                                                        )
                                                    }}
                                                </span>
                                                <span v-if="!post.ends_at">
                                                    Newer
                                                </span>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.stripe_status }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.name }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    post.updated_subscription_name
                                                }}
                                            </td>

                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.quantity }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.created_at
                                                        ),
                                                        "dd/MM/yyyy HH:mm"
                                                    )
                                                }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.updated_at
                                                        ),
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
                                                    <CheckIcon
                                                        class="shrink-0 w-4 h-4 m-2 stroke-2"
                                                    ></CheckIcon>
                                                </button>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <button
                                                    type="button"
                                                    @click="
                                                        handleCancelSubscription(
                                                            post.id
                                                        )
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
            </div>
            <!-- Canceled subcriptions # end -->
            <!-- Ended subcriptions # start -->
            <div class="mb-24">
                <div class="mb-4">
                    <h2 class="mySecondaryHeader">Ended Subscriptions</h2>
                </div>
                <template
                    v-if="
                        !fetchedSubscriptions?.subscriptions
                            ?.subscriptionsEnded ||
                        (Array.isArray(
                            fetchedSubscriptions.subscriptions
                                .subscriptionsEnded
                        ) &&
                            fetchedSubscriptions.subscriptions
                                .subscriptionsEnded.length === 0)
                    "
                >
                    <p class="myPrimaryParagraph">
                        Looks like there are no ended subscriptions!
                    </p>
                </template>

                <div
                    v-if="
                        fetchedSubscriptions &&
                        fetchedSubscriptions.subscriptions &&
                        Array.isArray(
                            fetchedSubscriptions.subscriptions
                                .subscriptionsEnded
                        ) &&
                        fetchedSubscriptions.subscriptions.subscriptionsEnded
                            .length > 0
                    "
                    class="myTableContainerPlusScrollButton"
                >
                    <div ref="scrolTableContainer" class="myTableContainer">
                        <div class="myTableSubContainer">
                            <table
                                class="myPrimaryTable"
                                aria-describedby="index"
                            >
                                <thead class="myPrimaryTableTHead">
                                    <tr class="myPrimaryTableTr">
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            ID
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Ends at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Status
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated name
                                        </th>

                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Quantity
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Created at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Reactivate Subscription
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Cancel
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="myPrimaryTableTBody">
                                    <TransitionGroup name="table">
                                        <tr
                                            class="myPrimaryTableTBodyTr"
                                            v-for="post in fetchedSubscriptions
                                                .subscriptions
                                                .subscriptionsEnded"
                                            :key="post.id"
                                        >
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.id }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <span v-if="post.ends_at">
                                                    {{
                                                        format(
                                                            parseISO(
                                                                post.ends_at
                                                            ),
                                                            "dd/MM/yyyy"
                                                        )
                                                    }}
                                                </span>
                                                <span v-if="!post.ends_at">
                                                    Newer
                                                </span>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.stripe_status }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.name }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    post.updated_subscription_name
                                                }}
                                            </td>

                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.quantity }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.created_at
                                                        ),
                                                        "dd/MM/yyyy HH:mm"
                                                    )
                                                }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.updated_at
                                                        ),
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
                                                    <CheckIcon
                                                        class="shrink-0 w-4 h-4 m-2 stroke-2"
                                                    ></CheckIcon>
                                                </button>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <button
                                                    type="button"
                                                    @click="
                                                        handleCancelSubscription(
                                                            post.id
                                                        )
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
            </div>
            <div
                class="flex justify-end mt-4 pb-8 bottom-0 right-0 sticky"
                v-if="Object.values(deletePostForm.errors).length !== 0"
            >
                <div
                    @click="showErrorNotifications = true"
                    class="w-fit py-1 flex items-center gap-2 rounded-md px-2 cursor-pointer italic"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4 text-myPrimaryErrorColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    <p
                        class="myPrimaryParagraph text-xs text-myPrimaryErrorColor py-0 my-0"
                    >
                        Show
                        {{ Object.values(deletePostForm.errors).length }}
                        {{
                            Object.values(deletePostForm.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </div>
            <!-- Ended subcriptions # end -->
            <!-- Incomplete subcriptions # start -->
            <div class="mb-24">
                <div class="mb-4">
                    <h2 class="mySecondaryHeader">Incomplete Subscriptions</h2>
                </div>
                <template
                    v-if="
                        !fetchedSubscriptions?.subscriptions
                            ?.subscriptionsIncomplete ||
                        (Array.isArray(
                            fetchedSubscriptions.subscriptions
                                .subscriptionsIncomplete
                        ) &&
                            fetchedSubscriptions.subscriptions
                                .subscriptionsIncomplete.length === 0)
                    "
                >
                    <p class="myPrimaryParagraph">
                        Looks like there are no ended incomplete subscriptions!
                    </p>
                </template>

                <div
                    v-if="
                        fetchedSubscriptions &&
                        fetchedSubscriptions.subscriptions &&
                        Array.isArray(
                            fetchedSubscriptions.subscriptions
                                .subscriptionsIncomplete
                        ) &&
                        fetchedSubscriptions.subscriptions
                            .subscriptionsIncomplete.length > 0
                    "
                    class="myTableContainerPlusScrollButton"
                >
                    <div ref="scrolTableContainer" class="myTableContainer">
                        <div class="myTableSubContainer">
                            <table
                                class="myPrimaryTable"
                                aria-describedby="index"
                            >
                                <thead class="myPrimaryTableTHead">
                                    <tr class="myPrimaryTableTr">
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            ID
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Ends at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Status
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated name
                                        </th>

                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Quantity
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Created at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Updated at
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Reactivate Subscription
                                        </th>
                                        <th
                                            scope="col"
                                            class="myPrimaryTableTh"
                                        >
                                            Cancel
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="myPrimaryTableTBody">
                                    <TransitionGroup name="table">
                                        <tr
                                            class="myPrimaryTableTBodyTr"
                                            v-for="post in fetchedSubscriptions
                                                .subscriptions
                                                .subscriptionsIncomplete"
                                            :key="post.id"
                                        >
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.id }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <span v-if="post.ends_at">
                                                    {{
                                                        format(
                                                            parseISO(
                                                                post.ends_at
                                                            ),
                                                            "dd/MM/yyyy"
                                                        )
                                                    }}
                                                </span>
                                                <span v-if="!post.ends_at">
                                                    Newer
                                                </span>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.stripe_status }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.name }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    post.updated_subscription_name
                                                }}
                                            </td>

                                            <td class="myPrimaryTableTBodyTd">
                                                {{ post.quantity }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.created_at
                                                        ),
                                                        "dd/MM/yyyy HH:mm"
                                                    )
                                                }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{
                                                    format(
                                                        parseISO(
                                                            post.updated_at
                                                        ),
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
                                                    <CheckIcon
                                                        class="shrink-0 w-4 h-4 m-2 stroke-2"
                                                    ></CheckIcon>
                                                </button>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <button
                                                    type="button"
                                                    @click="
                                                        handleCancelSubscription(
                                                            post.id
                                                        )
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
            </div>
            <div
                class="flex justify-end mt-4 pb-8 bottom-0 right-0 sticky"
                v-if="Object.values(deletePostForm.errors).length !== 0"
            >
                <div
                    @click="showErrorNotifications = true"
                    class="w-fit py-1 flex items-center gap-2 rounded-md px-2 cursor-pointer italic"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4 text-myPrimaryErrorColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    <p
                        class="myPrimaryParagraph text-xs text-myPrimaryErrorColor py-0 my-0"
                    >
                        Show
                        {{ Object.values(deletePostForm.errors).length }}
                        {{
                            Object.values(deletePostForm.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </div>
            <!-- Incomplete subcriptions # end -->
            <NotificationsFixedBottom
                :listOfMessages="Object.values(deletePostForm.errors)"
                :show="showErrorNotifications"
                @notificationsModalButton="notificationsModalButton"
            >
                <div class="flex items-center justify-start gap-2">
                    <p class="myPrimaryParagraphError">
                        {{ Object.values(deletePostForm.errors).length }}
                        {{
                            Object.values(deletePostForm.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </NotificationsFixedBottom>
        </LoggedInLayout>
    </MainLayout>
</template>
