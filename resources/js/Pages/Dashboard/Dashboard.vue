<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Dashboard from "@/Components/Dashboard/Dashboard.vue";
</script>

<template>
    <LoggedInLayout title="Dashboard">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Dashboard</h2>
        </template>

        <div>
            <div class="mt-24">
                <Dashboard />
            </div>
        </div>
    </LoggedInLayout>
</template>
