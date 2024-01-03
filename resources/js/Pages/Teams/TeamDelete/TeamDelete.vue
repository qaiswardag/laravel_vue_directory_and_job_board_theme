<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
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
        label: "Company Settings",
        route: {
            name: "teams.show",
            parameters: [props.team.id],
        },
    },
    {
        label: "Delete Company",
    },
];
</script>

<template>
    <Head title="Delete Company" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Delete Company </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div v-if="team.owner !== null && team.owner !== undefined">
                <DeleteTeamForm class="mt-10 sm:mt-0" :team="team">
                </DeleteTeamForm>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
