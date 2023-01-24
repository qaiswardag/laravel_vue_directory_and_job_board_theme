<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import CreateTeamForm from "@/Pages/Teams/Partials/CreateTeamForm.vue";
</script>

<template>
    <LoggedInLayout title="Create Team">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Create Team</h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
                <CreateTeamForm />
            </div>
        </div>
    </LoggedInLayout>
</template>
