<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import UnqiueResource from "@/Components/PageTemplates/LoggedIn/Team/UnqiueResource.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    post: {
        required: true,
    },
    team: {
        required: true,
    },
    authors: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
    categories: {
        required: true,
    },
    states: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Stores",
        route: {
            name: "team.stores.index",
            parameters: [props.currentUserTeam.id],
        },
    },
];
</script>
<template>
    <MainLayout>
        <LoggedInLayout>
            <Head :title="`Store | ${post.title}`" />
            <template #header> Store </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <!-- Show Unique Resorce - start -->
            <UnqiueResource
                :post="post"
                :team="team"
                :authors="authors"
                :categories="categories"
                :states="states"
                :onlyForCurrentTeam="true"
                postType="Store"
            ></UnqiueResource>
            <!-- sShow Unique Post - end -->
        </LoggedInLayout>
    </MainLayout>
</template>
