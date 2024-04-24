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
    stores: {
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
    { label: "Update Campaign" },
];
</script>

<template>
    <Head title="Update Campaign" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Update Campaign </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <PostForm
                :currentUserTeam="currentUserTeam"
                :user="user"
                :post="post"
                :categories="categories"
                :stores="stores"
                :coverImages="coverImages"
            ></PostForm>
        </LoggedInLayout>
    </MainLayout>
</template>
