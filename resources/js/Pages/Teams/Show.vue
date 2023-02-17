<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import DeleteTeamForm from "@/Pages/Teams/Partials/DeleteTeamForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TeamMemberManager from "@/Pages/Teams/Partials/TeamMemberManager.vue";
import UpdateTeamNameForm from "@/Pages/Teams/Partials/UpdateTeamNameForm.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
});

const modalShowWhenNoTeamOwner = ref(false);

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

if (props.team.owner === null || props.team.owner === undefined) {
    modalShowWhenNoTeamOwner.value = true;

    // set modal standards
    typeModal.value = "danger";
    gridColumnModal.value = 1;
    titleModal.value = `Account Team owner is messing`;
    descriptionModal.value = `There is no Account Team owner for this Team. Please select a Team first.`;
    firstButtonModal.value = null;
    secondButtonModal.value = null;
    thirdButtonModal.value = "Select Team";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowWhenNoTeamOwner.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowWhenNoTeamOwner.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowWhenNoTeamOwner.value = false;
        // action
        router.get(route("teams.index"));
    };
    // end modal
}
</script>

<template>
    <LoggedInLayout title="Team Settings">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Teams Settings</h2>
        </template>

        <DynamicModal
            :show="modalShowWhenNoTeamOwner"
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
            <main></main>
        </DynamicModal>

        <div v-if="team.owner !== null && team.owner !== undefined">
            <UpdateTeamNameForm :team="team"> </UpdateTeamNameForm>

            <TeamMemberManager
                class="mt-10 sm:mt-0"
                :team="team"
                :available-roles="availableRoles"
                :user-permissions="permissions"
            />
            <SectionBorder />
            <DeleteTeamForm class="mt-10 sm:mt-0" :team="team">
            </DeleteTeamForm>

            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <!--  -->
            <div class="mt-64">
                <div
                    class="my-4 border-2 border-myPrimaryBrandColor rounded-lg px-4"
                >
                    <h1 class="mySecondaryHeader my-4">Permissions:</h1>
                    <hr />
                    <p
                        class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                    >
                        <span class="text-myErrorColor">
                            Current Permissions:
                        </span>
                        {{ JSON.stringify(permissions) }}
                    </p>
                    <hr />
                    <p
                        class="myPrimaryParagraph my-4 text-indigo-600 flex justify-between items-center"
                    >
                        <span class="text-indigo-800">
                            Example for company created by user. Like (teams
                            table: user_id = 1, which is Qais ):
                        </span>

                        {"canAddTeamMembers":true,"canDeleteTeam":true,"canRemoveTeamMembers":true,"canUpdateTeam":true}
                    </p>

                    <hr />

                    <p
                        class="myPrimaryParagraph my-4 text-fuchsia-600 flex justify-between items-center"
                    >
                        <span class="text-fuchsia-800">
                            Permissions Added Team Member as Administrator:
                        </span>
                        {"canAddTeamMembers":false,"canDeleteTeam":false,"canRemoveTeamMembers":false,"canUpdateTeam":false}
                    </p>
                    <hr />
                    <p
                        class="myPrimaryParagraph my-4 text-green-600 flex justify-between items-center"
                    >
                        <span class="text-green-800">
                            Permissions Added Team Member as Editor:
                        </span>
                        {"canAddTeamMembers":false,"canDeleteTeam":false,"canRemoveTeamMembers":false,"canUpdateTeam":false}
                    </p>
                </div>

                <!-- PERMISSIONS END -->
                <!-- PERMISSIONS END -->
                <!-- PERMISSIONS END -->
                <!-- PERMISSIONS END -->

                <!-- TEAM START -->
                <!-- TEAM START -->
                <!-- TEAM START -->
                <!-- TEAM START -->
                <div>
                    <div
                        class="my-4 border-2 border-myPrimaryBrandColor rounded-lg px-4"
                    >
                        <h1 class="mySecondaryHeader my-4">Team:</h1>
                        <hr />
                        <p
                            class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                        >
                            <span class="text-myErrorColor"> team id: </span>
                            {{ JSON.stringify(team.id) }}
                        </p>
                        <hr />
                        <p
                            class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                        >
                            <span class="text-myErrorColor">
                                team user id:
                            </span>
                            {{ JSON.stringify(team.user_id) }}
                        </p>
                        <hr />
                        <p
                            class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                        >
                            <span class="text-myErrorColor">
                                Personal team:
                            </span>
                            {{ JSON.stringify(team.personal_team) }}
                        </p>
                        <hr />
                        <p
                            class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                        >
                            <span class="text-myErrorColor"> Owner: </span>
                            {{ team.owner && team.owner.first_name }}
                            {{ team.owner && team.owner.last_name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </LoggedInLayout>
</template>
