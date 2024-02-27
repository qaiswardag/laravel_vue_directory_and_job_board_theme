<script setup>
import ActionSection from "@/Components/Actions/ActionSection.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useStore } from "vuex";
import UserTag from "@/Components/Users/UserTag.vue";
import { vueFetch } from "@/composables/vueFetch";
import { TailwindPagination } from "laravel-vue-pagination";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import InputError from "@/Components/Forms/InputError.vue";

import {
    CheckIcon,
    PencilIcon,
    ArrowLeftIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/outline";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";

// store
const store = useStore();

//
//
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
        route("user.api.internal.teams.index", {
            page: 1,
            search_query: searchForm.search_query,
        })
    );

    modalShowTeams.value = true;
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 3;
    titleModal.value = `Switch Company`;
    descriptionModal.value = `Are you sure you want to switch Company?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Switch Company";

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

const removeTeam = function () {
    switchTeamForm.team = null;
    switchTeamForm.team_id = null;
};

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    handleGetTeams(
        route("user.api.internal.teams.index", {
            page: page,
            search_query: searchForm.search_query,
        })
    );
};

const search_query = ref("");

// handle search
const handleSearch = function (page) {
    handleGetTeams(
        route("user.api.internal.teams.index", {
            page: page,
            search_query: searchForm.search_query,
        })
    );
};

const switchTeamForm = useForm({
    team: null,
    team_id: null,
});

const handleSelectTeam = function (team) {
    switchTeamForm.team = team;
    switchTeamForm.team_id = team.id;
};
//
const handleSwitchTeam = function () {
    switchTeamForm.put(
        route("current-team.update", { team_id: switchTeamForm.team_id }),
        {
            preserveScroll: true,
            onSuccess: () => {
                store.commit("mediaLibrary/setCurrentImage", null);
                store.commit("mediaLibrary/setCurrentPreviewImage", null);
                store.commit("attachedUsersOrItems/setRemoveAttachedUser", []);

                modalShowTeams.value = false;
            },
            onError: (err) => {},
        }
    );
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

                <!-- Selected Company # start -->

                <div
                    class="flex justify-center items-center mt-6 h-20 py-4 border rounded-full bg-gray-50"
                >
                    <div
                        v-if="switchTeamForm.team"
                        class="flex justify-between items-center my-2 px-6 gap-4 myPrimaryTag w-max"
                    >
                        <div
                            class="flex justify-left items-center myPrimaryGap"
                        >
                            <div
                                v-for="logo in switchTeamForm.team
                                    ?.coverImagesWithLogos.logos"
                                :key="logo.id"
                            >
                                <div class="flex-shrink-0">
                                    <img
                                        :src="`/storage/uploads/${logo.thumbnail_path}`"
                                        alt="image"
                                        class="h-10 rounded-full cursor-default"
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
                            <div
                                @click="removeTeam"
                                class="h-10 w-10 text-myPrimaryDarkGrayColor cursor-pointer rounded-full flex items-center justify-center bg-myPrimaryErrorColor aspect-square text-white hover:text-white focus-visible:ring-0"
                            >
                                <span class="material-symbols-outlined">
                                    remove
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Company # end -->
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

            <div class="mt-2">
                <div
                    class="min-h-[25rem] max-h-[25rem] overflow-y-scroll flex flex-col myPrimaryGap border border-gray-200 p-2 divide-y"
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
                            <div class="p-2 rounded cursor-pointer">
                                <div
                                    class="flex justify-between items-center my-2 px-6 gap-4 myPrimaryTag w-max"
                                >
                                    <div
                                        class="flex justify-left items-center myPrimaryGap"
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
                                                        @click="
                                                            handleSelectTeam(
                                                                team
                                                            )
                                                        "
                                                        :src="`/storage/uploads/${logo.thumbnail_path}`"
                                                        alt="image"
                                                        class="h-10 rounded-full"
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
                                        <template
                                            v-if="
                                                team.id !==
                                                switchTeamForm?.team_id
                                            "
                                        >
                                            <div
                                                @click="handleSelectTeam(team)"
                                                class="h-10 w-10 text-myPrimaryDarkGrayColor cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                            >
                                                <span
                                                    class="material-symbols-outlined"
                                                >
                                                    add
                                                </span>
                                            </div>
                                        </template>
                                        <template
                                            v-if="
                                                team.id ===
                                                switchTeamForm?.team_id
                                            "
                                        >
                                            <div
                                                @click="removeTeam"
                                                class="h-10 w-10 text-myPrimaryDarkGrayColor cursor-pointer rounded-full flex items-center justify-center bg-myPrimaryErrorColor aspect-square text-white hover:text-white focus-visible:ring-0"
                                            >
                                                <span
                                                    class="material-symbols-outlined"
                                                >
                                                    remove
                                                </span>
                                            </div>
                                        </template>
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

    <div class="flex justify-center">
        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.user.current_team &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <div class="myPrimaryTag px-4">
                <div
                    class="flex items-center justify-center px-2 py-2"
                    v-if="$page.props.user.current_team.coverImagesWithLogos"
                >
                    <div
                        v-if="
                            Array.isArray(
                                $page.props.user.current_team
                                    .coverImagesWithLogos.logos
                            )
                        "
                    >
                        <template
                            v-if="
                                $page.props.user.current_team
                                    .coverImagesWithLogos?.logos.length !== 0
                            "
                        >
                            <ThumbnailSmallImageSlider
                                :images="
                                    $page.props.user.current_team
                                        .coverImagesWithLogos?.logos
                                "
                                imageSize="medium_path"
                                imageHeight="min-h-16 max-h-16"
                                imageWidth="min-w-16 max-w-16"
                                :roundedFull="true"
                            ></ThumbnailSmallImageSlider>

                            <p
                                class="myPrimaryParagraph font-medium mt-2 text-center"
                            >
                                {{ $page.props.currentUserTeam?.name }}
                            </p>
                        </template>
                    </div>
                </div>
                <div
                    v-if="
                        $page.props.user.current_team.coverImagesWithLogos
                            ?.logos?.length === 0
                    "
                    class="flex items-center justify-center px-2 py-2"
                >
                    <p class="myPrimaryParagraph font-medium text-center">
                        {{ $page.props.currentUserTeam?.name }}
                    </p>
                </div>
                <div class="flex justify-center">
                    <Link
                        :href="
                            route(
                                'team.update.information',
                                $page.props.currentUserTeam?.id
                            )
                        "
                        class="h-10 w-10 text-myPrimaryDarkGrayColor cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <span class="material-symbols-outlined"> edit </span>
                    </Link>
                </div>
            </div>
        </template>
    </div>

    <div class="flex justify-center">
        <div class="justify-between group flex items-center px-2">
            <UserTag
                :user="$page.props.user"
                :showTeamRole="true"
                :currentUserTeamRole="$page.props.currentUserTeamRole"
                :clickable="true"
            ></UserTag>
        </div>
    </div>

    <div>
        <div class="myInputsOrganization">
            <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                <div class="myPrimaryFormOrganizationHeader">
                    Switch Company
                </div>
                <p class="myPrimaryParagraph">
                    Switch to another Company you are part of.
                </p>
            </div>
            <div>
                <button
                    type="button"
                    @click="handleShowAllTeams"
                    class="myPrimaryButton"
                >
                    Switch Company
                </button>
            </div>
        </div>
    </div>
</template>
