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
});

const breadcrumbsLinks = [
    {
        label: "All Components",
        route: {
            name: "admin.components",
            parameters: null,
        },
    },
    { label: "Add Component" },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Create Component" />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Create a New Component</h2>
            </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <ComponentForm
                :user="user"
                :currentUserTeam="currentUserTeam"
            ></ComponentForm>
        </LoggedInLayout>
    </MainLayout>
</template>
