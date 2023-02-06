<script setup>
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import { ref } from "@vue/reactivity";

const props = defineProps({
    team: Object,
    permissions: Object,
});

const modalShowUpdateTeam = ref(false);

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

const handleUpdateTeam = function () {
    // handle show modal for unique content
    modalShowUpdateTeam.value = true;
    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Update team";
    descriptionModal.value = "Are you sure you want to update this team?";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Update";
    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowUpdateTeam.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        updateTeamName();
        // handle show modal for unique content
        modalShowUpdateTeam.value = false;
    };
    // end modal
};

const form = useForm({
    name: props.team.name,
});

const updateTeamName = () => {
    form.put(route("teams.update", props.team), {
        // error bag validation
        errorBag: "updateTeam",
        preserveScroll: true,
        onSuccess: (log) => {},
        onError: (err) => {},
        onFinish: () => {},
    });
};
</script>

<template>
    <DynamicModal
        :show="modalShowUpdateTeam"
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
    <FormSection @submitted="handleUpdateTeam">
        <template #title> Team Name </template>

        <template #description>
            The team's name and owner information.
        </template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="col-span-6">
                <InputLabel value="Team Owner" />

                <div class="flex items-center gap-2 mt-2">
                    <div
                        v-if="$page.props.team.owner.profile_photo_url && false"
                    >
                        <img
                            class="object-cover w-12 h-12 rounded-full"
                            :src="$page.props.team.owner.profile_photo_url"
                            :alt="$page.props.team.owner.first_name"
                        />
                    </div>

                    <div
                        v-if="true"
                        class="h-12 w-12 rounded-full bg-myPrimaryColor-500 flex justify-center items-center text-xs font-semibold text-white"
                    >
                        <hr />

                        {{
                            $page.props.team.owner.first_name
                                .charAt(0)
                                .toUpperCase()
                        }}
                        {{
                            $page.props.team.owner.last_name
                                .charAt(0)
                                .toUpperCase()
                        }}
                    </div>
                    <div class="flex flex-col items-left gap-1">
                        <p class="text-xs font-semibold">
                            {{ $page.props.team.owner.first_name }}
                            {{ $page.props.team.owner.last_name }}
                        </p>
                        <p class="text-xs font-semibold">
                            {{ $page.props.team.owner.email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Team Name -->
            <div class="myInputGroup mt-8">
                <InputLabel for="name" value="Team Name" />

                <TextInput id="name" v-model="form.name" type="text" />

                <InputError :message="form.errors.name" />
            </div>
        </template>

        <template #actions>
            <SubmitButton
                :status="form.recentlySuccessful"
                successMessage="Successfully updated your team."
                :disabled="form.processing"
                buttonText="Update"
            >
            </SubmitButton>
        </template>
    </FormSection>
</template>
