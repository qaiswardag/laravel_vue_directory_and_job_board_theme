<script setup>
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
        <StoreForm
            :currentUserTeam="currentUserTeam"
            :user="user"
            :post="post"
            :postAuthor="postAuthor"
            :categories="categories"
        ></StoreForm>
    </LoggedInLayout>
</template>
