<script setup>
import ActionSection from "@/Components/ActionSection.vue";
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
                                                Cover image
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Team Name
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
                                                Switch Team
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
                                                .all_teams"
                                            :key="team.id"
                                            class="myPrimaryTableTBodyTr"
                                        >
                                            <td class="myPrimaryTableTBodyTd">
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
                                                        class="flex items-center myPrimaryGap justify-start"
                                                        v-if="
                                                            $page.props.user
                                                                .all_teams
                                                                .length > 0
                                                        "
                                                    >
                                                        <div
                                                            v-if="
                                                                team.thumbnail
                                                            "
                                                            class="h-16 w-16 flex-shrink-0"
                                                        >
                                                            <img
                                                                @click="
                                                                    handleSwitchToTeam(
                                                                        team,
                                                                        $page
                                                                            .props
                                                                            .user
                                                                            .current_team_id
                                                                    )
                                                                "
                                                                class="w-16 h-16 rounded-full object-cover"
                                                                :class="[
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                        ? 'cursor-default'
                                                                        : 'cursor-pointer',
                                                                ]"
                                                                :src="`/storage/uploads/${team.logo}`"
                                                                alt="logo"
                                                            />
                                                        </div>
                                                        <div
                                                            v-if="
                                                                team.thumbnail ===
                                                                null
                                                            "
                                                            class="h-16 w-16 flex-shrink-0"
                                                        >
                                                            <img
                                                                @click="
                                                                    handleSwitchToTeam(
                                                                        team,
                                                                        $page
                                                                            .props
                                                                            .user
                                                                            .current_team_id
                                                                    )
                                                                "
                                                                class="block h-16 w-16 rounded-full object-cover"
                                                                :class="[
                                                                    team.id ===
                                                                    $page.props
                                                                        .user
                                                                        .current_team_id
                                                                        ? 'cursor-default'
                                                                        : 'cursor-pointer',
                                                                ]"
                                                                src="https://emirateswoman.com/wp-content/uploads/2021/04/Sheikha-Sana-Al-Maktoum-.png"
                                                                alt="image"
                                                            />
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="myPrimaryTableTBodyTd">
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
                                                        class="flex items-center myPrimaryGap justify-start"
                                                        v-if="
                                                            $page.props.user
                                                                .all_teams
                                                                .length > 0
                                                        "
                                                    >
                                                        <button
                                                            class="myPrimaryButtonNoBackground text-myPrimaryDarkGrayColor flex items-center gap-2"
                                                            :class="{
                                                                'text-myPrimaryBrandColor cursor-default':
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
                                                                    stroke-width="1.5"
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
                                                <span
                                                    :class="
                                                        team.public
                                                            ? 'bg-green-100'
                                                            : 'bg-red-100 text-myErrorColor'
                                                    "
                                                    class="inline-flex rounded-full px-2 font-semibold leading-5 text-green-800"
                                                    >{{
                                                        team.public
                                                            ? "Public"
                                                            : "Privat"
                                                    }}</span
                                                >
                                            </td>

                                            <td class="myPrimaryTableTBodyTd">
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
                                            <td class="myPrimaryTableTBodyTd">
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
                                                        <button
                                                            class="myPrimaryButton flex items-center myPrimaryGap py-2 cursor-default"
                                                            :class="{
                                                                'bg-gray-600 hover:bg-gray-700 focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 focus-visible:ring-2 focus-visible:ring-gray-600 cursor-pointer':
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
                                                                    stroke-width="1.5"
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
                                            <td class="myPrimaryTableTBodyTd">
                                                <Link
                                                    :href="
                                                        route(
                                                            'team.update.information',
                                                            team
                                                        )
                                                    "
                                                    class="myPrimaryButtonNoBackground"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                        class="w-5 h-5"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"
                                                        ></path>
                                                    </svg>
                                                </Link>
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
