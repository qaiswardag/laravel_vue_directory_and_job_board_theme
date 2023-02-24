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
import AvatarCardCenterSmall from "@/Components/Avatars/AvatarCardCenterSmall.vue";
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
        onError: (err) => {
            console.log("error is:", err);
        },
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
    <FormSection @submitted="handleUpdateTeam" :sidebarArea="false">
        <template #title>
            {{ $page.props.team && $page.props.team.name }}
        </template>

        <template #description>
            <div
                v-if="
                    $page.props.team.owner &&
                    $page.props.team.owner.id === $page.props.user.id
                "
            >
                <p class="group flex items-center pr-2 py-2 myPrimaryParagraph">
                    Your name
                    <span class="px-2 py-1 bg-green-100 mx-2 rounded-md">
                        {{
                            $page.props.team.owner &&
                            $page.props.team.owner.first_name
                        }}
                        {{
                            $page.props.team.owner &&
                            $page.props.team.owner.last_name
                        }}
                    </span>
                    and current role
                    <span class="px-2 py-1 bg-green-100 ml-2 rounded-md">
                        {{ $page.props.currentUserTeamRole }}
                    </span>
                </p>
            </div>
            <div
                v-if="
                    $page.props.team.owner &&
                    $page.props.team.owner.id !== $page.props.user.id
                "
            >
                <p class="myPrimaryParagraph">
                    Account owner of this Team is:

                    <span class="px-2 py-1 bg-gray-100 mr-1 rounded-md">
                        {{
                            $page.props.team.owner &&
                            $page.props.team.owner.first_name
                        }}
                        {{
                            $page.props.team.owner &&
                            $page.props.team.owner.last_name
                        }}
                    </span>
                </p>
                <p
                    class="group flex items-center pr-2 py-2 mt-2 myPrimaryParagraph"
                >
                    Your name
                    <span class="px-2 py-1 bg-green-100 mx-2.5 rounded-md">
                        {{ $page.props.user.first_name }}
                        {{ $page.props.user.last_name }}
                    </span>
                    and current role
                    <span class="px-2 py-1 bg-green-100 mx-2 rounded-md">
                        {{ $page.props.currentUserTeamRole }}
                    </span>
                </p>
            </div>
        </template>

        <template #main>
            <!-- Team Owner Information -->
            <div class="myInputsOrganization">
                <div class="myInputGroup">
                    <div
                        class="flex flex-col justify-center items-center gap-2 mb-8 mt-4"
                    >
                        <p class="myPrimaryParagraph">Team owner</p>
                        <div
                            v-show="
                                $page.props.team.owner &&
                                $page.props.team.owner.profile_photo_path !==
                                    null
                            "
                            class="mt-2"
                        >
                            <img
                                class="object-cover w-16 h-16 rounded-full"
                                :src="$page.props.team.owner.profile_photo_url"
                                :alt="
                                    $page.props.team.owner.first_name +
                                    $page.props.team.owner.last_name
                                "
                            />
                        </div>

                        <div
                            v-show="
                                $page.props.team.owner &&
                                $page.props.team.owner.profile_photo_path ===
                                    null
                            "
                            class="w-16 h-16 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-semibold text-white"
                        >
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
                        <span
                            class="flex flex-col items-center gap-1 myPrimaryParagraph"
                        >
                            <span>
                                {{ $page.props.team.owner.first_name }}
                                {{ $page.props.team.owner.last_name }}
                            </span>
                            <span>
                                {{ $page.props.team.owner.email }}
                            </span>
                        </span>
                    </div>
                    <!--  -->
                    <!--  -->
                    <!--  -->
                    <InputLabel for="name" value="Update Team Name" />
                    <TextInput id="name" v-model="form.name" type="text" />
                    <InputError :message="form.errors.name" />
                </div>
            </div>
        </template>

        <template #actions>
            <SubmitButton :disabled="form.processing" buttonText="Update">
            </SubmitButton>
            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully updated your team.
            </ActionMessage>
        </template>
    </FormSection>
</template>
