<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Posts/Partials/PostForm.vue";
import ChargeForm from "@/Pages/Stripe/Partials/ChargeForm.vue";

import jobPrices from "@/utils/pricing/job-prices";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    user: {
        required: true,
    },
    intent: {
        required: true,
    },
    publishableKey: {
        required: true,
    },
    job: {
        required: true,
    },
});

const breadcrumbsLinks = [
    {
        label: "Running Jobs",
        route: {
            name: "team.jobs.index",
            parameters: usePage().props.currentUserTeam.id,
        },
    },
    { label: "Payment" },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Payment" />
            <template #header> Payment </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <ChargeForm
                title="Payment job"
                nextRouteName="team.jobs.index"
                :user="user"
                :intent="intent"
                :publishableKey="publishableKey"
                :products="jobPrices"
                :resource="job"
                createPath="stripe.single.charge.job.store"
            ></ChargeForm>
        </LoggedInLayout>
    </MainLayout>
</template>
