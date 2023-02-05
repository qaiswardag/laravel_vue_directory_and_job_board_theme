<script setup>
import { ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/Buttons/DangerButton.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";

const props = defineProps({
    team: Object,
    availableRoles: Array,
    userPermissions: Object,
});

const modalShowCancelTeamInvitation = ref(false);
const modalShowAddedTeamMember = ref(false);
const modalShowTeamMemberBeingRemoved = ref(false);

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

const addTeamMemberForm = useForm({
    email: "",
    role: null,
});

const cancelTeamInvitationForm = useForm({});
const updateRoleForm = useForm({
    role: null,
});

const leaveTeamForm = useForm({});
const removeTeamMemberForm = useForm({});

const managingRoleFor = ref(null);
const modalShowCurrentlyManagingRole = ref(false);
const modalShowLeaveTeam = ref(false);

const addTeamMember = () => {
    addTeamMemberForm.post(route("team-members.store", props.team), {
        // error bag validation
        errorBag: "addTeamMember",
        preserveScroll: true,
        onSuccess: () => {
            addTeamMemberForm.reset();

            modalShowAddedTeamMember.value = true;
            // set modal standards
            typeModal.value = "success";
            gridColumnModal.value = 1;
            titleModal.value = `Team member added to ${props.team.name}`;
            descriptionModal.value = `
            These people have been invited to your team and have been sent an invitation email. They may join the team by accepting the email invitation.
            `;
            firstButtonModal.value = "Close";
            secondButtonModal.value = null;
            thirdButtonModal.value = null;

            // handle click
            firstModalButtonFunction.value = function () {
                // handle show modal for unique content
                modalShowAddedTeamMember.value = false;
            };
            // handle click
            secondModalButtonFunction.value = function () {};
            // handle click
            thirdModalButtonFunction.value = function () {};
            // end modal
        },

        onError: (err) => {},
        onFinish: () => {},
    });
};

const handleCancelTeamInvitation = function (invitation) {
    modalShowCancelTeamInvitation.value = true;

    // set modal standards
    typeModal.value = "danger";
    gridColumnModal.value = 2;
    titleModal.value = "Cancel pending Team Invitation";
    descriptionModal.value = `Are you sure you want to cancel this pending team invitations: ${invitation.email}`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Cancel invitation";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCancelTeamInvitation.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCancelTeamInvitation.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        cancelTeamInvitation(invitation);
    };
    // end modal
};

const cancelTeamInvitation = (invitation) => {
    cancelTeamInvitationForm.delete(
        route("team-invitations.destroy", invitation),
        {
            preserveScroll: true,
            onSuccess: () => (modalShowCancelTeamInvitation.value = false),
            onError: (err) => {
                console.log("Something went wrong chaning role. Error:", err);
            },
        }
    );
};

const manageRole = (teamMember) => {
    managingRoleFor.value = teamMember;
    updateRoleForm.role = teamMember.membership.role;
    modalShowCurrentlyManagingRole.value = true;

    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Change Role ";
    descriptionModal.value = "Are you sure you want to change user role?";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Change";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCurrentlyManagingRole.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCurrentlyManagingRole.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        updateRole();
    };
    // end modal
};

const updateRole = () => {
    updateRoleForm.put(
        route("team-members.update", [props.team, managingRoleFor.value]),
        {
            preserveScroll: true,
            onSuccess: () => (modalShowCurrentlyManagingRole.value = false),
            onError: (err) => {
                console.log("Something went wrong chaning role. Error:", err);
            },
        }
    );
};

const handleLeaveTeam = function () {
    modalShowLeaveTeam.value = true;

    // set modal standards
    typeModal.value = "warning";
    gridColumnModal.value = 2;
    titleModal.value = "Leave team ";
    descriptionModal.value = "Are you sure you want to leave this team?";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Leave team";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowLeaveTeam.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowLeaveTeam.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        leaveTeam();
    };
    // end modal
};

const leaveTeam = () => {
    leaveTeamForm.delete(
        route("team-members.destroy", [props.team, usePage().props.user]),
        {
            preserveScroll: true,
            onError: (err) => {
                console.log("Error leaving team:", err);
            },
            onSuccess: () => {
                modalShowLeaveTeam.value = false;
            },
        }
    );
};

const handleRemoveTeamMember = (teamMember) => {
    // handle show modal for unique content
    modalShowTeamMemberBeingRemoved.value = true;

    // set modal standards
    typeModal.value = "danger";
    gridColumnModal.value = 2;
    titleModal.value = "Remove team member? ";
    descriptionModal.value =
        "Are you sure you want to delete this team member?";
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowTeamMemberBeingRemoved.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowTeamMemberBeingRemoved.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        removeTeamMember(teamMember);
    };
    // end modal
};

