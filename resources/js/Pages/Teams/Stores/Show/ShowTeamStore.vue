<script setup>
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import ShowUniquePost from "../../../Posts/Show/ShowUniquePost.vue";

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
});

const breadcrumbsLinks = [
    {
        label: "All Posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
];
</script>
<template>
    <LoggedInLayout>
        <Head title="Post" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Team Post</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <div class="myPrimarySection"></div>

        <!-- sShow Unique Post - start -->
        <ShowUniquePost :post="post" :authors="authors"></ShowUniquePost>
        <!-- sShow Unique Post - end -->
    </LoggedInLayout>
</template>
