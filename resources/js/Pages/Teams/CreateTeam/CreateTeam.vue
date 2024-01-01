<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import CreateTeamForm from "@/Pages/Teams/Partials/CreateTeamForm.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const breadcrumbsLinks = [
    {
        label: "Your Companies",
        route: {
            name: "user.teams",
            parameters: null,
        },
    },
    {
        label: "Create Company",
    },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Create Company" />
            <template #header> Create Company </template>
            <template #description> Create Company </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <CreateTeamForm></CreateTeamForm>
        </LoggedInLayout>
    </MainLayout>
</template>
