<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
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
    <FormSection @submitted="handleCreateTeam">
        <template #title> Create Team </template>

        <template #description>
            Create a new team to collaborate with others on projects.
        </template>

        <template #main>
            <div class="myInputsOrganization">
                <div class="myInputsOrganizationText">
                    <p class="myTertiaryHeader">Lorem, ipsum dolor</p>
                    <p class="myPrimaryParagraph">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Corporis ex dignissimos quas doloremque culpa at!
                    </p>
                </div>
                <div class="myInputGroup">
                    <div class="col-span-6">
                        <InputLabel value="Team Owner" />
                        <div class="flex items-center gap-2 mt-2">
                            <div
                                v-if="
                                    $page.props.user.profile_photo_url && false
                                "
                            >
                                <img
                                    class="object-cover w-12 h-12 rounded-full"
                                    :src="$page.props.user.profile_photo_url"
                                    :alt="$page.props.user.first_name"
                                />
                            </div>

                            <div
                                v-if="true"
                                class="h-12 w-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-semibold text-white"
                            >
                                {{
                                    $page.props.user.first_name
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                                {{
                                    $page.props.user.last_name
                                        .charAt(0)
                                        .toUpperCase()
                                }}
                            </div>
                            <div class="flex flex-col items-left gap-1">
                                <p class="text-xs font-semibold">
                                    {{ $page.props.user.first_name }}
                                    {{ $page.props.user.last_name }}
                                </p>
                                <p class="text-xs font-semibold">
                                    {{ $page.props.user.email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="myInputGroup">
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
            </div>
        </template>

        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myInputsOrganizationText">
                    <p class="myTertiaryHeader">Lorem, ipsum dolor</p>
                    <p class="myPrimaryParagraph">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Corporis ex dignissimos quas doloremque culpa at!
                    </p>
                </div>
            </div>
        </template>

        <template #actions>
            <SubmitButton
                :disabled="createTeamForm.processing"
                buttonText="Create team"
            >
            </SubmitButton>
        </template>
    </FormSection>
</template>
