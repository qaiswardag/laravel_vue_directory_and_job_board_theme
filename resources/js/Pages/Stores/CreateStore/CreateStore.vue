<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import StoreForm from "@/Pages/Stores/Partials/StoreForm.vue";

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
            parameters: [props.currentUserTeam.id],
        },
    },
    { label: "Add Store" },
];
</script>

<template>
    <Head title="Create Listing" />

    <MainLayout>
        <LoggedInLayout>
            <template #header> Create a New Listing </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <StoreForm
                :currentUserTeam="currentUserTeam"
                :currentUserTeamRole="currentUserTeamRole"
                :user="user"
            ></StoreForm>
        </LoggedInLayout>
    </MainLayout>
</template>
