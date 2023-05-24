<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Dashboard from "@/Components/Dashboard/Dashboard.vue";

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
</script>

<template>
    <LoggedInLayout title="Dashboard">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Dashboard</h2>
        </template>
        <Dashboard />

        <div class="myPrimarySection overflow-hidden">
            <p class="mb-8 mt-12 pb-4 whitespace-pre-line">
                <span class="font-normal pb-2 uppercase"> user: </span>
                <br />
                {{ user }}
            </p>
            <p class="my-8 pb-16 whitespace-pre-line">
                <span class="font-normal pb-2 uppercase">
                    currentUserTeamRole from shared Data:
                </span>
                <br />
                {{ currentUserTeamRole }}
            </p>
            <p class="my-8 pb-16 whitespace-pre-line">
                <span class="font-normal pb-2 uppercase">
                    currentUserTeam from shared Data:
                </span>
                <br />
                {{ currentUserTeam }}
            </p>
        </div>
    </LoggedInLayout>
</template>
