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
    post: {
        required: true,
    },
    subscriptionTeam: {
        required: false,
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
    { label: "Update Subscription" },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head
                title="Update Subscription
"
            />
            <template #header> Update Subscription </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <ChargeForm
                title="Subscription Form"
                :user="user"
                :intent="intent"
                :publishableKey="publishableKey"
                :post="post"
                :subscriptionTeam="subscriptionTeam"
                :products="storeSubscriptionPrices"
                updatePath="stripe.stores.update.subscription"
            ></ChargeForm>
        </LoggedInLayout>
    </MainLayout>
</template>
