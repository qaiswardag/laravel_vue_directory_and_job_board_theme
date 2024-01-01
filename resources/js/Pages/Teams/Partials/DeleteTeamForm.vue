<script setup>
import { ref } from "vue";
import ActionSection from "@/Components/Actions/ActionSection.vue";
import DangerButton from "@/Components/Buttons/DangerButton.vue";
import InputError from "@/Components/Forms/InputError.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/Forms/InputLabel.vue";

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
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <ActionSection :sidebarArea="false">
        <template #title> Delete Company </template>
        <template #description> Delete {{ team.name }}? </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Delete Company {{ team.name }}
                    </div>
                    <p class="myPrimaryParagraph">
                        Deleting a team is a permanent action and cannot be
                        undone. Before proceeding with the deletion, please
                        ensure that you have carefully considered the
                        consequences. Here are a few points to keep in mind:

                        <br />
                        <br />
                        Data Loss: Deleting a team will result in the permanent
                        removal of all associated data, including team members,
                        images, files, and any other team-specific information.
                        Make sure you have backed up any important data.
                        <br />
                        <br />
                        Collaboration Impact: Once a team is deleted, all
                        collaboration and communication within the team will
                        cease. Any ongoing projects, discussions, or shared
                        resources will no longer be accessible. It is crucial to
                        inform team members in advance and make alternative
                        arrangements for their work and collaboration needs.
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
                <main>
                    <div class="myInputGroup">
                        <InputLabel for="roles" value="Enter Password" />
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            @keyup.enter="handleDeleteTeam"
                        />

                        <InputError :message="form.errors.password" />
                    </div>
                </main>
            </DynamicModal>
        </template>

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
