<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
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
    states: {
        required: true,
    },
    countries: {
        required: true,
    },
    coverImages: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "Running Jobs",
        route: {
            name: "team.jobs.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    { label: "Update Job" },
];
</script>

<template>
    <Head title="Update Job" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Update Job </template>
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
                :states="states"
                :countries="countries"
                :coverImages="coverImages"
            ></JobForm>
        </LoggedInLayout>
    </MainLayout>
</template>
