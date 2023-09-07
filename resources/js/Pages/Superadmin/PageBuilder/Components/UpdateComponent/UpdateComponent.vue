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
    component: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "All Components",
        route: {
            name: "admin.components.index",
            parameters: null,
        },
    },
    { label: "Update Component" },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Update Compoent" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Update Component</h2>
        </template>

        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <ComponentForm
            :user="user"
            :currentUserTeam="currentUserTeam"
            :component="component"
        ></ComponentForm>
    </LoggedInLayout>
</template>
