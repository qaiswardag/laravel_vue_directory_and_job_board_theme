<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import {
    AcademicCapIcon,
    BanknotesIcon,
    CheckBadgeIcon,
    ClockIcon,
    ReceiptRefundIcon,
    UsersIcon,
    BeakerIcon,
    Square3Stack3DIcon,
} from "@heroicons/vue/24/outline";
import UserTag from "@/Components/Users/UserTag.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import { vueFetch } from "@/composables/vueFetch";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import { ref } from "vue";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";

const breadcrumbsLinks = [
    {
        label: "Admin Dashboard",
        route: {
            name: "admin.dashboard",
            parameters: null,
        },
    },
];

const actions = [
    {
        title: "Users",
        href: "admin.users",
        icon: "users",
        iconForeground: "text-myPrimaryBrandColor",
        iconBackground: "bg-gray-50",
    },
    {
        title: "Page Builder Components",
        href: "admin.components",
        icon: "html",
        iconForeground: "text-myPrimaryBrandColor",
        iconBackground: "bg-gray-50",
    },
];

const modalShowTeams = ref(false);

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

// get teams
const {
    handleData: handleGetTeams,
    fetchedData: fetchedTeams,
    isError: isErrorTeams,
    error: errorTeams,
    errors: errorsTeams,
    isLoading: isLoadingTeams,
    isSuccess: isSuccessTeams,
} = vueFetch();

const handleSwitchTeam = function () {
    handleGetTeams(route("superadmin.api.internal.teams.index"));

    modalShowTeams.value = true;
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 3;
    titleModal.value = `Switch Team`;
    descriptionModal.value = `Are you sure you want to switch Team?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Switch Team";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowTeams.value = false;
    };

    // handle click
    thirdModalButtonFunction.value = function () {
        modalShowTeams.value = false;
    };
};
</script>

<template>
    <DynamicModal
        :show="modalShowTeams"
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
            <!-- error # start -->
            <template v-if="!isLoadingTeams && isErrorTeams && !isSuccessTeams">
                <p class="myPrimaryParagraphError">
                    {{ errorTeams }}
                </p>
            </template>
            <!-- error # end -->

            <!-- Loading # start -->
            <template v-if="isLoadingTeams">
                <SmallUniversalSpinner
                    width="w-8"
                    height="h-8"
                    border="border-4"
                ></SmallUniversalSpinner>
            </template>
            <!-- Loading # end -->

            <!-- Data # start -->
            <template v-if="fetchedTeams && Array.isArray(fetchedTeams)">
                <p class="py-8">fetchedTeams er:</p>
                <p class="py-8">{{ JSON.stringify(fetchedTeams) }}</p>
            </template>
            <!-- Data # end -->
        </main>
    </DynamicModal>

    <MainLayout>
        <LoggedInLayout>
            <Head title="Dashboard" />
            <template #header> Super Admininistrator </template>

            <template #description>Super Admininistrator Dashboard </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div class="myPrimarySection">
                <div class="flex gap-2 justify-center items-center mb-8">
                    <UserTag
                        customClass="my-0"
                        :user="$page.props.user"
                        :showTeamRole="true"
                        :clickable="true"
                        :currentUserTeamRole="
                            $page.props.user.superadmin &&
                            $page.props.user.superadmin.role === 'admin'
                                ? { role: 'superadmin' }
                                : $page.props.user.superadmin
                        "
                    ></UserTag>
                </div>
                <div
                    class="divide-y divide-gray-200 sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
                >
                    <div
                        v-for="(action, actionIdx) in actions"
                        :key="action.title"
                        :class="[
                            actionIdx === 0 ? '' : '',
                            actionIdx === 1 ? '' : '',
                            actionIdx === actions.length - 2 ? '' : '',
                            actionIdx === actions.length - 1 ? '' : '',
                            'relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer',
                        ]"
                    >
                        <div>
                            <span
                                :class="[
                                    action.iconBackground,
                                    action.iconForeground,
                                    'h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0',
                                ]"
                            >
                                <template v-if="action.icon === 'users'">
                                    <span class="material-symbols-outlined">
                                        group
                                    </span>
                                </template>
                                <template v-if="action.icon === 'html'">
                                    <span class="material-symbols-outlined">
                                        html
                                    </span>
                                </template>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <Link
                                    :href="route(action.href)"
                                    class="focus:outline-none"
                                >
                                    <!-- Extend touch target to entire panel -->
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    {{ action.title }}
                                </Link>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Navigate to the page and discover a range of
                                settings crafted to suit your needs. Whether
                                it's personalizing your account details,
                                adjusting security preferences, or fine-tuning
                                company settings.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <span class="material-symbols-outlined">
                                arrow_forward
                            </span>
                        </span>
                    </div>
                </div>

                <SectionBorder description="Teams"></SectionBorder>

                <div
                    class="divide-y divide-gray-200 sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
                >
                    <!-- Update payment methods # start -->
                    <div
                        class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-myPrimaryBrandColor cursor-pointer"
                    >
                        <div>
                            <span
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <span class="material-symbols-outlined">
                                    stacks
                                </span>
                            </span>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-normal">
                                <button
                                    type="button"
                                    @click="handleSwitchTeam"
                                    class="focus:outline-none text-myPrimaryLinkColor"
                                >
                                    <span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Change Current Team
                                </button>
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Navigate to the page and discover a range of
                                settings crafted to suit your needs. Whether
                                it's personalizing your account details,
                                adjusting security preferences, or fine-tuning
                                company settings.
                            </p>
                        </div>
                        <span
                            class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                            aria-hidden="true"
                        >
                            <span class="material-symbols-outlined">
                                arrow_forward
                            </span>
                        </span>
                    </div>
                    <!-- Update payment methods # end -->
                </div>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
