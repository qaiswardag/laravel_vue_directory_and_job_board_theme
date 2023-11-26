<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
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
    <MainLayout>
        <LoggedInLayout>
            <Head title="Switch Team" />
            <template #header> Switch Teams </template>
            <template #description>
                This is Team index page ipsum dolor sit, amet consectetur
                adipisicing elit. Animi, distinctio.
            </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div
                v-if="
                    $page.props.user.all_teams.length <= 0 &&
                    $page.props.jetstream.hasTeamFeatures
                "
            >
                <h1 class="myTertiaryHeader">No Teams</h1>
                <p class="myPrimaryParagraph">Looks like there are no Teams!</p>
            </div>

            <template
                v-if="
                    $page.props.user.all_teams.length > 0 &&
                    $page.props.jetstream.hasTeamFeatures
                "
            >
                <SwitchTeams></SwitchTeams>
            </template>
        </LoggedInLayout>
    </MainLayout>
</template>
