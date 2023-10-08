<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import JobForm from "@/Pages/Jobs/Partials/JobForm.vue";

const props = defineProps({
    currentUserTeamRole: {
        required: false,
    },
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Jobs",
        route: {
            name: "team.jobs.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    { label: "Add Job" },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Create Job" />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Create a New Job</h2>
            </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <JobForm
                :currentUserTeam="currentUserTeam"
                :currentUserTeamRole="currentUserTeamRole"
                :user="user"
            ></JobForm>
        </LoggedInLayout>
    </MainLayout>
</template>
