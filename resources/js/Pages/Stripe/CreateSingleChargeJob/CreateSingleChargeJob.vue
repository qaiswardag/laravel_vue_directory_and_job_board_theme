<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Posts/Partials/PostForm.vue";
import SingleChargeForm from "@/Pages/Stripe/Partials/SingleChargeForm.vue";
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
        label: "All Jobs",
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
            <Head
                title="Payment
"
            />
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Payment</h2>
            </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <SingleChargeForm
                :user="user"
                :intent="intent"
                :publishableKey="publishableKey"
                :products="jobPrices"
                :job="job"
            ></SingleChargeForm>
        </LoggedInLayout>
    </MainLayout>
</template>
