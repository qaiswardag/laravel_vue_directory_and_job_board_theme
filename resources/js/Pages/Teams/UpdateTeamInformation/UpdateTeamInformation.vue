<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
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
        label: "Company Settings",
        route: {
            name: "teams.show",
            parameters: [props.team.id],
        },
    },
    {
        label: "Update Company",
    },
];
</script>

<template>
    <Head title="Update Company" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Update Company </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div v-if="team.owner !== null && team.owner !== undefined">
                <UpdateTeamNameForm :team="team"> </UpdateTeamNameForm>
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
