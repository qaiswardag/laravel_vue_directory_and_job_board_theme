<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Posts/Partials/PostForm.vue";
import ChargeForm from "@/Pages/Stripe/Partials/ChargeForm.vue";
import storeSubscriptionPrices from "@/utils/pricing/store-subscription-prices";

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
});

const breadcrumbsLinks = [
    {
        label: "All Subscriptions",
        route: {
            name: "stripe.payment.subscription.index",
            parameters: null,
        },
    },
    { label: "Create Subscription" },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head
                title="Create Subscription
"
            />
            <template #header> Create Subscription </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <ChargeForm
                title="Subscription Form"
                :user="user"
                :intent="intent"
                :publishableKey="publishableKey"
                :products="storeSubscriptionPrices"
                createPath="stripe.stores.store.subscription"
                nameOfCharge="storeSubscription"
            ></ChargeForm>
        </LoggedInLayout>
    </MainLayout>
</template>
