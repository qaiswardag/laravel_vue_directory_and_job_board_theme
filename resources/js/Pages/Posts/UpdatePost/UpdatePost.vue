<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Posts/Partials/PostForm.vue";

const props = defineProps({
    currentUserTeam: {
        required: true,
    },
    user: {
        required: true,
    },
    post: {
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
        label: "All Running posts",
        route: {
            name: "team.posts.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    { label: "Update Post" },
];
</script>

<template>
    <Head title="Update Post" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Update Post </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <PostForm
                :currentUserTeam="currentUserTeam"
                :user="user"
                :post="post"
                :categories="categories"
                :coverImages="coverImages"
            ></PostForm>
        </LoggedInLayout>
    </MainLayout>
</template>
