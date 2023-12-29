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
        required: false,
    },
    currentUserTeam: {
        required: true,
    },
    categories: {
        required: true,
    },
    stores: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Running campaigns",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
];
</script>
<template>
    <MainLayout>
        <LoggedInLayout>
            <Head :title="`Sale | ${post.title}`" />
            <template #header> Sale </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <!-- Show Unique Resorce - start -->
            <UnqiueResource
                :post="post"
                :team="team"
                :categories="categories"
                :stores="stores"
                :onlyForCurrentTeam="true"
                postType="Campaign"
            ></UnqiueResource>
            <!-- Show Unique Post - end -->
        </LoggedInLayout>
    </MainLayout>
</template>