const removeTeamMember = function (teamMember) {
    removeTeamMemberForm.delete(
        route("team-members.destroy", [props.team, teamMember]),
        {
            errorBag: "removeTeamMember",
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (modalShowTeamMemberBeingRemoved.value = false),
        }
    );
};

const displayableRole = (role) => {
    return props.availableRoles.find((r) => r.key === role).name;
};
</script>

<template>
    <div>
        <div v-if="userPermissions.canAddTeamMembers">
            <SectionBorder />

            <!-- Add Team Member -->
            <FormSection @submitted="addTeamMember">
                <template #title> Add Team Member </template>

                <template #description>
                    Add a new team member to your team, allowing them to
                    collaborate with you.
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl">
                            <p class="myPrimaryParagraph">
                                Please provide the email address of the person
                                you would like to add to this team.
                            </p>
                        </div>
                    </div>

                    <!-- Member Email -->
                    <div class="myInputGroup">
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            v-model="addTeamMemberForm.email"
                            type="email"
                        />
                        <InputError :message="addTeamMemberForm.errors.email" />
                    </div>

                    <!-- Role -->
                    <div
                        v-if="availableRoles.length > 0"
                        class="col-span-6 lg:col-span-4"
                    >
                        <InputLabel for="roles" value="Role" />
                        <InputError :message="addTeamMemberForm.errors.role" />

                        <div
                            class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer"
                        >
                            <button
                                v-for="(role, i) in availableRoles"
                                :key="role.key"
                                type="button"
                                class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10"
                                :class="{
                                    'rounded-b-none': i > 0,
                                    'rounded-b-none':
                                        i !=
                                        Object.keys(availableRoles).length - 1,
                                }"
                                @click="addTeamMemberForm.role = role.key"
                            >
                                <div
                                    :class="{
                                        'opacity-40':
                                            addTeamMemberForm.role &&
                                            addTeamMemberForm.role != role.key,
                                    }"
                                >
                                    <!-- Role Name -->
                                    <div class="flex items-center">
                                        <div
                                            class="text-sm text-gray-600 dark:text-gray-400"
                                            :class="{
                                                '':
                                                    updateRoleForm.role ===
                                                    role.key,
                                            }"
                                        >
                                            {{ role.name }}
                                        </div>

                                        <svg
                                            v-if="
                                                addTeamMemberForm.role ==
                                                role.key
                                            "
                                            class="ml-2 h-5 w-5 text-green-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>

                                    <!-- Role Description -->
                                    <div
                                        class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-left"
                                    >
                                        {{ role.description }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <SubmitButton
                        :status="addTeamMemberForm.recentlySuccessful"
                        successMessage="Successfully added a team member."
                        :disabled="addTeamMemberForm.processing"
                        buttonText="Add memeber"
                    >
                    </SubmitButton>
                </template>
            </FormSection>
        </div>

        <div v-if="userPermissions.canAddTeamMembers">
            <SectionBorder />

            <!-- Team Member Invitations -->
            <ActionSection class="mt-10 sm:mt-0">
                <template #title> Pending Team Invitations </template>

                <template #description>
                    <div v-if="team.team_invitations.length < 1">
                        No Pending Team Invitations
                    </div>
                    <div v-if="team.team_invitations.length > 0">
                        These people have been invited to your team and have
                        been sent an invitation email. They may join the team by
                        accepting the email invitation.
                    </div>
                </template>

                <!-- Pending Team Member Invitation List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-if="team.team_invitations.length < 1">
                            <p class="myPrimaryParagraph">
                                No people have been invited to your team yet.
                            </p>
                        </div>
                    </div>

                    <div
                        class="flex flex-col myPrimaryGap"
                        v-if="team.team_invitations.length > 0"
                    >
                        <div
                            v-for="invitation in team.team_invitations"
                            :key="invitation.id"
                            class="flex items-center justify-between py-2 px-2 border border-myPrimaryColor-200 rounded-md"
                        >
                            <p class="myPrimaryParagraph">
                                {{ invitation.email }}
                            </p>

                            <div class="flex items-center">
                                <!-- Cancel Team Invitation -->
                                <button
                                    v-if="userPermissions.canRemoveTeamMembers"
                                    class="myPrimaryDeleteButtonNoBackground"
                                    @click="
                                        handleCancelTeamInvitation(invitation)
                                    "
                                >
                                    Cancel Invitation
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>

        <div v-if="team.users.length > 0">
            <SectionBorder />

            <!-- Manage Team Members -->
            <ActionSection class="mt-10 sm:mt-0">
                <template #title> Team Members </template>

                <template #description>
                    All of the people that are part of this team.
                </template>

                <!-- Team Member List -->
                <template #content>
                    <div class="space-y-6">
                        <div
                            v-for="user in team.users"
                            :key="user.id"
                            class="flex items-center justify-between pb-2 border-b"
                        >
                            <div class="flex items-center gap-2 mt-2">
                                <div v-if="user.profile_photo_url && false">
                                    <img
                                        class="object-cover w-12 h-12 rounded-full"
                                        :src="user.profile_photo_url"
                                        :alt="user.first_name"
                                    />
                                </div>

                                <div
                                    v-if="true"
                                    class="h-12 w-12 rounded-full bg-myPrimaryColor-500 flex justify-center items-center text-xs font-semibold text-white"
                                >
                                    {{
                                        user.first_name.charAt(0).toUpperCase()
                                    }}
                                    {{ user.last_name.charAt(0).toUpperCase() }}
                                </div>
                                <div class="flex flex-col items-left gap-1">
                                    <p class="text-xs font-semibold">
                                        {{ user.first_name }}
                                        {{ user.last_name }}
                                    </p>
                                    <p class="text-xs font-semibold">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center myPrimaryGap">
                                <!-- Manage Team Member Role -->
                                <button
                                    v-if="
                                        userPermissions.canAddTeamMembers &&
                                        availableRoles.length
                                    "
                                    class="myPrimaryButtonNoBackground"
                                    @click="manageRole(user)"
                                >
                                    {{ displayableRole(user.membership.role) }}
                                </button>

                                <div
                                    v-else-if="availableRoles.length"
                                    class="myPrimaryParagraph"
                                >
                                    &nbsp;
                                    {{ displayableRole(user.membership.role) }}
                                </div>

                                <!-- Leave Team -->
                                <DangerButton
                                    v-if="$page.props.user.id === user.id"
                                    @click="handleLeaveTeam()"
                                >
                                    Leave Team
                                </DangerButton>

                                <!-- Remove Team Member -->
                                <DangerButton
                                    v-else-if="
                                        userPermissions.canRemoveTeamMembers
                                    "
                                    @click="handleRemoveTeamMember(user)"
                                >
                                    Remove
                                </DangerButton>
                            </div>
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>

        <!-- Cancel Team Invitation -->
        <DynamicModal
            :show="modalShowCancelTeamInvitation"
            :type="typeModal"
            :disabled="cancelTeamInvitationForm.processing"
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

        <!-- Manage Team Members -->
        <DynamicModal
            :show="modalShowCurrentlyManagingRole"
            :type="typeModal"
            :disabled="updateRoleForm.processing"
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
                <div v-if="managingRoleFor">
                    <div
                        class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer"
                    >
                        <button
                            v-for="(role, i) in availableRoles"
                            :key="role.key"
                            type="button"
                            class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10"
                            :class="{
                                'rounded-b-none': i > 0,
                                'rounded-b-none':
                                    i !==
                                    Object.keys(availableRoles).length - 1,
                            }"
                            @click="updateRoleForm.role = role.key"
                        >
                            <div
                                :class="{
                                    'opacity-40':
                                        updateRoleForm.role &&
                                        updateRoleForm.role !== role.key,
                                }"
                            >
                                <!-- Role Name -->
                                <div class="flex items-center">
                                    <div
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                        :class="{
                                            '':
                                                updateRoleForm.role ===
                                                role.key,
                                        }"
                                    >
                                        {{ role.name }}
                                    </div>

                                    <svg
                                        v-if="updateRoleForm.role == role.key"
                                        class="ml-2 h-5 w-5 text-green-400"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>

                                <!-- Role Description -->
                                <div
                                    class="mt-2 text-xs text-gray-600 dark:text-gray-400"
                                >
                                    {{ role.description }}
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </main>
        </DynamicModal>

        <!-- Role Leave Team -->
        <DynamicModal
            :show="modalShowLeaveTeam"
            :type="typeModal"
            :disabled="leaveTeamForm.processing"
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
        <!-- Role Leave Team -->
        <DynamicModal
            :show="modalShowAddedTeamMember"
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

        <!-- Role Management Modal -->
        <DynamicModal
            :show="modalShowTeamMemberBeingRemoved"
            :type="typeModal"
            :disabled="removeTeamMemberForm.processing"
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
    </div>
</template>
