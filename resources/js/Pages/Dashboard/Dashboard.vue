<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Dashboard from "@/Components/Dashboard/Dashboard.vue";

const props = defineProps({
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
});

const people = [
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    {
        name: "Lindsay Walton",
        title: "Front-end Developer",
        email: "lindsay.walton@example.com",
        role: "Member",
    },
    // More people...
];
</script>

<template>
    <LoggedInLayout title="Dashboard">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Dashboard</h2>
        </template>
        <Dashboard />

        <div class="myPrimarySection overflow-hidden">
            <p class="my-4 pb-4 whitespace-pre-line">
                currentUserTeam from shared Data is:
            </p>
            <p class="my-4 whitespace-pre-line">{{ currentUserTeam }}</p>
            <p class="mb-4 mt-12 pb-4 whitespace-pre-line">user:</p>
            <p class="my-4 whitespace-pre-line">{{ user }}</p>
        </div>
    </LoggedInLayout>
</template>
