<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import UserDashboardStats from "../../Components/Dashboard/UserDashboardStats.vue";

import { ref } from "vue";

const props = defineProps({
    user: {
        required: true,
    },
    currentUserTeamRole: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "Dashboard",
        route: {
            name: "dashboard",
            parameters: null,
        },
    },
];

const currentUserDetails = ref(false);
</script>

<template>
    <Head title="Dashboard" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Dashboard </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <UserDashboardStats
                :user="user"
                :currentUserTeam="currentUserTeam"
                :currentUserTeamRole="currentUserTeamRole"
            ></UserDashboardStats>
        </LoggedInLayout>
    </MainLayout>
</template>
