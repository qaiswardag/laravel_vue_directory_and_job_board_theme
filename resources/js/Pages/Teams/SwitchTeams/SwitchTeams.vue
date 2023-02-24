<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import SwitchTeams from "../Partials/SwitchTeams.vue";
import CreateTeamForm from "@/Pages/Teams/Partials/CreateTeamForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const breadcrumbsLinks = [
    {
        label: "Your Teams",
        url: "user.teams",
    },
    {
        label: "Switch Teams",
    },
];
</script>

<template>
    <LoggedInLayout title="Switch Teams">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Switch Teams</h2>
        </template>
        <template #description>
            This is Team index page ipsum dolor sit, amet consectetur
            adipisicing elit. Animi, distinctio.
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <div
            class="myPrimarySection text-center"
            v-if="
                $page.props.user.all_teams.length <= 0 &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <!-- TODO: make a componenet for this kind of text -->
            <h1 class="myPrimaryHeader">You are not in any Team</h1>
        </div>

        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <SwitchTeams></SwitchTeams>
        </template>
    </LoggedInLayout>
</template>
