<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/DangerButton.vue";
import ConfirmYourPassword from "@/Components/Modals/ConfirmYourPassword.vue";

const props = defineProps({
    team: Object,
});

const modalShowConfirmingTeamDeletion = ref(false);

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

const form = useForm({});

// const confirmTeamDeletion = () => {
//     modalShowConfirmingTeamDeletion.value = true;
// };

const deleteTeam = () => {
    // handle show modal for unique content
    modalShowConfirmingTeamDeletion.value = true;
    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 2;
    titleModal.value = `Delete Team ${props.team.name}?`;
    descriptionModal.value = `Are you sure you want to delete ${props.team.name}? Once a team is
                    deleted, all of its resources and data will be permanently
                    deleted.`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowConfirmingTeamDeletion.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowConfirmingTeamDeletion.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        form.delete(route("teams.destroy", props.team), {
            errorBag: "deleteTeam",
        });
        // handle show modal for unique content
        modalShowConfirmingTeamDeletion.value = false;
    };
};

const okHaveBeenConfirmed = function () {
    console.log("this it a TEST TEST TEST");
};
const test1 = function () {
    console.log("22222");
};
</script>

<template>
    <ActionSection>
        <template #title> Delete Team </template>

        <template #description> Permanently delete this team. </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once a team is deleted, all of its resources and data will be
                permanently deleted. Before deleting this team, please download
                any data or information regarding this team that you wish to
                retain.
            </div>

            <div class="mt-5">
                <DangerButton @click="deleteTeam"> Delete Team </DangerButton>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <ConfirmYourPassword
                :show="modalShowConfirmingTeamDeletion"
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
            </ConfirmYourPassword>
        </template>
    </ActionSection>
</template>
