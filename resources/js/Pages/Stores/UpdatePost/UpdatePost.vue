<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Stores/Partials/PostForm.vue";

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
    postAuthor: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Stores",
        route: {
            name: "team.stores.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
    { label: "Update Store" },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Update Store" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Update Store</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <PostForm
            :currentUserTeam="currentUserTeam"
            :user="user"
            :post="post"
            :postAuthor="postAuthor"
        ></PostForm>
    </LoggedInLayout>
</template>
