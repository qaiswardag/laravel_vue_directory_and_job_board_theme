<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Stores/Partials/PostForm.vue";

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
        label: "All Stores",
        route: {
            name: "team.stores.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
    { label: "Add Store" },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Create Store" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Create a New Store</h2>
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
