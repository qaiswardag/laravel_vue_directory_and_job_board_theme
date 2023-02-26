<script setup>
import ActionSection from "@/Components/ActionSection.vue";
import DropdownLink from "../../../Components/Dropdowns/DropdownLink.vue";
import NavLink from "../../../Components/Navbars/NavLink.vue";
import { router } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

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

const handleSwitchToTeam = (team) => {
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
        switchTeam(team);
    };
    // end modal
};
//
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
                    No Team Selected. Select a Team.
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
                        <div class="myTableContainer">
                            <div class="myTableSubContainer">
                                <table class="myPrimaryTable">
                                    <caption class="myPrimaryTableCaption">
                                        <p class="myPrimaryParagraph my-4">
                                            Switch Team
                                        </p>
                                    </caption>
                                    <thead class="myPrimaryTableTHead">
                                        <tr class="myPrimaryTableTr">
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Team
                                            </th>

                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Team id
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Account Team Owner
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Your Role in this Team
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Switch Team
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="myPrimaryTableTBody">
                                        <tr
                                            v-for="team in $page.props.user
                                                .all_teams"
                                            :key="team.id"
                                            class="myPrimaryTableTBodyTr"
                                        >
                                            <td class="myPrimaryTableTBodyTd">
                                                <form
                                                    @submit.prevent="
                                                        handleSwitchToTeam(team)
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
                                                        <button
                                                            class="myPrimaryButtonNoBackground text-myPrimaryDarkGrayColor w-full flex items-center myPrimaryGap py-2"
                                                            :class="{
                                                                'text-myPrimaryBrandColor':
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id,
                                                            }"
                                                        >
                                                            <div>
                                                                {{ team.name }}
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                "
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke-width="2"
                                                                    stroke="currentColor"
                                                                    class="w-5 h-5 text-myPrimaryBrandColor"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M4.5 12.75l6 6 9-13.5"
                                                                    />
                                                                </svg>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>

                                            <td class="myPrimaryTableTBodyTd">
                                                {{ team.id }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                {{ team.user_id }}
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                descripe user role for this team
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
                                                <form
                                                    @submit.prevent="
                                                        handleSwitchToTeam(team)
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
                                                        <button
                                                            class="myPrimaryButton flex items-center myPrimaryGap py-2"
                                                            :class="{
                                                                'bg-gray-600 hover:bg-gray-700 focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 focus-visible:ring-2 focus-visible:ring-gray-600':
                                                                    team.id !==
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id,
                                                            }"
                                                        >
                                                            <div>
                                                                {{ team.name }}
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                "
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke-width="2"
                                                                    stroke="currentColor"
                                                                    class="w-5 h-5 text-white"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M4.5 12.75l6 6 9-13.5"
                                                                    />
                                                                </svg>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
