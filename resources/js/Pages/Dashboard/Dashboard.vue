<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Welcome from "@/Components/Welcome/Welcome.vue";
</script>

<template>
    <LoggedInLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Dashboard</h2>
        </template>

        <div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <Welcome />
            </div>
        </div>
    </LoggedInLayout>
</template>
