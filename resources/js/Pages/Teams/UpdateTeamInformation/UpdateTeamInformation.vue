<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import UpdateTeamNameForm from "@/Pages/Teams/Partials/UpdateTeamNameForm.vue";
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
        label: "Update Team",
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Update Team" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Update Team</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <div v-if="team.owner !== null && team.owner !== undefined">
            <UpdateTeamNameForm :team="team"> </UpdateTeamNameForm>
        </div>
    </LoggedInLayout>
</template>
