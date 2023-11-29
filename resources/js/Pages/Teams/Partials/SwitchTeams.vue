<script setup>
import ActionSection from "@/Components/Actions/ActionSection.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useStore } from "vuex";
import {
    CheckIcon,
    PencilIcon,
    ArrowLeftIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/outline";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";

// store
const store = useStore();

const switchTeamForm = useForm({});

const modalShowSwitchTeam = ref(false);

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

const handleSwitchToTeam = (team, currentTeamId) => {
    if (team.id === currentTeamId) {
        return;
    }

    modalShowSwitchTeam.value = true;

    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = `Switch team to ${team.name}`;
    descriptionModal.value = `Are you sure you want to switch  team to ${team.name}`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Switch Team";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowSwitchTeam.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowSwitchTeam.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        // commit

        store.commit("mediaLibrary/setCurrentImage", null);
        store.commit("mediaLibrary/setCurrentPreviewImage", null);
        store.commit("attachedUsersOrItems/setRemoveAttachedUser", []);

        switchTeam(team);
    };
    // end modal
};
//
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

//
const switchTeam = function (team) {
    switchTeamForm.put(route("current-team.update", { team_id: team.id }), {
        preserveScroll: true,
        onSuccess: () => {
            modalShowSwitchTeam.value = false;
        },
        onError: (err) => {},
    });
};
</script>

