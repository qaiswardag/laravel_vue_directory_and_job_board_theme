<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Welcome from "@/Components/Welcome/Welcome.vue";
</script>

<template>
    <LoggedInLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </LoggedInLayout>
</template>
