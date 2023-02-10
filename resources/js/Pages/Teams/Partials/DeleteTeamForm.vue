<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/Buttons/DangerButton.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";

const props = defineProps({
    team: Object,
});

const modalShowDeleteTeam = ref(false);

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

const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const handleDeleteTeam = () => {
    modalShowDeleteTeam.value = true;

    // handle show modal for unique content
    modalShowDeleteTeam.value = true;
    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 2;
    titleModal.value = `Delete team`;
    descriptionModal.value =
        "Please enter your password to confirm you would like to delete this team.";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Confirm";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeleteTeam.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        // modalShowDeleteTeam.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        deleteTeam();
    };
    // end modal
};

const deleteTeam = function () {
    form.delete(route("teams.destroy", props.team), {
        errorBag: "deleteTeam",
        preserveScroll: true,
        onSuccess: (log) => {
            modalShowDeleteTeam.value = false;
        },
        onError: (err) => {
            modalShowDeleteTeam.value = false;
        },
        onFinish: (log) => {
            modalShowDeleteTeam.value = false;
        },
    });
};
</script>

<template>
    <ActionSection :noSidebar="true">
        <template #title> Delete Team </template>

        <template #description> Delete {{ team.name }}? </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myInputsOrganizationText">
                    <p class="myTertiaryHeader">
                        Permanently delete {{ team.name }}?
                    </p>
                    <p class="myPrimaryParagraph">
                        Once a team is deleted, all of its resources and data
                        will be permanently deleted. Before deleting this team,
                        please download any data or information regarding this
                        team that you wish to retain.
                    </p>
                </div>
            </div>

            <DynamicModal
                :show="modalShowDeleteTeam"
                :type="typeModal"
                :disabled="form.processing"
                disabledWhichButton="thirdButton"
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
        </template>

        <!-- TODO: Add "confirms password" middleware to delete team
            right now, team can be deleted without password confirmation -->

        <template #actions>
            <SubmitButton
                :ButtonStyleDelete="true"
                :disabled="false"
                @firstButtonClick="handleDeleteTeam"
                :buttonText="`Delete ${team.name}`"
            >
            </SubmitButton>
        </template>
    </ActionSection>
</template>
