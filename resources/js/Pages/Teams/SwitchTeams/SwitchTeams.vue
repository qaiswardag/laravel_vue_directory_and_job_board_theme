<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import SwitchTeams from "../Partials/SwitchTeams.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";

const breadcrumbsLinks = [
    {
        label: "Your Teams",
        route: {
            name: "user.teams",
            parameters: null,
        },
    },
    {
        label: "Switch Teams",
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Switch Team" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Switch Teams</h2>
        </template>
        <template #description>
            This is Team index page ipsum dolor sit, amet consectetur
            adipisicing elit. Animi, distinctio.
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <template
            v-if="
                $page.props.user.all_teams.length <= 0 &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <h1 class="myPrimaryHeaderMessage">You are not in any Team</h1>
            <p class="myPrimaryParagraph">Looks like there are no Teams!</p>
        </template>

        <template
            v-if="
                $page.props.user.all_teams.length > 0 &&
                $page.props.jetstream.hasTeamFeatures
            "
        >
            <SwitchTeams></SwitchTeams>
        </template>
    </LoggedInLayout>
</template>
