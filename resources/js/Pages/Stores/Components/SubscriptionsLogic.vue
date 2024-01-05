<script setup>
import FriendlyAlert from "@/Components/Alerts/FriendlyAlert.vue";
import CardHeadings from "@/Components/Actions/CardHeadings.vue";

const props = defineProps({
    numberOfPublishedStores: {
        required: true,
    },
    activeSubscriptions: {
        required: true,
    },
});
</script>

<template>
    <div>
        <template v-if="!$page.props.user?.superadmin">
            <div
                class="p-4 my-4 bg-myPrimaryLightGrayColor rounded-full flex items-center justify-center"
            >
                <p class="myPrimaryParagraph font-medium">
                    Subscriptions Status
                </p>
            </div>

            <div class="flex lg:flex-row flex-col myPrimaryGap">
                <template
                    v-if="
                        typeof activeSubscriptions === 'number' &&
                        typeof numberOfPublishedStores === 'number'
                    "
                >
                    <FriendlyAlert
                        :message="`Published Stores: ${numberOfPublishedStores}`"
                    >
                    </FriendlyAlert>
                    <FriendlyAlert
                        :message="`Remaining Stores: ${
                            activeSubscriptions - numberOfPublishedStores
                        }`"
                    >
                    </FriendlyAlert>
                </template>
            </div>

            <template
                v-if="
                    activeSubscriptions <= numberOfPublishedStores &&
                    activeSubscriptions !== 0
                "
            >
                <div class="p-4 my-4 bg-yellow-100 rounded-full">
                    <Link :href="route('stripe.payment.subscription.index')">
                        <p
                            class="myPrimaryParagraph font-medium cursor-pointer"
                        >
                            You cannot create more Stores with the current
                            subscriptions. Update your subscription.
                        </p>
                    </Link>
                </div>
            </template>
            <template v-if="activeSubscriptions === 0">
                <div class="mb-20">
                    <CardHeadings>
                        <template #title
                            >You do not have subscription
                        </template>
                        <template #buttons>
                            <Link
                                class="myPrimaryButton"
                                type="button"
                                :href="
                                    route('stripe.stores.create.subscription')
                                "
                            >
                                <span class="material-symbols-outlined">
                                    add
                                </span>
                                Create Subscription
                            </Link>
                        </template>
                    </CardHeadings>
                </div>
            </template>
        </template>
    </div>
</template>
