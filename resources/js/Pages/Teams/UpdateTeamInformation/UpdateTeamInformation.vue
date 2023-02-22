<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import DeleteTeamForm from "@/Pages/Teams/Partials/DeleteTeamForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TeamMemberManager from "@/Pages/Teams/Partials/TeamMemberManager.vue";
import UpdateTeamNameForm from "@/Pages/Teams/Partials/UpdateTeamNameForm.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import SideBarLink from "@/Components/MenuLinks/SideBarLink.vue";

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
            <h2 class="myPrimaryMainPageHeader">Team Information</h2>
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
        </div>
    </LoggedInLayout>
</template>
