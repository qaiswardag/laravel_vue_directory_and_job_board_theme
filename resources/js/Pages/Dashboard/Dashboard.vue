<script setup>
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
    <LoggedInLayout>
        <Head title="Dashboard" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Dashboard</h2>
        </template>

        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <UserDashboardStats
            :user="user"
            :currentUserTeam="currentUserTeam"
            :currentUserTeamRole="currentUserTeamRole"
        ></UserDashboardStats>
    </LoggedInLayout>
</template>
