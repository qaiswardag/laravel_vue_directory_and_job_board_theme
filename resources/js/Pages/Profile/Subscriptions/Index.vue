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
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import { vueFetch } from "use-lightweight-fetch";

import {
    TrashIcon,
    PencilIcon,
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
    EyeIcon,
} from "@heroicons/vue/24/outline";

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
const modalShowResumePost = ref(false);

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

// resume subscription
// handle action
const handleResumeSubscription = function (postId) {
    modalShowResumePost.value = true;

    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 3;
    titleModal.value = `Resume Subscription`;
    descriptionModal.value = `Are you sure you want to resume this subscription?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Resume";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowResumePost.value = false;
    };

    thirdModalButtonFunction.value = function () {
        resumeSubscription(postId);
    };

    // end modal
};

const resumePostForm = useForm({});

// form action
const resumeSubscription = (postId) => {
    resumePostForm.post(route("stripe.stores.resume.subscription", [postId]), {
        preserveScroll: true,
        onSuccess: () => {
            getSubscriptions();
        },
        onError: (err) => {},
        onFinish: () => {
            modalShowResumePost.value = false;
        },
    });
};

// delete post
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
const cancelSubscriptionForm = (postId) => {
    deletePostForm.delete(
        route("stripe.stores.destroy.subscription", [postId]),
        {
            preserveScroll: true,
            onSuccess: () => {
                modalShowDeletePost.value = false;
                getSubscriptions();
            },
            onError: (err) => {},
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
                :show="modalShowResumePost"
                :type="typeModal"
                :disabled="resumePostForm.processing"
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

            <div>
                <h1 class="myPrimaryHeaderMessage">Subscriptions</h1>
            </div>

            <!-- error # start -->
            <template
                v-if="
                    !isLoadingSubscriptions &&
                    isErrorSubscriptions &&
                    !isSuccessSubscriptions
                "
            >
                <p class="myPrimaryParagraphError">
                    {{ errorSubscriptions }}
                </p>
            </template>
            <!-- error # end -->

            <!-- Loading # start -->
            <template v-if="isLoadingSubscriptions">
                <SmallUniversalSpinner
                    width="w-8"
                    height="h-8"
                    border="border-4"
                ></SmallUniversalSpinner>
            </template>
            <!-- Loading # end -->

            <template
                v-if="
                    !isLoadingSubscriptions &&
                    !isErrorSubscriptions &&
                    isSuccessSubscriptions
                "
            >
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

                    <template
                        v-if="
                            fetchedSubscriptions &&
                            fetchedSubscriptions.subscriptions &&
                            Array.isArray(
                                fetchedSubscriptions.subscriptions
                                    .subscriptionsActive
                            ) &&
                            fetchedSubscriptions.subscriptions
                                .subscriptionsActive.length > 0
                        "
                    >
                        <div class="myTableContainerPlusScrollButton">
                            <div
                                ref="scrolTableContainer"
                                class="myTableContainer"
                            >
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
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.id }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <span
                                                            v-if="post.ends_at"
                                                        >
                                                            {{
                                                                format(
                                                                    parseISO(
                                                                        post.ends_at
                                                                    ),
                                                                    "dd/MM/yyyy"
                                                                )
                                                            }}
                                                        </span>
                                                        <span
                                                            v-if="!post.ends_at"
                                                        >
                                                            Newer
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <div
                                                            class="myPrimaryTag"
                                                            :class="
                                                                post.stripe_status ===
                                                                'active'
                                                                    ? 'bg-myPrimaryLinkColor text-white'
                                                                    : 'bg-myPrimaryErrorColor text-white'
                                                            "
                                                        >
                                                            {{
                                                                post.stripe_status
                                                            }}
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.name }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            post.updated_subscription_name
                                                        }}
                                                    </td>

                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.quantity }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.created_at
                                                                ),
                                                                "dd. MMMM yyyy HH:mm"
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.updated_at
                                                                ),
                                                                "dd. MMMM yyyy HH:mm"
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <button
                                                            type="button"
                                                            @click="
                                                                handleEdit(
                                                                    post.id
                                                                )
                                                            "
                                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                        >
                                                            <PencilIcon
                                                                class="shrink-0 w-4 h-4 m-2 stroke-1.5"
                                                            ></PencilIcon>
                                                        </button>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <button
                                                            type="button"
                                                            @click="
                                                                handleCancelSubscription(
                                                                    post.id
                                                                )
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
                    </template>
                </div>
                <!-- Active subcriptions # end -->

                <!-- Canceled subcriptions # start -->
                <div class="mb-24">
                    <div class="mb-4">
                        <h2 class="mySecondaryHeader">
                            Canceled Subscriptions
                        </h2>
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

                    <template
                        v-if="
                            fetchedSubscriptions &&
                            fetchedSubscriptions.subscriptions &&
                            Array.isArray(
                                fetchedSubscriptions.subscriptions
                                    .subscriptionsCanceled
                            ) &&
                            fetchedSubscriptions.subscriptions
                                .subscriptionsCanceled.length > 0
                        "
                    >
                        <div class="myTableContainerPlusScrollButton">
                            <div
                                ref="scrolTableContainer"
                                class="myTableContainer"
                            >
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
                                                    Resume subscription
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
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.id }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <span
                                                            v-if="post.ends_at"
                                                        >
                                                            {{
                                                                format(
                                                                    parseISO(
                                                                        post.ends_at
                                                                    ),
                                                                    "dd/MM/yyyy"
                                                                )
                                                            }}
                                                        </span>
                                                        <span
                                                            v-if="!post.ends_at"
                                                        >
                                                            Newer
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <div
                                                            class="myPrimaryTag"
                                                            :class="
                                                                post.stripe_status ===
                                                                'active'
                                                                    ? 'bg-myPrimaryLinkColor text-white'
                                                                    : 'bg-myPrimaryErrorColor text-white'
                                                            "
                                                        >
                                                            {{
                                                                post.stripe_status
                                                            }}
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.name }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            post.updated_subscription_name
                                                        }}
                                                    </td>

                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.quantity }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.created_at
                                                                ),
                                                                "dd. MMMM yyyy HH:mm"
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.updated_at
                                                                ),
                                                                "dd. MMMM yyyy HH:mm"
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <button
                                                            type="button"
                                                            @click="
                                                                handleResumeSubscription(
                                                                    post.id
                                                                )
                                                            "
                                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                        >
                                                            <span
                                                                class="myMediumIcon material-symbols-outlined"
                                                            >
                                                                check
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
                    </template>
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

                    <template
                        v-if="
                            fetchedSubscriptions &&
                            fetchedSubscriptions.subscriptions &&
                            Array.isArray(
                                fetchedSubscriptions.subscriptions
                                    .subscriptionsEnded
                            ) &&
                            fetchedSubscriptions.subscriptions
                                .subscriptionsEnded.length > 0
                        "
                    >
                        <div class="myTableContainerPlusScrollButton">
                            <div
                                ref="scrolTableContainer"
                                class="myTableContainer"
                            >
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
                                                    Ended at
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
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.id }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <span
                                                            v-if="post.ends_at"
                                                        >
                                                            {{
                                                                format(
                                                                    parseISO(
                                                                        post.ends_at
                                                                    ),
                                                                    "dd/MM/yyyy"
                                                                )
                                                            }}
                                                        </span>
                                                        <span
                                                            v-if="!post.ends_at"
                                                        >
                                                            Newer
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <div
                                                            class="myPrimaryTag"
                                                            :class="
                                                                post.stripe_status ===
                                                                'active'
                                                                    ? 'bg-myPrimaryLinkColor text-white'
                                                                    : 'bg-myPrimaryErrorColor text-white'
                                                            "
                                                        >
                                                            {{
                                                                post.stripe_status
                                                            }}
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.name }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            post.updated_subscription_name
                                                        }}
                                                    </td>

                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.quantity }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.created_at
                                                                ),
                                                                "dd. MMMM yyyy HH:mm"
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.updated_at
                                                                ),
                                                                "dd. MMMM yyyy HH:mm"
                                                            )
                                                        }}
                                                    </td>
                                                </tr>
                                            </TransitionGroup>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Ended subcriptions # end -->
                <!-- Incomplete subcriptions # start -->
                <div class="mb-24">
                    <div class="mb-4">
                        <h2 class="mySecondaryHeader">
                            Incomplete Subscriptions
                        </h2>
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
                            Looks like there are no incomplete subscriptions!
                        </p>
                    </template>

                    <template
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
                    >
                        <div class="myTableContainerPlusScrollButton">
                            <div
                                ref="scrolTableContainer"
                                class="myTableContainer"
                            >
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
                                                    Edit and Complete
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
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.id }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <span
                                                            v-if="post.ends_at"
                                                        >
                                                            {{
                                                                format(
                                                                    parseISO(
                                                                        post.ends_at
                                                                    ),
                                                                    "dd/MM/yyyy"
                                                                )
                                                            }}
                                                        </span>
                                                        <span
                                                            v-if="!post.ends_at"
                                                        >
                                                            Newer
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <div
                                                            class="myPrimaryTag"
                                                            :class="
                                                                post.stripe_status ===
                                                                'active'
                                                                    ? 'bg-myPrimaryLinkColor text-white'
                                                                    : 'bg-myPrimaryErrorColor text-white'
                                                            "
                                                        >
                                                            {{
                                                                post.stripe_status
                                                            }}
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.name }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            post.updated_subscription_name
                                                        }}
                                                    </td>

                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{ post.quantity }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.created_at
                                                                ),
                                                                "dd. MMMM yyyy HH:mm"
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        {{
                                                            format(
                                                                parseISO(
                                                                    post.updated_at
                                                                ),
                                                                "dd. MMMM yyyy HH:mm"
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <button
                                                            type="button"
                                                            @click="
                                                                handleEdit(
                                                                    post.id
                                                                )
                                                            "
                                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                        >
                                                            <PencilIcon
                                                                class="shrink-0 w-4 h-4 m-2 stroke-1.5"
                                                            ></PencilIcon>
                                                        </button>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <button
                                                            type="button"
                                                            @click="
                                                                handleCancelSubscription(
                                                                    post.id
                                                                )
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
                    </template>
                </div>
            </template>

            <div
                class="flex justify-end mt-4 pb-8 bottom-0 right-0 sticky"
                v-if="
                    Object.values(deletePostForm.errors).length !== 0 ||
                    Object.values(resumePostForm.errors).length !== 0
                "
            >
                <div
                    @click="showErrorNotifications = true"
                    class="w-fit py-1 flex items-center gap-2 rounded-md px-2 cursor-pointer italic"
                >
                    <EyeIcon class="w-4 h-4 text-myPrimaryErrorColor"></EyeIcon>
                    <p
                        class="myPrimaryParagraph text-xs text-myPrimaryErrorColor py-0 my-0"
                    >
                        Show errors
                    </p>
                </div>
            </div>
            <!-- Incomplete subcriptions # end -->

            <NotificationsFixedBottom
                :listOfMessages="
                    Object.values(deletePostForm.errors) &&
                    Object.values(resumePostForm.errors)
                "
                :show="showErrorNotifications"
                @notificationsModalButton="notificationsModalButton"
            >
                <div class="flex items-center justify-start gap-2">
                    <p class="myPrimaryParagraphError">Errors</p>
                </div>
            </NotificationsFixedBottom>
        </LoggedInLayout>
    </MainLayout>
</template>
