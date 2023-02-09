<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import DeleteTeamForm from "@/Pages/Teams/Partials/DeleteTeamForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TeamMemberManager from "@/Pages/Teams/Partials/TeamMemberManager.vue";
import UpdateTeamNameForm from "@/Pages/Teams/Partials/UpdateTeamNameForm.vue";

defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
});
</script>

<template>
    <LoggedInLayout title="Team Settings">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight rounded">
                Teams Settings
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
            <UpdateTeamNameForm :team="team"> </UpdateTeamNameForm>

            <TeamMemberManager
                class="mt-10 sm:mt-0"
                :team="team"
                :available-roles="availableRoles"
                :user-permissions="permissions"
            />
            <SectionBorder />
            <DeleteTeamForm class="mt-10 sm:mt-0" :team="team">
            </DeleteTeamForm>
        </div>

        <div class="mt-64">
            <!-- PERMISSIONS START -->
            <!-- PERMISSIONS START -->
            <!-- PERMISSIONS START -->
            <!-- PERMISSIONS START -->
            <div
                class="my-4 border-2 border-myPrimaryBrandColor rounded-lg px-4"
            >
                <h1 class="mySecondaryHeader my-4">Permissions:</h1>
                <hr />
                <p
                    class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                >
                    <span class="text-myErrorColor">
                        Current Permissions:
                    </span>
                    {{ JSON.stringify(permissions) }}
                </p>
                <hr />
                <p
                    class="myPrimaryParagraph my-4 text-indigo-600 flex justify-between items-center"
                >
                    <span class="text-indigo-800">
                        Example for company created by user. Like (teams table:
                        user_id = 1, which is Qais ):
                    </span>

                    {"canAddTeamMembers":true,"canDeleteTeam":true,"canRemoveTeamMembers":true,"canUpdateTeam":true}
                </p>

                <hr />

                <p
                    class="myPrimaryParagraph my-4 text-fuchsia-600 flex justify-between items-center"
                >
                    <span class="text-fuchsia-800">
                        Permissions Added Team Member as Administrator:
                    </span>
                    {"canAddTeamMembers":false,"canDeleteTeam":false,"canRemoveTeamMembers":false,"canUpdateTeam":false}
                </p>
                <hr />
                <p
                    class="myPrimaryParagraph my-4 text-green-600 flex justify-between items-center"
                >
                    <span class="text-green-800">
                        Permissions Added Team Member as Editor:
                    </span>
                    {"canAddTeamMembers":false,"canDeleteTeam":false,"canRemoveTeamMembers":false,"canUpdateTeam":false}
                </p>
            </div>

            <!-- PERMISSIONS END -->
            <!-- PERMISSIONS END -->
            <!-- PERMISSIONS END -->
            <!-- PERMISSIONS END -->

            <!-- TEAM START -->
            <!-- TEAM START -->
            <!-- TEAM START -->
            <!-- TEAM START -->
            <div>
                <div
                    class="my-4 border-2 border-myPrimaryBrandColor rounded-lg px-4"
                >
                    <h1 class="mySecondaryHeader my-4">Team:</h1>
                    <hr />
                    <p
                        class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                    >
                        <span class="text-myErrorColor"> team id: </span>
                        {{ JSON.stringify(team.id) }}
                    </p>
                    <hr />
                    <p
                        class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                    >
                        <span class="text-myErrorColor"> team user id: </span>
                        {{ JSON.stringify(team.user_id) }}
                    </p>
                    <hr />
                    <p
                        class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                    >
                        <span class="text-myErrorColor"> Personal team: </span>
                        {{ JSON.stringify(team.personal_team) }}
                    </p>
                    <hr />
                    <p
                        class="myPrimaryParagraph my-4 text-myErrorColor flex justify-between items-center"
                    >
                        <span class="text-myErrorColor"> Owner: </span>
                        {{ team.owner.first_name }}
                        {{ team.owner.last_name }}
                    </p>
                </div>
            </div>
        </div>

        <!-- TEAM END -->
        <!-- TEAM END -->
        <!-- TEAM END -->
        <!-- TEAM END -->
    </LoggedInLayout>
</template>
