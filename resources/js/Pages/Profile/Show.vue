<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { router } from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SelectPaymentMethod from "@/Pages/Stripe/SelectPaymentMethod.vue";

import {
    ArrowPathIcon,
    BanknotesIcon,
    CreditCardIcon,
} from "@heroicons/vue/24/outline";
import { ref } from "vue";
import SectionBorder from "../../Components/Sections/SectionBorder.vue";

const props = defineProps({
    sessions: Array,
    confirmsTwoFactorAuthentication: Boolean,
    //
    //
    intent: {
        required: false,
    },
    publishableKey: {
        required: false,
    },
});

const breadcrumbsLinks = [
    {
        label: "Your Profile",
        route: {
            name: "profile.show",
            parameters: null,
        },
    },
];

const modalShowUpdatePaymentMethod = ref(false);

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

const updatePaymentMethod = function () {
    modalShowUpdatePaymentMethod.value = true;

    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 3;
    titleModal.value = `Update payment methods`;
    descriptionModal.value = null;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = null;

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowUpdatePaymentMethod.value = false;
    };

    // end modal
};
</script>

<template>
    <DynamicModal
        v-if="modalShowUpdatePaymentMethod"
        :show="modalShowUpdatePaymentMethod"
        maxWidth="lg"
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
        <header></header>
        <main>
            <SelectPaymentMethod
                :user="$page.props.user"
                :intent="intent"
                :publishableKey="publishableKey"
            ></SelectPaymentMethod>
        </main>
    </DynamicModal>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Your Profile" />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Your Profile</h2>
            </template>
            <template #description> Profile Settings </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div class="myPrimarySection">
                <div
                    class="divide-y divide-gray-200 sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
                >
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="myMediumIcon"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                                    />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="route('user.profile.update')"
                                    class="focus:outline-none"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Update Profile
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Doloribus dolores nostrum quia qui natus officia
                                quod et dolorem. Sit repellendus qui ut at
                                blanditiis et quo et molestiae.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <svg
                                class="h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                                />
                            </svg>
                        </span>
                    </div>

                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="myMediumIcon"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"
                                    />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="route('user.profile.security')"
                                    class="focus:outline-none"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Profile Security
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Browser Sessions and Profile deletion
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <svg
                                class="h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                                />
                            </svg>
                        </span>
                    </div>
                    <!-- password -->
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="myMediumIcon"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"
                                    />
                                </svg>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="route('user.profile.password')"
                                    class="focus:outline-none"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Update Password
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Doloribus dolores nostrum quia qui natus officia
                                quod et dolorem. Sit repellendus qui ut at
                                blanditiis et quo et molestiae.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <svg
                                class="h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                                />
                            </svg>
                        </span>
                    </div>
                </div>
                <SectionBorder
                    description="Billing & subscriptions"
                ></SectionBorder>
                <div
                    class="divide-y divide-gray-200 sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
                >
                    <!-- Payments and invoices # start -->
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <BanknotesIcon
                                    class="myMediumIcon"
                                ></BanknotesIcon>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="route('stripe.payments.index')"
                                    class="focus:outline-none"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Payments and invoices
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Doloribus dolores nostrum quia qui natus officia
                                quod et dolorem. Sit repellendus qui ut at
                                blanditiis et quo et molestiae.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <svg
                                class="h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                                />
                            </svg>
                        </span>
                    </div>
                    <!-- Payments and invoices # end -->
                    <!-- subscriptions # start -->
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <ArrowPathIcon
                                    class="myMediumIcon"
                                ></ArrowPathIcon>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="
                                        route(
                                            'stripe.payment.subscription.index'
                                        )
                                    "
                                    class="focus:outline-none"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Subscriptions
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Doloribus dolores nostrum quia qui natus officia
                                quod et dolorem. Sit repellendus qui ut at
                                blanditiis et quo et molestiae.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <svg
                                class="h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                                />
                            </svg>
                        </span>
                    </div>
                    <!-- subscriptions # end -->
                </div>
                <SectionBorder description="Payment methods"></SectionBorder>
                <div
                    class="divide-y divide-gray-200 sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
                >
                    <!-- Update payment methods # start -->
                    <div
                        v-if="intent"
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <CreditCardIcon
                                    class="myMediumIcon"
                                ></CreditCardIcon>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <button
                                    type="button"
                                    @click="updatePaymentMethod"
                                    class="focus:outline-none text-myPrimaryLinkColor"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Update Payment Methods
                                </button>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Doloribus dolores nostrum quia qui natus officia
                                quod et dolorem. Sit repellendus qui ut at
                                blanditiis et quo et molestiae.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <svg
                                class="h-6 w-6"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                                />
                            </svg>
                        </span>
                    </div>
                    <!-- Update payment methods # end -->
                </div>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
