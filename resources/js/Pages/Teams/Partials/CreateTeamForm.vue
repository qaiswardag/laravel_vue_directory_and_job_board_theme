<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import AvatarCardCenterSmall from "@/Components/Avatars/AvatarCardCenterSmall.vue";

import { ref } from "@vue/reactivity";

const createTeamForm = useForm({
    name: "",
});

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

const modalShowCreateTeam = ref(false);
const handleCreateTeam = function () {
    // handle show modal for unique content
    modalShowCreateTeam.value = true;
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = `Create a new team ${createTeamForm.name}`;
    descriptionModal.value = `Following team is being created ${createTeamForm.name}.`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Create Team";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCreateTeam.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        createTeam();
        // handle show modal for unique content
        modalShowCreateTeam.value = false;
    };
    // end modal
};

const createTeam = () => {
    createTeamForm.post(route("teams.store"), {
        // error bag validation
        errorBag: "createTeam",
        preserveScroll: true,
        onSuccess: (log) => {
            createTeamForm.reset();
        },
        onError: (err) => {
            createTeamForm.reset();
        },
        onFinish: () => {},
    });
};
</script>

<template>
    <DynamicModal
        :show="modalShowCreateTeam"
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
    <FormSection
        @submitted="handleCreateTeam"
        :sidebarArea="false"
        :actionsArea="false"
    >
        <template #title> Create Team </template>

        <template #description>
            Create a new team to collaborate with others on projects.
        </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myInputGroup">
                    <p class="myPrimaryParagraph text-center">
                        You will become Account owner of this Team
                    </p>
                    <AvatarCardCenterSmall></AvatarCardCenterSmall>
                    <InputLabel for="name" value="Team Name" />
                    <TextInput
                        id="name"
                        v-model="createTeamForm.name"
                        type="text"
                        autofocus
                        autocomplete="off"
                    />
                    <InputError :message="createTeamForm.errors.name" />
                </div>
            </div>
        </template>
    </FormSection>
</template>
