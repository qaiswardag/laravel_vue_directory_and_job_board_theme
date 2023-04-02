<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Posts/Partials/PostForm.vue";

const props = defineProps({
    currentUserTeamRole: {
        required: false,
    },
    currentUserTeamRole: {
        required: true,
    },
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Posts",
        route: {
            name: "overview.posts.index",
            parameters: [props.currentUserTeam],
        },
    },
    { label: "Add Post" },
];
</script>

<template>
    <LoggedInLayout title="Create Post">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Create a New Post</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <PostForm
            :currentUserTeam="currentUserTeam"
            :currentUserTeamRole="currentUserTeamRole"
            :user="user"
        ></PostForm>
    </LoggedInLayout>
</template>
