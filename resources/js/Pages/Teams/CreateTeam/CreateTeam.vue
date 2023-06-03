<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import CreateTeamForm from "@/Pages/Teams/Partials/CreateTeamForm.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const breadcrumbsLinks = [
    {
        label: "Your Teams",
        route: {
            name: "user.teams",
            parameters: null,
        },
    },
    {
        label: "Create Team",
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Create Team" />
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
        <CreateTeamForm></CreateTeamForm>
    </LoggedInLayout>
</template>
