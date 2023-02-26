<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import DeleteTeamForm from "@/Pages/Teams/Partials/DeleteTeamForm.vue";
import { router } from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const props = defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
});

const breadcrumbsLinks = [
    {
        label: "Team Settings",
        url: "teams.show",
        parameter: props.team,
    },
    {
        label: "Team Delete",
    },
];
</script>

<template>
    <LoggedInLayout title="Team Delete">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Team Delete</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <div v-if="team.owner !== null && team.owner !== undefined">
            <DeleteTeamForm class="mt-10 sm:mt-0" :team="team">
            </DeleteTeamForm>
        </div>
    </LoggedInLayout>
</template>
