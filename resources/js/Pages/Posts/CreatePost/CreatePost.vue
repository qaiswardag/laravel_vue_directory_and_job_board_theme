<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Posts/Partials/PostForm.vue";

const props = defineProps({
    currentUserTeamRole: {
        required: false,
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
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    { label: "Add Post" },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Create Post" />
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
