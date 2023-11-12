<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import PostForm from "@/Pages/Posts/Partials/PostForm.vue";
import SubscriptionChargeForm from "@/Pages/Stripe/Partials/SubscriptionChargeForm.vue";
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
            <template #header>
                <h2 class="myPrimaryMainPageHeader">Update Subscription</h2>
            </template>

            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>
            <SubscriptionChargeForm
                :user="user"
                :intent="intent"
                :publishableKey="publishableKey"
                :post="post"
                :products="storeSubscriptionPrices"
            ></SubscriptionChargeForm>
        </LoggedInLayout>
    </MainLayout>
</template>
