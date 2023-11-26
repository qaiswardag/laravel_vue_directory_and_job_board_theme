<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
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
    <MainLayout>
        <LoggedInLayout>
            <Head title="Create Team" />
            <template #header> Create Team </template>
            <template #description>
                This is Team index page ipsum dolor sit, amet consectetur
                adipisicing elit. Animi, distinctio.
            </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <CreateTeamForm></CreateTeamForm>
        </LoggedInLayout>
    </MainLayout>
</template>
