<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import JobForm from "@/Pages/Jobs/Partials/JobForm.vue";

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
    categories: {
        required: true,
    },
    types: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Jobs",
        route: {
            name: "team.jobs.index",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
    { label: "Update Job" },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Update Job" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Update Job</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <JobForm
            :currentUserTeam="currentUserTeam"
            :user="user"
            :post="post"
            :postAuthor="postAuthor"
            :categories="categories"
            :types="types"
        ></JobForm>
    </LoggedInLayout>
</template>
