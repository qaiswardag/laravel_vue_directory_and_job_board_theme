<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Jobs/Partials/PostForm.vue";

const props = defineProps({
    currentUserTeam: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Components",
        route: {
            name: "admin.components.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
    { label: "Add Job" },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Create Compoent" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Create a New Component</h2>
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
