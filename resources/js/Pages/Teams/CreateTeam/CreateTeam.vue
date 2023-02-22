<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import SwitchTeams from "../Partials/SwitchTeams.vue";
import CreateTeamForm from "@/Pages/Teams/Partials/CreateTeamForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
</script>

<template>
    <LoggedInLayout title="Your Teams">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Create Team</h2>
        </template>

        <template #description>
            This is Team index page ipsum dolor sit, amet consectetur
            adipisicing elit. Animi, distinctio.
        </template>
        <SectionBorder />
        <CreateTeamForm></CreateTeamForm>
    </LoggedInLayout>
</template>
