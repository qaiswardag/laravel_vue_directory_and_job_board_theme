<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import SwitchTeams from "./Partials/SwitchTeams.vue";
import CreateTeamForm from "@/Pages/Teams/Partials/CreateTeamForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
</script>

<template>
    <LoggedInLayout title="Manage teams">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Manage Teams</h2>
        </template>

        <template v-if="$page.props.user.all_teams.length < 1">
            <div>
                <h1 class="myPrimayHeader">Create your first Team</h1>
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
            <SwitchTeams></SwitchTeams>
        </template>

        <SectionBorder />
        <CreateTeamForm></CreateTeamForm>
    </LoggedInLayout>
</template>
