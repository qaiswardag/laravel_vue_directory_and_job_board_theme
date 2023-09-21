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
        route: {
            name: "teams.show",
            parameters: [props.team.id],
        },
    },
    {
        label: "Delete Team",
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Delete Team" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Delete Team</h2>
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
