<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
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
    <MainLayout>
        <LoggedInLayout>
            <Head :title="`Job | ${post.title}`" />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Job</h2>
            </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <!-- Show Unique Resorce - start -->
            <UnqiueResource
                :onlyForCurrentTeam="true"
                :post="post"
                :authors="authors"
                :countries="countries"
                :jobTypes="jobTypes"
                :categories="categories"
                :coverImages="coverImages"
            ></UnqiueResource>
            <!-- sShow Unique Post - end -->
        </LoggedInLayout>
    </MainLayout>
</template>
