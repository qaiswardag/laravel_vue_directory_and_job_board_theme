<script setup>
import { ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import ActionSection from "@/Components/Actions/ActionSection.vue";
import DangerButton from "@/Components/Buttons/DangerButton.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { PencilIcon } from "@heroicons/vue/24/outline";
import UserTag from "@/Components/Users/UserTag.vue";

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
            titleModal.value = `Company member added to ${props.team.name}`;
            descriptionModal.value = `
           New Company Member have been invited to your team and have been sent an invitation email. The new Member may join the team by accepting the email invitation.
            `;
            firstButtonModal.value = null;
            secondButtonModal.value = null;
            thirdButtonModal.value = "Close";

            // handle click
            firstModalButtonFunction.value = function () {
                // handle show modal for unique content
                modalShowAddedTeamMember.value = false;
            };
            // handle click
            secondModalButtonFunction.value = function () {};
            // handle click
            thirdModalButtonFunction.value = function () {
                // handle show modal for unique content
                modalShowAddedTeamMember.value = false;
            };
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
    titleModal.value = "Cancel pending Company Invitation";
    descriptionModal.value = `Are you sure you want to cancel Pending Company Invitation for: ${invitation.email}`;
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
            onError: (err) => {},
            onFinish: (err) => {
                modalShowCancelTeamInvitation.value = false;
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
            onError: (err) => {},
            onFinish: () => {
                modalShowCurrentlyManagingRole.value = false;
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
            onError: (err) => {},
            onSuccess: () => {
                modalShowLeaveTeam.value = false;
            },
            onFinish: () => {
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
            onError: (err) => {
                modalShowTeamMemberBeingRemoved.value = false;
            },
            onFinish: (log) => {
                modalShowTeamMemberBeingRemoved.value = false;
            },
        }
    );
};

const displayAbleRole = (role) => {
    return props.availableRoles.find((r) => r.key === role).name;
};
</script>

<template>
    <div>
        <div>
            <!-- Add Company Member -->
            <FormSection @submitted="addTeamMember" :sidebarArea="false">
                <template #title> Add Company Member </template>

                <template #description>
                    Add a new team member to your Company, allowing them to
                    collaborate with you.
                </template>

                <template #main>
                    <div class="flex gap-2 justify-center items-center">
                        <UserTag
                            v-if="
                                $page.props.team.owner.id !==
                                $page.props.user.id
                            "
                            :user="$page.props.team.owner"
                            :showTeamRole="true"
                            :currentUserTeamRole="{ key: 'owner' }"
                            :showJobTitle="true"
                        >
                        </UserTag>
                    </div>
                    <div class="myInputsOrganization">
                        <!-- Member Email -->
                        <div class="myInputGroup">
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                v-model="addTeamMemberForm.email"
                                type="email"
                            />
                            <InputError
                                :message="addTeamMemberForm.errors.email"
                            />

                            <!-- Role -->
                            <div
                                v-if="availableRoles.length > 0"
                                class="col-span-6 lg:col-span-4"
                            >
                                <InputLabel
                                    for="roles"
                                    value="Define a Role for the user"
                                />

                                <div
                                    class="bg-white relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer"
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
                                                Object.keys(availableRoles)
                                                    .length -
                                                    1,
                                        }"
                                        @click="
                                            addTeamMemberForm.role = role.key
                                        "
                                    >
                                        <div
                                            :class="{
                                                'opacity-40':
                                                    addTeamMemberForm.role &&
                                                    addTeamMemberForm.role !=
                                                        role.key,
                                            }"
                                        >
                                            <!-- Role Name -->
                                            <div class="flex items-center">
                                                <div
                                                    class="text-sm text-myPrimaryDarkGrayColor dark:text-myPrimaryLightTextColor"
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
                                                    class="ml-2 h-5 w-5 text-myPrimaryLinkColor"
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
                                                class="mt-2 text-xs text-myPrimaryDarkGrayColor dark:text-myPrimaryLightTextColor text-left"
                                            >
                                                {{ role.description }}
                                            </div>
                                        </div>
                                    </button>
                                </div>
                                <InputError
                                    :message="addTeamMemberForm.errors.role"
                                />
                            </div>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <SubmitButton
                        :disabled="addTeamMemberForm.processing"
                        buttonText="Add memeber"
                    >
                    </SubmitButton>
                </template>
            </FormSection>
        </div>

        <div>
            <SectionBorder />

            <!-- Company Member Invitations -->
            <ActionSection :sidebarArea="false" :actionsArea="false">
                <template #title> Pending Company Invitations </template>

                <template #description>
                    <div v-if="team.team_invitations.length < 1">
                        No Pending Company Invitations
                    </div>
                    <div v-if="team.team_invitations.length > 0">
                        These people have been invited to your Company and have
                        been sent an invitation email. They may join the Company
                        by accepting the email invitation.
                    </div>
                </template>

                <!-- Pending Company Member Invitation List -->
                <template #main>
                    <div class="myInputsOrganization">
                        <div class="myInputGroup">
                            <div class="space-y-6">
                                <div v-if="team.team_invitations.length < 1">
                                    <p class="myPrimaryParagraph">
                                        No Pending Company Invitations
                                    </p>
                                </div>
                            </div>

                            <div v-if="team.team_invitations.length > 0">
                                <div class="myTableContainer">
                                    <div class="myTableSubContainer">
                                        <table class="myPrimaryTable">
                                            <caption
                                                class="myPrimaryTableCaption"
                                            >
                                                Manage Pending Company
                                                Invitations
                                            </caption>
                                            <thead class="myPrimaryTableTHead">
                                                <tr class="myPrimaryTableTr">
                                                    <th
                                                        scope="col"
                                                        class="myPrimaryTableTh"
                                                    >
                                                        Email
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="myPrimaryTableTh"
                                                    >
                                                        Role
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="myPrimaryTableTh"
                                                    >
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="myPrimaryTableTBody">
                                                <TransitionGroup name="table">
                                                    <tr
                                                        v-for="invitation in team.team_invitations"
                                                        :key="invitation.id"
                                                        class="myPrimaryTableTBodyTr"
                                                    >
                                                        <td
                                                            class="myPrimaryTableTBodyTd"
                                                        >
                                                            {{
                                                                invitation.email
                                                            }}
                                                        </td>
                                                        <td
                                                            class="myPrimaryTableTBodyTd"
                                                        >
                                                            {{
                                                                invitation.role
                                                            }}
                                                        </td>

                                                        <!-- Cancel Company Invitation -->
                                                        <td
                                                            class="myPrimaryTableTBodyTd"
                                                        >
                                                            <button
                                                                class="myPrimaryDeleteButton"
                                                                @click="
                                                                    handleCancelTeamInvitation(
                                                                        invitation
                                                                    )
                                                                "
                                                            >
                                                                Cancel
                                                                Invitation
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </TransitionGroup>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ActionMessage
                        :on="cancelTeamInvitationForm.recentlySuccessful"
                        type="success"
                    >
                        Successfully deleted pending invition.
                    </ActionMessage>
                </template>
            </ActionSection>
        </div>

        <div v-if="team.users.length > 0">
            <SectionBorder />

            <!-- Manage Company Members -->
            <ActionSection :sidebarArea="false" :actionsArea="false">
                <template #title>
                    Company Members at
                    {{ $page.props.user && $page.props.user.current_team.name }}
                </template>

                <template #description>
                    People that are part of
                    {{ $page.props.user && $page.props.user.current_team.name }}
                    <div class="flex gap-2 justify-center items-center">
                        <UserTag
                            v-if="
                                $page.props.team.owner.id !==
                                $page.props.user.id
                            "
                            :user="$page.props.team.owner"
                            :showTeamRole="true"
                            :currentUserTeamRole="{ key: 'owner' }"
                            :showJobTitle="true"
                        >
                        </UserTag>
                    </div>
                </template>

                <!-- Company Member List -->
                <template #main>
                    <div class="myInputsOrganization">
                        <div class="myTableContainer">
                            <div class="myTableSubContainer">
                                <table class="myPrimaryTable">
                                    <caption class="myPrimaryTableCaption">
                                        Company
                                        <span class="font-normal">
                                            {{
                                                $page.props.user.current_team
                                                    .name
                                            }}
                                        </span>
                                    </caption>
                                    <thead class="myPrimaryTableTHead">
                                        <tr class="myPrimaryTableTr">
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                User
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Role
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Action
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="myPrimaryTableTBody">
                                        <TransitionGroup name="table">
                                            <tr
                                                v-for="user in team &&
                                                team.users"
                                                :key="user.id"
                                                class="myPrimaryTableTBodyTr"
                                            >
                                                <template
                                                    v-if="!user.superadmin"
                                                >
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <UserTag
                                                            :user="user"
                                                            :showTeamRole="true"
                                                            :showJobTitle="true"
                                                        >
                                                        </UserTag>
                                                    </td>
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <!-- Manage Company Member Role -->
                                                        <div
                                                            v-if="
                                                                availableRoles.length
                                                            "
                                                            class="myPrimaryTag inline-block"
                                                        >
                                                            <div
                                                                class="flex items-center myPrimaryGap"
                                                            >
                                                                <div
                                                                    class="text-xs font-medium"
                                                                >
                                                                    <div
                                                                        class="font-normal text-xs"
                                                                    >
                                                                        role:
                                                                    </div>
                                                                    {{
                                                                        displayAbleRole(
                                                                            user
                                                                                .membership
                                                                                .role
                                                                        )
                                                                    }}
                                                                </div>
                                                                <button
                                                                    @click="
                                                                        manageRole(
                                                                            user
                                                                        )
                                                                    "
                                                                    type="button"
                                                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                                >
                                                                    <span
                                                                        class="material-symbols-outlined"
                                                                    >
                                                                        edit
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Cancel Company Invitation -->
                                                    <td
                                                        class="myPrimaryTableTBodyTd"
                                                    >
                                                        <!-- Leave Company -->
                                                        <div
                                                            class="flex items-center myPrimaryGap"
                                                        >
                                                            <DangerButton
                                                                v-if="
                                                                    $page.props
                                                                        .user
                                                                        .id ===
                                                                    user.id
                                                                "
                                                                @click="
                                                                    handleLeaveTeam()
                                                                "
                                                            >
                                                                Leave Company
                                                            </DangerButton>
                                                            <p
                                                                class="myPrimaryParagraph flex gap-1 items-center bg-myPrimaryLightGrayColor text-black rounded-full py-2 px-4 font-normal"
                                                                v-if="
                                                                    $page.props
                                                                        .user
                                                                        .id ===
                                                                    user.id
                                                                "
                                                            >
                                                                <svg
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke-width="1.5"
                                                                    stroke="currentColor"
                                                                    class="w-4 h-4"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                                                                    />
                                                                </svg>

                                                                <span>
                                                                    You
                                                                </span>
                                                            </p>

                                                            <!-- Remove Company Member -->
                                                            <DangerButton
                                                                @click="
                                                                    handleRemoveTeamMember(
                                                                        user
                                                                    )
                                                                "
                                                            >
                                                                Remove User
                                                            </DangerButton>
                                                        </div>
                                                    </td>
                                                </template>
                                            </tr>
                                        </TransitionGroup>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </template>
                <template #actions>
                    <ActionMessage
                        :on="removeTeamMemberForm.recentlySuccessful"
                        type="success"
                    >
                        Successfully Updated Company Member List.
                    </ActionMessage>
                    <ActionMessage
                        :on="updateRoleForm.recentlySuccessful"
                        type="success"
                    >
                        Successfully Updated Company Member Role.
                    </ActionMessage>
                </template>
            </ActionSection>
        </div>

        <!-- Cancel Company Invitation -->
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

        <!-- Manage Company Members -->
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
                        class="bg-white relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer"
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
                                        class="text-sm text-myPrimaryDarkGrayColor dark:text-myPrimaryLightTextColor"
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
                                        class="ml-2 h-5 w-5 text-myPrimaryLinkColor"
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
                                    class="mt-2 text-xs text-myPrimaryDarkGrayColor dark:text-myPrimaryLightTextColor"
                                >
                                    {{ role.description }}
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </main>
        </DynamicModal>

        <!-- Role Leave Company -->
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
        <!-- Role Leave Company -->
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
