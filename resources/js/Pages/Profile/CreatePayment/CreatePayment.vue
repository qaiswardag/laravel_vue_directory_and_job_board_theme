<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Posts/Partials/PostForm.vue";
import PaymentForm from "@/Pages/Profile/Partials/PaymentForm.vue";

const props = defineProps({
    team: {
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
            parameters: [props.team.id],
        },
    },
    { label: "Create Payment" },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Create Post" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Create Payment</h2>
        </template>

        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>
        <PaymentForm :team="team" :user="user"></PaymentForm>
    </LoggedInLayout>
</template>
