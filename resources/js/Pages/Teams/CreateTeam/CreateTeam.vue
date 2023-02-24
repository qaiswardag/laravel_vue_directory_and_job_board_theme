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
        label: "Create Team",
    },
];
</script>

<template>
    <LoggedInLayout title="Create Team">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Create Team</h2>
        </template>
        <template #description>
            This is Team index page ipsum dolor sit, amet consectetur
            adipisicing elit. Animi, distinctio.
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <SectionBorder />
        <CreateTeamForm></CreateTeamForm>
    </LoggedInLayout>
</template>
