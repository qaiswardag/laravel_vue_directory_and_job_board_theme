<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import { TailwindPagination } from "laravel-vue-pagination";
import { router, useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/Forms/InputError.vue";

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

const searchForm = useForm({
    search_query: "",
});

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

const handleShowAllTeams = function () {
    handleGetTeams(
        route("superadmin.api.internal.teams.index", {
            page: 1,
            search_query: searchForm.search_query,
        })
    );

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

        switchTeamForm.team = null;
        switchTeamForm.team_id = null;
    };

    // handle click
    thirdModalButtonFunction.value = function () {
        handleSwitchTeam();
    };
};

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    handleGetTeams(
        route("superadmin.api.internal.teams.index", {
            page: page,
            search_query: searchForm.search_query,
        })
    );
};

const search_query = ref("");

// handle search
const handleSearch = function (page) {
    handleGetTeams(
        route("superadmin.api.internal.teams.index", {
            page: page,
            search_query: searchForm.search_query,
        })
    );
};

const switchTeamForm = useForm({
    team: null,
    team_id: null,
});

const handleSwitchTeam = function () {
    switchTeamForm.put(route("superadmin.switch.team"), {
        onSuccess: () => {
            modalShowTeams.value = false;
        },
        onError: () => {},
        onFinish: () => {},
    });
};

const handleSelectTeam = function (team) {
    switchTeamForm.team = team;
    switchTeamForm.team_id = team.id;
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
            <div
                v-if="
                    fetchedTeams &&
                    fetchedTeams.teams &&
                    fetchedTeams.teams.data
                "
            >
                <!-- Search # start -->
                <form @submit.prevent="handleSearch">
                    <div class="relative w-full mb-12">
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
                            class="myPrimaryInput pl-10 shadow-none min-h-[3.5rem] h-[3.5rem]"
                            autocomplete="off"
                            :placeholder="`Search..`"
                        />
                    </div>
                </form>
                <!-- Search # end -->

                <!-- Pagination # start -->

                <TailwindPagination
                    :limit="1"
                    :keepLength="true"
                    :class="[
                        'space-x-1',
                        'shadow-none',
                        'tailwind-pagination-package',
                    ]"
                    :active-classes="[
                        'bg-myPrimaryLinkColor',
                        'text-white',
                        'rounded-full',
                    ]"
                    :item-classes="[
                        'p-0',
                        'm-0',
                        'border-none',
                        'bg-myPrimaryLightGrayColor',
                        'shadow-sm',
                        'hover:bg-gray-300',
                        'text-myPrimaryDarkGrayColor',
                        'rounded-full',
                    ]"
                    :data="fetchedTeams.teams"
                    @pagination-change-page="getResultsForPage"
                >
                    <template #prev-nav>
                        <span> Prev </span>
                    </template>
                    <template #next-nav>
                        <span>Next</span>
                    </template>
                </TailwindPagination>

                <!-- Selected Team # start -->
                <template v-if="switchTeamForm.team">
                    <div
                        class="flex justify-center items-center bg-red-50 my-4"
                    >
                        <div
                            class="flex justify-between items-center my-2 px-6 gap-4 myPrimaryTag w-max"
                        >
                            <div class="flex justify-left items-center gap-2">
                                <div
                                    v-for="logo in switchTeamForm.team
                                        ?.coverImagesWithLogos.logos"
                                    :key="logo.id"
                                >
                                    <div class="flex-shrink-0">
                                        <img
                                            :src="`/storage/uploads/${logo.thumbnail_path}`"
                                            alt="image"
                                            class="myPrimarythumbnailInsertPreview cursor-default"
                                        />
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <p class="myPriamryParagraph font-medium">
                                        {{ switchTeamForm.team?.name }}
                                    </p>
                                    <p class="myPriamryParagraph text-xs">
                                        Id: {{ switchTeamForm.team?.id }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Selected Team # end -->
                <InputError :message="switchTeamForm.errors.team_id" />
            </div>
            <!-- Pagination # end -->

            <!-- error # start -->
            <template v-if="!isLoadingTeams && isErrorTeams && !isSuccessTeams">
                <p class="myPrimaryParagraphError">
                    {{ errorTeams }}
                </p>
            </template>
            <!-- error # end -->

            <div class="mt-8">
                <div
                    class="min-h-[25rem] max-h-[25rem] overflow-y-scroll flex flex-col myPrimaryGap"
                >
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
                    <template
                        v-if="
                            fetchedTeams &&
                            fetchedTeams.teams &&
                            fetchedTeams.teams.data &&
                            Array.isArray(fetchedTeams.teams.data) &&
                            !isLoadingTeams
                        "
                    >
                        <template v-if="fetchedTeams.teams.data.length === 0">
                            <p class="myPrimaryParagraph">
                                It seems that there are no teams.
                            </p>
                        </template>
                        <div
                            v-for="team in fetchedTeams.teams.data"
                            :key="team.id"
                        >
                            <div
                                @click="handleSelectTeam(team)"
                                class="p-2 border border-myPrimaryLightGrayColor rounded cursor-pointer"
                            >
                                <div
                                    class="flex justify-between items-center my-2 px-6 gap-4 myPrimaryTag w-max"
                                >
                                    <div
                                        class="flex justify-left items-center gap-2"
                                    >
                                        <template
                                            v-if="
                                                team?.coverImagesWithLogos
                                                    .logos &&
                                                Array.isArray(
                                                    team?.coverImagesWithLogos
                                                        .logos
                                                ) === true
                                            "
                                        >
                                            <div
                                                v-for="logo in team
                                                    ?.coverImagesWithLogos
                                                    .logos"
                                                :key="logo.id"
                                            >
                                                <div class="flex-shrink-0">
                                                    <img
                                                        :src="`/storage/uploads/${logo.thumbnail_path}`"
                                                        alt="image"
                                                        class="myPrimarythumbnailInsertPreview"
                                                    />
                                                </div>
                                            </div>
                                        </template>

                                        <div class="flex flex-col gap-1">
                                            <p
                                                class="myPriamryParagraph font-medium cursor-pointer"
                                            >
                                                {{ team?.name }}
                                            </p>
                                            <p
                                                class="myPriamryParagraph text-xs cursor-pointer"
                                            >
                                                Id: {{ team?.id }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Data # end -->
            </div>
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
                                    @click="handleShowAllTeams"
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
