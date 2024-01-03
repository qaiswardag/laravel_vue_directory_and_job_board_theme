<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import TeamMemberManager from "@/Pages/Teams/Partials/TeamMemberManager.vue";
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
        label: "Company Members",
    },
];
</script>

<template>
    <Head title="Company Memebers" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Company Members </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div v-if="team.owner !== null && team.owner !== undefined">
                <TeamMemberManager
                    class="mt-10 sm:mt-0"
                    :team="team"
                    :available-roles="availableRoles"
                    :user-permissions="permissions"
                />
            </div>
        </LoggedInLayout>
    </MainLayout>
</template>
