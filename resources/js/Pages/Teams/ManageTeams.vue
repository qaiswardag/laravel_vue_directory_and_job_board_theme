<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import SwitchTeams from "./Partials/SwitchTeams.vue";
import CreateTeamForm from "@/Pages/Teams/Partials/CreateTeamForm.vue";
import CreateTeamFormOld from "@/Pages/Teams/Partials/CreateTeamFormOld.vue";
</script>

<template>
    <LoggedInLayout title="Manage teams">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Manage teams</h2>
        </template>

        <template v-if="$page.props.user.all_teams.length < 1">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
                <h1 class="myPrimayHeader">Create your first team</h1>
                <p class="myPrimaryParagraph">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                    Maiores impedit perferendis suscipit eaque, iste dolor
                    cupiditate blanditiis.
                </p>
            </div>
        </template>

        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
                <SwitchTeams></SwitchTeams>
            </div>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
            <CreateTeamForm></CreateTeamForm>
        </div>
    </LoggedInLayout>
</template>
