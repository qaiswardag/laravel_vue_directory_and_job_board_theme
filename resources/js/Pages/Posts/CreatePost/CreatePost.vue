<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
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
        label: "All Running posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    { label: "Add Post" },
];
</script>

<template>
    <Head title=" Create Campaign" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Create Campaign </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <PostForm
                :currentUserTeam="currentUserTeam"
                :currentUserTeamRole="currentUserTeamRole"
                :user="user"
            ></PostForm>
        </LoggedInLayout>
    </MainLayout>
</template>
