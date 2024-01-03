<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import StoreForm from "@/Pages/Stores/Partials/StoreForm.vue";

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
    states: {
        required: true,
    },
    coverImages: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Stores",
        route: {
            name: "team.stores.index",
            parameters: [props.currentUserTeam.id],
        },
    },
    { label: "Update Store" },
];
</script>

<template>
    <Head title="Update Store" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Update Store </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <StoreForm
                :currentUserTeam="currentUserTeam"
                :user="user"
                :post="post"
                :postAuthor="postAuthor"
                :categories="categories"
                :states="states"
                :coverImages="coverImages"
            ></StoreForm>
        </LoggedInLayout>
    </MainLayout>
</template>
