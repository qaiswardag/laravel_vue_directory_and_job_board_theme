<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
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
    <MainLayout>
        <LoggedInLayout>
            <Head title="Update Component" />
            <template #header> Update Component </template>

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
    </MainLayout>
</template>