<template>
    <div>
        <ActionSection :sidebarArea="false" :actionsArea="false">
            <template #title>Switch Teams</template>

            <template #description>
                <template
                    v-if="
                        $page.props.user.all_teams.length > 0 &&
                        !$page.props.user.current_team &&
                        $page.props.jetstream.hasTeamFeatures
                    "
                >
                    No Team selected. Select a Team.
                    <br />
                </template>

                <template
                    v-if="
                        $page.props.user.all_teams.length > 0 &&
                        $page.props.user.current_team &&
                        $page.props.jetstream.hasTeamFeatures
                    "
                >
                    Switch to another Team.
                </template>
            </template>

            <template #main>
                <div class="myInputsOrganization">
                    <div class="myInputGroup">
                        <!-- table start -->
                        <div
                            v-if="
                                $page.props.user.all_teams &&
                                Array.isArray($page.props.user.all_teams) &&
                                $page.props.user.all_teams.length > 0
                            "
                            class="myTableContainerPlusScrollButton"
                        >
                            <div class="myScrollButtonContainer">
                                <button
                                    @click="handleLeft"
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                >
                                    <ArrowLeftIcon
                                        class="mySmallIcon"
                                    ></ArrowLeftIcon>
                                </button>
                                <button
                                    @click="handleRight"
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                >
                                    <ArrowRightIcon
                                        class="mySmallIcon"
                                    ></ArrowRightIcon>
                                </button>
                            </div>
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
                                                    Logo
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="myPrimaryTableTh"
                                                >
                                                    Cover image
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="myPrimaryTableTh"
                                                >
                                                    Switch to Company
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
                                                    Your Role
                                                </th>

                                                <th
                                                    scope="col"
                                                    class="myPrimaryTableTh"
                                                >
                                                    Edit
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="myPrimaryTableTBody">
                                            <tr
                                                v-for="team in $page.props.user
                                                    .all_teams &&
                                                Array.isArray(
                                                    $page.props.user.all_teams
                                                ) &&
                                                $page.props.user.all_teams"
                                                :key="team.id"
                                                class="myPrimaryTableTBodyTr"
                                            >
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    <div
                                                        v-if="
                                                            team.coverImagesWithLogos
                                                        "
                                                        class="text-myPrimaryDarkGrayColor flex items-center justify-center px-2 py-2 text-xs font-normal"
                                                    >
                                                        <div
                                                            v-if="
                                                                Array.isArray(
                                                                    team
                                                                        .coverImagesWithLogos
                                                                        .logos
                                                                )
                                                            "
                                                        >
                                                            <ThumbnailSmallImageSlider
                                                                :images="
                                                                    team
                                                                        .coverImagesWithLogos
                                                                        .logos
                                                                "
                                                                imageSize="medium_path"
                                                                imageHeight="h-28 self-start"
                                                                imageWidth="w-28"
                                                                :roundedFull="
                                                                    true
                                                                "
                                                            ></ThumbnailSmallImageSlider>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    <div
                                                        v-if="
                                                            team.coverImagesWithLogos
                                                        "
                                                        class="text-myPrimaryDarkGrayColor flex items-center justify-center px-2 py-2 text-xs font-normal"
                                                    >
                                                        <div
                                                            v-if="
                                                                Array.isArray(
                                                                    team
                                                                        .coverImagesWithLogos
                                                                        .cover_images
                                                                )
                                                            "
                                                        >
                                                            <ThumbnailSmallImageSlider
                                                                :images="
                                                                    team
                                                                        .coverImagesWithLogos
                                                                        .cover_images
                                                                "
                                                                imageSize="medium_path"
                                                                imageHeight="h-28"
                                                                imageWidth="w-28"
                                                                :roundedFull="
                                                                    true
                                                                "
                                                            ></ThumbnailSmallImageSlider>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    <form
                                                        @submit.prevent="
                                                            handleSwitchToTeam(
                                                                team,
                                                                $page.props.user
                                                                    .current_team_id
                                                            )
                                                        "
                                                    >
                                                        <div
                                                            class="flex items-center myPrimaryGap"
                                                            v-if="
                                                                $page.props.user
                                                                    .all_teams
                                                                    .length > 0
                                                            "
                                                        >
                                                            <span
                                                                class="myPrimaryParagraph text-xs"
                                                                v-if="
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                "
                                                                >Current Team
                                                            </span>
                                                            <span
                                                                class="myPrimaryParagraph text-xs"
                                                                v-if="
                                                                    team.id !==
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                "
                                                                >Switch to
                                                            </span>
                                                            <button
                                                                class="myPrimaryButton"
                                                                :class="{
                                                                    'mySecondaryButton flex items-center myPrimaryGap py-2 cursor-no-drop focus:ring-0':
                                                                        team.id ===
                                                                        $page
                                                                            .props
                                                                            .user
                                                                            .current_team_id,
                                                                }"
                                                            >
                                                                <div>
                                                                    <span>
                                                                        {{
                                                                            team.name
                                                                        }}
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <span
                                                                        v-if="
                                                                            team.id ===
                                                                            $page
                                                                                .props
                                                                                .user
                                                                                .current_team_id
                                                                        "
                                                                        class="myMediumIcon material-symbols-outlined"
                                                                    >
                                                                        check
                                                                    </span>
                                                                    <span
                                                                        v-if="
                                                                            team.id !==
                                                                            $page
                                                                                .props
                                                                                .user
                                                                                .current_team_id
                                                                        "
                                                                        class="myMediumIcon material-symbols-outlined"
                                                                    >
                                                                        refresh
                                                                    </span>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>

                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    <div
                                                        class="myPrimaryTag"
                                                        :class="
                                                            team.public
                                                                ? 'bg-myPrimaryLinkColor text-white'
                                                                : 'bg-myPrimaryErrorColor text-white'
                                                        "
                                                    >
                                                        {{
                                                            team.public
                                                                ? "Public"
                                                                : "Private"
                                                        }}
                                                    </div>
                                                </td>

                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{
                                                        team.membership
                                                            ? team.membership.role
                                                                  .charAt(0)
                                                                  .toUpperCase() +
                                                              team.membership.role.slice(
                                                                  1
                                                              )
                                                            : "Owner"
                                                    }}
                                                </td>

                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    <Link
                                                        v-if="
                                                            $page.props.user
                                                                .all_teams
                                                                .length > 0 &&
                                                            team.id ===
                                                                $page.props.user
                                                                    .current_team_id
                                                        "
                                                        :href="
                                                            route(
                                                                'team.update.information',
                                                                team.id
                                                            )
                                                        "
                                                        class="h-10 w-10 text-myPrimaryDarkGrayColor cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                    >
                                                        <span
                                                            class="material-symbols-outlined"
                                                        >
                                                            edit
                                                        </span>
                                                    </Link>
                                                    <span
                                                        v-if="
                                                            $page.props.user
                                                                .all_teams
                                                                .length > 0 &&
                                                            team.id !==
                                                                $page.props.user
                                                                    .current_team_id
                                                        "
                                                    >
                                                        Switch to Company for
                                                        edit
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <DynamicModal
                            :show="modalShowSwitchTeam"
                            :type="typeModal"
                            :disabled="switchTeamForm.processing"
                            disabledWhichButton="thirdButton"
                            :gridColumnAmount="gridColumnModal"
                            :title="titleModal"
                            :description="descriptionModal"
                            :firstButtonText="firstButtonModal"
                            :secondButtonText="secondButtonModal"
                            :thirdButtonText="thirdButtonModal"
                            @firstModalButtonFunction="firstModalButtonFunction"
                            @secondModalButtonFunction="
                                secondModalButtonFunction
                            "
                            @thirdModalButtonFunction="thirdModalButtonFunction"
                        >
                            <header></header>
                            <main></main>
                        </DynamicModal>
                    </div>
                </div>
            </template>
        </ActionSection>
    </div>
</template>
