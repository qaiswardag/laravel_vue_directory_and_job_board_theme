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
//
//
//
//
</script>

<template>
    <div>
        <ActionSection :noSidebar="true">
            <template #title> Switch team </template>

            <template #description> Switch to another team.</template>

            <template #main>
                <div class="myInputsOrganization">
                    <div class="myInputsOrganizationText">
                        <p class="myTertiaryHeader">Lorem, ipsum dolor</p>
                        <p class="myPrimaryParagraph">
                            Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit. Corporis ex dignissimos quas doloremque culpa
                            at!
                        </p>
                    </div>
                    <div class="myInputGroup">
                        <div class="overflow-x-auto">
                            <div
                                class="inline-block min-w-full py-2 align-middle px-1"
                            >
                                <div
                                    class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded"
                                >
                                    <table
                                        class="min-w-full divide-y divide-gray-300"
                                    >
                                        <caption class="bg-white">
                                            <p class="myPrimaryParagraph my-4">
                                                Switch Team
                                            </p>
                                        </caption>
                                        <thead class="bg-gray-50">
                                            <tr
                                                class="divide-x divide-gray-200"
                                            >
                                                <th
                                                    scope="col"
                                                    class="py-3.5 pl-4 pr-4 text-left text-sm font-semibold text-myPrimaryDarkGrayColor sm:pl-6 min-w-[20rem]"
                                                >
                                                    Team
                                                </th>

                                                <th
                                                    scope="col"
                                                    class="px-4 py-3.5 text-left text-sm font-semibold text-myPrimaryDarkGrayColor"
                                                >
                                                    Team id
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-4 py-3.5 text-left text-sm font-semibold text-myPrimaryDarkGrayColor"
                                                >
                                                    Team owner
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-4 py-3.5 text-left text-sm font-semibold text-myPrimaryDarkGrayColor"
                                                >
                                                    Switch Team
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody
                                            class="divide-y divide-gray-200 bg-white"
                                        >
                                            <tr
                                                v-for="team in $page.props.user
                                                    .all_teams"
                                                :key="team.id"
                                                class="divide-x divide-gray-200"
                                            >
                                                <td
                                                    class="whitespace-nowrap py-4 pl-4 pr-4 text-sm font-medium text-myPrimaryDarkGrayColor sm:pl-6"
                                                >
                                                    <form
                                                        @submit.prevent="
                                                            handleSwitchToTeam(
                                                                team
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
                                                                class="myPrimaryButtonNoBackground text-myPrimaryDarkGrayColor w-full flex items-center myPrimaryGap py-2"
                                                                :class="{
                                                                    'text-myPrimaryBrandColor':
                                                                        team.id ===
                                                                        $page
                                                                            .props
                                                                            .user
                                                                            .current_team_id,
                                                                }"
                                                            >
                                                                <div>
                                                                    {{
                                                                        team.name
                                                                    }}
                                                                </div>
                                                                <div
                                                                    v-if="
                                                                        team.id ===
                                                                        $page
                                                                            .props
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

                                                <td
                                                    class="whitespace-nowrap p-4 text-sm text-myPrimaryDarkGrayColor"
                                                >
                                                    {{ team.id }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap p-4 text-sm text-myPrimaryDarkGrayColor"
                                                >
                                                    {{ team.user_id }}
                                                </td>
                                                <td
                                                    class="whitespace-nowrap p-4 text-sm text-myPrimaryDarkGrayColor"
                                                >
                                                    <form
                                                        @submit.prevent="
                                                            handleSwitchToTeam(
                                                                team
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
                                                                class="myPrimaryButton w-full flex items-center myPrimaryGap py-2"
                                                                :class="{
                                                                    'bg-gray-600 hover:bg-gray-700 focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 focus-visible:ring-2 focus-visible:ring-gray-600':
                                                                        team.id !==
                                                                        $page
                                                                            .props
                                                                            .user
                                                                            .current_team_id,
                                                                }"
                                                            >
                                                                <div>
                                                                    {{
                                                                        team.name
                                                                    }}
                                                                </div>
                                                                <div
                                                                    v-if="
                                                                        team.id ===
                                                                        $page
                                                                            .props
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
