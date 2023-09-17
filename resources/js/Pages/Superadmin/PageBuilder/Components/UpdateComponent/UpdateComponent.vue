<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import ComponentForm from "@/Pages/Superadmin/PageBuilder/Components/Partials/ComponentForm.vue";

const props = defineProps({
    user: {
        required: true,
    },
    currentUserTeam: {
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
        label: "All Components",
        route: {
            name: "admin.components",
            parameters: null,
        },
    },
    { label: "Update Component" },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Update Component" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Update Component</h2>
        </template>

        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <ComponentForm
            :user="user"
            :currentUserTeam="currentUserTeam"
            :post="post"
            :categories="categories"
            :coverImages="coverImages"
        ></ComponentForm>
    </LoggedInLayout>
</template>
