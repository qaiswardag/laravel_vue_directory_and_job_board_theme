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
    categories: {
        required: true,
    },
    states: {
        required: true,
    },
    coverImages: {
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
    <LoggedInLayout>
        <Head title="Post" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Store</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <!-- Show Unique Resorce - start -->
        <UnqiueResource
            :post="post"
            :authors="authors"
            :categories="categories"
            :states="states"
            :coverImages="coverImages"
        ></UnqiueResource>
        <!-- sShow Unique Post - end -->
    </LoggedInLayout>
</template>
