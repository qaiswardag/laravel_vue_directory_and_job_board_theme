<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import UnqiueResource from "@/Components/PageTemplates/LoggedIn/Team/UnqiueResource.vue";

const props = defineProps({
    post: {
        required: true,
    },
    authors: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
    countries: {
        required: true,
    },
    states: {
        required: true,
    },
    jobTypes: {
        required: true,
    },
    categories: {
        required: true,
    },
    coverImages: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Jobs",
        route: {
            name: "team.jobs.index",
            parameters: [props.currentUserTeam.id],
        },
    },
];
</script>
<template>
    <LoggedInLayout>
        <Head title="Post" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Job</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <div class="myPrimarySection"></div>

        <!-- Show Unique Resorce - start -->
        <UnqiueResource
            :post="post"
            :authors="authors"
            :countries="countries"
            :jobTypes="jobTypes"
            :categories="categories"
            :coverImages="coverImages"
        ></UnqiueResource>
        <!-- sShow Unique Post - end -->
    </LoggedInLayout>
</template>
